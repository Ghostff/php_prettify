# PHP Prettify

Outputs or returns html markup for a syntax highlighted version of the given PHP code using the your defined colors.   
*includes PHP 7.1 version*

## Optional methods
```php
Highlight::showLineNumber($flag, $start_line); #Displays lines number, make text processing to start at a certain line
Highlight::setHighlight($line_number, $arr_of_html_attr); #Add attribute(HTML) to a particular line
Highlight::theme($name, $default); #theme name, default if $name was not found
```

##
```php

 <?php
    
    use PhpPrettify\Highlight;
 
    /*
    * @param (string) file name or string of PHP code to be highlighted
    * @param (bool) specified name is a file flag
    * @param (bool) allow catching of processed text (currently work for files only)
    */
    echo '<pre>', Highlight::render('code.txt', true, true), '</pre>';
    
```


![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/dark.png)   
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/light.png)