<?php

require 'Regex.php';

class CodeHighlight extends Regex
{
    
    private static $stm = '#008000';
    
    private static $tag = '#FF0000';
    
    private static $qot = '#FF0000';
    
    private static $var = '#2071ED';
    
    private static $prd = '#0000FF';
    
    private static $adn = '#0000FF';
    
    private static $com = '#FEA500';
    
    private static $con = '#8c4d03';
    
    private static $num = '#800080';
    
    private static $cst = '#038c8c';
    
    private static $ocb = '#038c8c';
    
    private static $occ = '#7F5217';
    
    private static $bbk = '#F46164';
    
    private static $allow_esc = true;
    
    private static $add_slashes = true;
    
    private static $italic_comment = true;
    
    
    
    /*
    * update an existing property(color) value
    *
    * returns null
    * @param property(color) to update
    * @param new property value
    */
    public static function set($prop_name, $values)
    {
        self::$$prop_name = $values;
    }
    
    /*
    * add slashes to qoute inside comment or
    * qoute box
    *
    * returns string
    */
    private static function addSlashes($string)
    {
        /*preg_match_all(self::$com_addslash, $string, $matches);
        foreach ($matches[0] as $matched) {
            $string = str_replace($matched, addslashes($matched), $string);        
        }
        preg_match_all(self::$mcm_addslash, $string, $matches);
        var_dump($matches);
        foreach ($matches[0] as $matched) {
            $string = str_replace($matched, addslashes($matched), $string);        
        }*/
        return $string;
    }
    
    /*
    * searches and strips out any code styling 
    * inside multi line comment or qoute block
    *
    * returns string
    * @param string to search
    */
    private static function stripTags($code)
    {
        $code .= '
        <script>var p = document.getElementsByClassName("stp");
            for(i = 0; i < p.length; i++) {
                p[i].innerHTML = p[i].innerHTML.replace(/<(?!br\s*\/?)[^>]+>/g, "");
            }
        </script>
        ';
        if (self::$allow_esc) {
            $code = str_replace(array("\'", '\"'), 
                                array("'", '"'), $code);    
        }
        return $code;
    }
    
    /*
    * Perform a regular expression search and replace
    *
    * returns string
    * @param The pattern to search for. It can be 
    * either a string or an array with strings
    *
    * @param The string or an array with strings to replace
    * @param The string or an array with strings to 
    * search and replace
    */
    private static function PR($pattern, $replacement, $subject)
    {
        return preg_replace($pattern, $replacement, $subject);
    }
    
    /*
    * Assigns a tag and style to a particular string
    *
    * returns string
    * @param string to be styled
    * @param color to be assigned
    */
    private static function color($string, $color, $font = null)
    {
        $cls = '~SM';
        
        if (substr($color, 0, 1) == '#') {
            $color = str_replace('#', '~~', $color);    
        }
        if ($font == 'fnt' && self::$italic_comment) {
            $font = ';font-style~italic';    
        }
        elseif ($font == 'cls') {
            $font = null;
            $cls = '~CSM';
        }
        elseif ($font == 'clf') {
            $font = ';font-style~italic';
            $cls = '~CSM';
        }
        return '~SO'. $color . $font . $cls . $string . '~SC';
    }
    
    /*
    * Converts *~~ and ~~ added on compile time with =" and "
    * an many more due to some html elements matches with
    * some php opperators.
    *
    * we gonna find a way to replace the 
    * tags that matches and thus creating an unreausabel
    * string
    *
    * case 1 ~SO
    * case 2 ~SM
    * case 3 ~SC
    * case 4 ~CSM
    * case 5 yle~ital
    * case 6 color:~~
    *
    * returns string
    * @param unreplaced string
    * @param return striped version (bool)
    */
    private static function makeQoute($code, $strip = true)
    {
        $pattern = array('/~SO/', '/color:~~/',
                         '/~SM/', '/~SC/',
                         '/~CSM/', '/yle~ital/'
                        );
                        
        $replacement = array('<span style="color:',
                             'color:#',  ';">', '</span>',
                             ';" class="stp">', 'yle:ital'
                        );
                        
       $code =  preg_replace($pattern, $replacement, $code);
       if ($strip) {
            return self::stripTags($code);  
       }
       else {
           return $code;
       }
    }
    

