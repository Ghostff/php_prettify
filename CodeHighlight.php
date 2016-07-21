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
	
	private static $con = '#8c4d03';
	
	private static $num = 'purple';
	
	private static $cst = '#038c8c';
	
	private static $italic_comment = true;
    
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
        if (substr($color, 0, 1) == '#') {
            $color = str_replace('#', '~~', $color);    
        }
		if ($font && self::$italic_comment) {
			$font = ';font-style~italic';	
		}
		return '~SO'. $color . $font . '~SM' . $string . '~SC';
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
    * case 4 color:~~
    * returns string
    * @param unreplaced string
    */
    private static function makeQoute($code)
    {
        $pattern = array('/~SO/', '/color:~~/',
						 '/~SM/', '/~SC/',
						);
						
        $replacement = array('<span style="color:',
							 'color:#',  ';">', '</span>'
						);
						
       return preg_replace($pattern, $replacement, $code);
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
                    self::color(self::strip($matches[0]),
					self::$com, true), $code);
        }
        return $code;
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
		var_dump($code);
		if (preg_match_all('/`~(.*?)~`/', $code, $matches)) {

			var_dump(array_map(array('self','strip'), $matches[1]));
			
			$code = str_replace($matches[0],
					array_map(array('self','strip'), $matches[1]),
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
        $code = htmlspecialchars($code, ENT_NOQUOTES);
		$code = self::PR(self::$con_arr, self::color("$0", self::$con), $code);
        $code = self::PR(self::$tag_arr, self::color("$0", self::$tag), $code);
        $code = self::PR(self::$stm_arr, self::color("$0", self::$stm), $code);
        $code = self::PR(self::$var_arr, self::color("$0", self::$var), $code);
        $code = self::PR(self::$adn_arr, self::color("$0", self::$adn), $code);
		$code = self::PR(self::$cst_arr, self::color("$0", self::$cst), $code);
		$code = self::PR(self::$num_arr, self::color("$0", self::$num), $code);
		$code = self::PR(self::$qot_arr, self::color("`~$0~`", self::$qot), $code);
		$code = self::PR(self::$mcm_arr, self::color("`~$0~`", self::$com), $code);
        $code = self::isPreDef($code);
		$code = self::stripTags($code);
        return '<pre>' .  self::makeQoute($code) . '<pre>';
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