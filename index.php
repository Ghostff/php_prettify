<?php

require 'src/Highlight.php';



//SETTING FOR DARK THEME

echo '<body style="background:#282c34;color:#FFFFFF">';
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





echo( Highlight::render('code.txt', true));