    /*
    * strips the custom tage we made at self::color
    *
    * returns string
    * @param string to striped
    */
    public static function strip($string)
    {
        $pathern = '/~SO(.*?)~SM(.*?)~SC/';
        return preg_replace($pathern, "$2", $string);
    }
    
    /*
    * searches and strips out any code styling 
    * inside single line comment block
    *
    * returns string
    * @param string to search
    */
    private static function stripinComments($code, $check_back = false)
    {
        if (preg_match(self::$com_arr, $code, $matches)) {
            $code = str_replace($matches[0],  
                    self::color(strip_tags(
                    self::makeQoute($matches[0], false)),
                    self::$com, 'clf'), $code);
        }
        return $code;
    }
       
    /*
    * searches the entire line and replaces any string
    * with upercase alone
    *
    * returns string
    * @param code line to search
    */ 
    private static function MatchConst($code)
    {
        $code_line = self::strip($code);
        $const_num = preg_split('/[\.\+\-\*\&\%\@\!\,\(\)\;\=\<\>]+/', $code_line);
        foreach ($const_num as $new_cn) {
            if (preg_match('/^[A-Z_]+$/', ltrim(trim($new_cn), 'const '), $matches)) {
                
                /* make sure matched constant is not TRUE, FALSE or NULL */
                if (!in_array($matches[0], array('TRUE', 'FALSE', 'NULL'))) {
                    $code = str_replace($matches[0], 
                                self::color($matches[0], self::$con),
                            $code);
                }
            }
        }
        return $code;
    }
    
    /*
    * check for php predefined functions
    *
    * returns string
    * @param string to search
    */
    private static function isPreDef($code)
    {
        $replaced = null;
        foreach (preg_split ('/$\R?^/m', $code) as $code_lines) {
            //var_dump($code_lines);
            $code_lines = self::stripinComments($code_lines);
            $code_lines = self::MatchConst($code_lines);

            $replaced .= $code_lines;
            preg_match_all("/([\w]+)(\s*)\(/", $code_lines, $matches);
                /*
                *
                * lets sort arrays by string lenght to prevent function like
                * time overiding function like strtotime.
                *
                * we wonna make sure it goes from function with more character
                * to function functions with less characters
                *
                */
                usort($matches[1], function($match, $with){
                    return strlen($with) - strlen($match);
                });
                
                foreach ($matches[1] as $function) {
                    $function = ltrim($function, '~SC');
                    
                    if (function_exists($function)) {
                        
                        //prevent custom function name highlighting
                        $pathern =     '/(?<!function~SC )' . $function . '\s*\(/';
                        $replaced = self::PR($pathern, 
                                        self::color($function . '(', 
                                        self::$prd), $replaced
                                    );
                                    
                        
                    }
                }
        }
        return $replaced;
    }
    
    private static function format($code)
    {
        $code = htmlspecialchars($code, ENT_NOQUOTES);
        $code = self::addSlashes($code);
        $code = self::PR(self::$tag_arr, self::color("$0", self::$tag, 'cls'), $code);
        $code = self::PR(self::$stm_arr, self::color("$0", self::$stm), $code);
        $code = self::PR(self::$var_arr, self::color("$0", self::$var), $code);
        $code = self::PR(self::$adn_arr, self::color("$0", self::$adn), $code);
        $code = self::PR(self::$cst_arr, self::color("$0", self::$cst), $code);
           $code = self::PR(self::$num_arr, self::color("$0", self::$num), $code);
        $code = self::PR(self::$occ_arr, self::color("$0", self::$occ), $code);
        $code = self::PR(self::$qot_arr, self::color("$0", self::$qot, 'cls'), $code);
        $code = self::PR(self::$mcm_arr, self::color("$0", self::$com, 'clf'), $code);
        $code = self::PR(self::$mut_arr, self::color("$0", self::$adn), $code);
        $code = self::PR(self::$bbk_arr, self::color("$0", self::$bbk), $code);
        $code = self::isPreDef($code);
        $code = self::PR(self::$ocb_arr, self::color("$0", self::$ocb), $code);
        return '<pre>' .  self::makeQoute($code) . '<pre>';
    }
    
    /*
    * chack if code is a file or a string
    *
    * returns string
    * @param file name or string of code to be highligted
    * @param use to identify if code if a file or string
    */
    public static function render($code, $is_file = false)
    {
        if ($is_file) {
            $code = file_get_contents($code);
        }
        return self::format($code);
    }
}