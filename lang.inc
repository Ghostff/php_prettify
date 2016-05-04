<?php
	const VISIBILTY         = "166e16";
	const FUNCTIONS         = "3131ff";
	const VARIABLES         = "267cfd";
	const CUSTOM_FUNCS      = "000000";
	const PRE_BULT_FUNCS    = "0909d0";
	const FUN_N_QUOTE       = "cc0000";
	const COMMENT			= 'ffab00';
	//const FONT              = "'Lucida Sans Unicode', 'Lucida Grande', sans-serif";
	const FONT_SIZE         = "16px";
	
	
	const TAG1  = '<24354>';
	const TAG2  = '<45643>';
	const TAG3  = '<13214>';
	const TAG4  = '<823g7>';
	const TAG5  = '<54114>';
	const TAG6  = '<56455>';
	const TAG7  = '<17812>';
	const TAG8  = '<86138>';
	const TAG9  = '<64823>';
	const TAG10 = '<49755>';
	const TAG11 = '<18832>';
	const TAG12 = '<90839>';
	const TAG13 = '<92121>';
	const TAG14 = '<31980>';
	const CLAXX = '<8726192098>';

$lang = array();
$lang = array_merge($lang, array("VISIBILTY" => array("/public /", "/\bclass /", "/\bprotected /", "/\bprivate /", "/\bnew /", "/\bextends /", "/\becho /", "/\bstatic /", "/\bforeach\b/", "/\bself\b/", "/return /", "/\bif\b/", "/\belse\b/", "/\belse if\b/", "/\belseif\b/", "/\btrue\b/", "/\bfalse\b/", "/\bempty\b/", "/\bisset\b/", "/\bunlink\b/", "/\bunset\b/", "/NULL/", "/\bbreak\b/", "/\bexit\b/", "/\bdie\b/", "/\bas /", "/\barray\b/", "/\bglobal\b/", "/\bnamespace /", "/\buse /")));

$lang = array_merge($lang, array("FUNCTIONS"    => array("/function /", "/\!/", "/\=/", "/\.=/", "/\->/", "/\?/", "/\&/", "/\:/")));
$lang = array_merge($lang, array("FUNCTIONS_QT" => array("/\<\?php/", "/\?\>/", '/"(.*?)"/s', "/'(.*?)'/s")));
$lang = array_merge($lang, array("FUNCS_QT_ENC" => array("/null/", "/@/", "/\[/", "/\]/", "/\;/", "/,/")));
$lang = array_merge($lang, array("COMMENTS"		=> array("/(\/\/)(.*)$/m", "/(#)(.*)$/m")));


?>