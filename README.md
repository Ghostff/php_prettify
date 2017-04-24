# PHP Prettify

Outputs or returns html markup for a syntax highlighted version of the given PHP code using the your defined colors.   
*includes PHP 7.1 version*

## Optional methods
```php
Highlight::showLineNumber(false); #Displays lines number
Highlight::setRange(0, 0, false); #Process text within set range
Highlight::setHighlight(0, ['class' => 'h-class']); #Add attribute(HTML) to a particular line

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