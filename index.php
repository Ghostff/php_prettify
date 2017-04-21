<?php

require 'src/PHP7.0/Highlight.php';


/*
//SETTING FOR DARK THEME

echo '<body style="background:#282c34;color:#FFFFFF">';


YOU CAN DO THIS:

Highlight::set('cast', 'C71FC1');
Highlight::set('null', 'FFFFFF');
Highlight::set('bool', '1591D7');
Highlight::set('self', '88B7FF');
Highlight::set('quote', '68F06F');
Highlight::set('number', 'FF4F51');
Highlight::set('comment', 'B7B7B7');
Highlight::set('tag_open', 'EF62FC');
Highlight::set('keywords', '88B7FF');
Highlight::set('function', '88B7FF');
Highlight::set('variable', 'DB97E4');
Highlight::set('constant', 'FFFFFF');
Highlight::set('tag_close', 'EF62FC');
Highlight::set('operators', 'FFFFFF');
Highlight::set('parenthesis', 'FFFFFF');
Highlight::set('php_function', 'FDD28A');
Highlight::set('curly_braces', 'FFFFFF');
Highlight::set('square_bracket', 'FFFFFF');
Highlight::set('custom_function', 'FFC13B');
Highlight::set('multi_line_comment', 'B7B7B7');


OR

$properties = array(
	'cast', 'null', 'bool', 'self', 'quote',
	'number', 'comment', 'tag_open', 'keywords', 'function',
	'variable', 'constant', 'tag_close', 'operators', 'parenthesis',
	'php_function', 'curly_braces', 'square_bracket', 'custom_function', 'multi_line_comment',
);

$repacement = array(
	'C71FC1', 'FFFFFF', '1591D7', '88B7FF', '68F06F', 
	'FF4F51', 'B7B7B7', 'EF62FC', '88B7FF', '88B7FF', 
	'DB97E4', 'FFFFFF', 'EF62FC', 'FFFFFF', 'FFFFFF', 
	'FDD28A', 'FFFFFF', 'FFFFFF', 'FFC13B', 'B7B7B7'
);
Highlight::set($properties, $repacement);

BOTH HAVE SAME PROCESSING SPEED
*/



echo PhpPrettify\Highlight::render('code.txt', true, false);
