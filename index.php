<?php

require 'CodeHighlight.php';

/*
CodeHighlight::set('stm', '#52D017');
CodeHighlight::set('tag', '#52D017');
CodeHighlight::set('qot', '#52D017');
CodeHighlight::set('var', '#52D017');
CodeHighlight::set('prd', '#52D017');
CodeHighlight::set('adn', '#52D017');
CodeHighlight::set('com', '#52D017');
CodeHighlight::set('con', '#52D017');
CodeHighlight::set('num', '#52D017');
CodeHighlight::set('cst', '#52D017');
CodeHighlight::set('ocb', '#52D017');
CodeHighlight::set('occ', '#52D017');
CodeHighlight::set('italic_comment', true);


*/
echo CodeHighlight::render('code.txt', true);
