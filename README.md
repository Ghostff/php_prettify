# PHP Prettify 

Outputs or returns html markup for a syntax highlighted version of the given PHP code using your defined colors.   
*includes PHP 7.1 version* 

```php
<?php

use PhpPrettify\Highlight;

/*
* @param (string) file name or string of PHP code to be highlighted
* @param (bool) set to true if @param1 is a file
* @param (bool) allow caching of processed text (currently work for files only)
*/
echo '<pre>', Highlight::render('code.txt', true, true), '</pre>';

```


![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/dark.png)   
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/light.png)

## showLineNumber
```php
/**
* @param (bool) displays line number if true.
* @param (int) line at wich code rendering begins.
* @return (void)
*/
Highlight::showLineNumber($flag, $start_line);
```

## theme
```php
/**
* @param (string) name of theme base on json file.
* @param (string) default theme to use if @param1 was not found.
* @return (int) (2 used @param1, 1 used @param2, 0 none, -1 no theme.json file found)
*/
Highlight::showLineNumber($flag, $start_line);
```

## setHighlight
```php
/**
* @param (int) line to highlight
* @param (array) attributes to add to line eg (array('class' => 'clsName'))
* @param (bool) allows only one(last called) highlight.
*/
Highlight::setHighlight($line, array $attributes, $override);
```

