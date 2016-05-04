<?php
include(dirname(__FILE__).'/lang.inc');
class in_code_view{
	private static function lang($_key, $key = null, $markers = null)
	{
		global $lang;
		if($key !== null && $markers === null){
			@$str = $lang[$_key][$key];
		}
		else if($key === null && $markers === null){
			@$str = $lang[$_key];
		}
		if($str == "")
			return ("MISING");
		else
			return $str;
	}
	private static function color($color, $strig, $type = null, $agr = null){
		if($type == null)
			return ($color == FUN_N_QUOTE || $color == COMMENT)? TAG1.$color.CLAXX.TAG2.$strig.TAG3 : TAG1.$color.TAG2.$strig.TAG3;
		else
			if($agr == null)	
				return TAG5.$color.TAG6.$type.TAG7.$strig.TAG8;
			if($agr == 'COLOR')	
				return TAG9.$color.TAG10.$strig.TAG11;
			if($agr == 'MERG')	
				return TAG12.$color.TAG13.$strig.TAG14;
		//return $strig;
	}
	private static function code($string)
	{
		$string = preg_replace('/([\w])=([\w])/', "$1 = $2", $string);
		$string = str_replace('. =', '.=', $string);
		$string = preg_replace('/\$[\w\$]+/i', self::color(VARIABLES, "$0", null), $string);
		$string = preg_replace(self::lang("FUNCTIONS_QT"), self::color(FUN_N_QUOTE, "$0", null), $string);
		$string = preg_replace(self::lang("COMMENTS"), self::color(COMMENT, "$1$2", null), $string);
		$string = preg_replace('/\/\*(.*?)\*\//s', self::color(COMMENT, "$0", null), $string);
		$string = preg_replace(self::lang("FUNCTIONS"), self::color(FUNCTIONS, "$0", null), $string);
		$string = preg_replace(self::lang("VISIBILTY"), self::color(VISIBILTY, "$0", null), $string);
		$string = preg_replace(self::lang("FUNCS_QT_ENC"), self::color(CUSTOM_FUNCS, "$0$1", null), $string);
		foreach(explode('(', $string) as $funcs){	
			$funcs = explode(' ', preg_replace('!\s+!', ' ', strip_tags($funcs)));
			$funcs = end($funcs);
			if(function_exists($funcs)){
				$string = preg_replace( '/'.$funcs.'/', self::color(PRE_BULT_FUNCS, $funcs, null), $string);
			}
		}
		$indent = 0;
		$indent_if = $its_lin_comment = $skip = $try = $_tscom = $_skip = $leav_out = false;
		$_string = '';
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $string) as $line){
			$striped = preg_replace('!\s+!', '', strip_tags($line));
			if($skip == true){
				$_string .= "</code>";
			}
			if(trim(str_replace('<br />', '', $line)) == '}'){
				$indent -= 20;
			}	
			if($indent && !$_skip){
				if(trim(str_replace('<br />', '', $line)) == '{')
					$_string .= self::color(intval($indent-20), $line, 'MER', 'MERG');
				else{
					$_string .= self::color($indent, $line, 'MER', 'MERG');
				}
				$_skip = false;
				if($indent_if == true){
					$indent -= 20;
					$indent_if = false;
				}
			}
			else{
				if(!$_skip)
					$_string .= $line;
				else
					$_skip = false;
			}
			if(substr(preg_replace(array('/<br \/>|'.TAG3.'/'), '', $line), -1) == '{'){
				$indent += 20;
			}
			else if(substr(preg_replace(array('/<br \/>|'.TAG3.'/'), '', $line), -1) == ')'
					|| substr(preg_replace(array('/<br \/>|'.TAG3.'/'), '', $line), -4) == 'else'){
				$indent += 20;
				$indent_if = true;
			}
		}
		$__string = '';
		foreach(explode('<br />', $_string) as $_line){
			$_striped = preg_replace('!\s+!', ' ', strip_tags($_line));
			if($try == true){
				$leav_commt = explode('>', $_line);
				$_leav_commt = (!$leav_out)? $leav_commt[0].'>':'';
				array_shift($leav_commt);
				$leav_commt = implode('>', $leav_commt);
				$__string .= $_leav_commt.$leav_commt.'<br />';
				$leav_out = true;
			}
			else{
				$__string .= $_line.'<br />';
			}
		}
		$string = $__string;
		$string = str_replace(TAG1, '<span style="color:#', str_replace(TAG2, ';">', str_replace(TAG3, '</span>', $string)));	
		$string = str_replace(TAG5, '<marg style="color:#', str_replace(TAG6, ';margin-left:', str_replace(TAG7, 'px;">', str_replace(TAG8, '</marg>', $string))));	
		$string = str_replace(TAG9, '<marg style="color:#', str_replace(TAG10, ';">', str_replace(TAG11, '</marg>', $string)));
		$string = str_replace(TAG12, '<marg style="margin-left:', str_replace(TAG13, 'px;">', str_replace(TAG14, '</marg>', $string)));
		$string = str_replace(CLAXX, ';" class="strip ', $string);
		$string .= '<script> var x = document.getElementsByClassName("strip");
					for(i = 0; i < x.length; i++) { x[i].innerHTML = x[i].innerHTML.replace(/<(?!br\s*\/?)[^>]+>/g, ""); } </script>';
		return $string;
	}
	public static function load_color($string, $file = null)
	{
		if($file && $file == 'f')
			$raw_string = file_get_contents($string);
		else
			$raw_string =  $string;
		return '<div class="in_code_view">'.self::code(nl2br($raw_string)).'</div>';
	}
}
?>