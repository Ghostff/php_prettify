<?php

require 'src/Highlight.php';

/*

SETTING FOR DARK THEME

echo '<body style="background:#282c34;color:#FFFFFF">';
CodeHighlight::set('stm', '#48d5f0');            // general (if, else, class, private) ...
CodeHighlight::set('tag', '#f200fb');            // for php open and close tage (<?php ..)
CodeHighlight::set('qot', '#96d668');            // for qoutes ("..." or  '...')
CodeHighlight::set('var', '#e89c51');            // for defined variable ($var_name) ...
CodeHighlight::set('prd', '#fd1344');            // for php function (eval, strstr) ...
CodeHighlight::set('adn', '#c66cad');            // for php allowes special chars (=, -, +) ...
CodeHighlight::set('com', '#969595');            // for comments
CodeHighlight::set('con', '#e06c75');            // for constants A-Z_ alone
CodeHighlight::set('num', '#e7db1d');            // for numbers 0-9
CodeHighlight::set('cst', '#50ffb3');            // for casting (string) ...
CodeHighlight::set('ocb', '#50ffb3');            // for ( )
CodeHighlight::set('occ', '#FFFFFF');            // for { }
CodeHighlight::set('bbk', '#FFFFFF');            // for [ ]
CodeHighlight::set('allow_esc', true);           // converts her\'s to her's before outputing
CodeHighlight::set('add_slashes', true);         // converts the he's to he\'s in comments block
CodeHighlight::set('italic_comment', true);      // makes all comment font style italic

*/


echo Highlight::render('code.txt', true);
