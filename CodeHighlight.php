<?php

require 'Regex.php';

class CodeHighlight extends Regex
{

    private static $stm = 'green';
	
    private static $tag = 'red';
	
    private static $qot = 'red';
	
    private static $var = '#2071ED';
	
    private static $prd = 'blue';
	
    private static $adn = 'blue';
	
    private static $com = '#FEA500';
    
    /*
    * Assigns a tag and style to a particular string
    *
    * returns string
    * @param string to be styled
    * @param color to be assigned
    */
    private static function color($string, $color)
    {
        if (substr($color, 0, 1) == '#') {
            $color = str_replace('#', '~~', $color);    
        }
        return '<span style*~~color:' . $color. ';~~>' . $string .'<~~span>';
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
    * Converts *~~ and ~~ added on compile time with =" and "
    * an many more due to some html elements matches with
    * some php opperators.
    *
    * we gonna find a way to replace the 
    * tags that matches and thus creating an unreausabel
    * string
    *
    * case 1 e\*~~
    * case 2 ;~~>
    * case 3 <~~s
    * case 4 lor:~~
    * returns string
    * @param unreplaced string
    */
    private static function makeQoute($code)
    {
        $pattern = array("/e\*~~/", "/;~~>/", "/<~~s/", "/lor:~~/");
        $replacement = array('e="', ';">', "</s", "lor:#");
        return self::PR($pattern, $replacement, $code);
    }

    /*
    * searches and strips out any code styling 
    * inside comment block
    *
    * returns string
    * @param string to search
    */
    private static function stripinComments($code, $check_back = false)
    {
        if (preg_match(self::$com_arr, $code, $matches)) {
            $code = str_replace($matches[0],  
                    self::color(strip_tags($matches[0]), self::$com),
                    $code);
        }
        return $code;
    }
    
    /*
    * searches and strips out any code styling 
    * inside comment qoute block
    *
    * returns string
    * @param string to search
    */
    private static function stripInQoutes($code)
    {
        if (preg_match(self::$qot_arr, $code, $matches)) {
            $code = str_replace($matches[0],  
                    self::color(strip_tags($matches[0]), self::$qot),
                    $code);
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
            
            $code_lines = self::stripinComments($code_lines);
            $code_lines = self::stripInQoutes($code_lines);
            
            
            $replaced .= $code_lines;
            preg_match_all("/([\w]+)(\s*)\(/", $code_lines, $matches);
                foreach ($matches[1] as $function) {
                    if (function_exists(trim($function))) {
                        $replaced = self::PR('/' . $function . '/', 
                                        self::color($function, 
                                        self::$prd), $replaced
                                    );
                    }
                }
        }
        return $replaced;
    }
    
    private static function format($code)
    {
        $code = htmlspecialchars($code);
        $code = self::PR(self::$tag_arr, self::color("$0", self::$tag), $code);
        $code = self::PR(self::$stm_arr, self::color("$0", self::$stm), $code);
        $code = self::PR(self::$var_arr, self::color("$0", self::$var), $code);
        $code = self::PR(self::$adn_arr, self::color("$0", self::$adn), $code);
        $code = self::isPreDef($code);
        echo '<pre>' .  self::makeQoute($code) . '<pre>';
    }
    
    /*
    * update an existing property(color) value
    *
    * returns null
    * @param property to update
    * @param new property value
    */
    public static function setColor($prop_name, $values)
    {
        self::$$prop_name = $values;
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