# PHP Prettify

Outputs or returns html markup for a syntax highlighted version of the given PHP code using the your defined colors. *includes PHP 7.1 version*

1: passing as a file
```php
 <?php

    /*
    *
    * @param file name or string of php code
    * @param specified name is a file flag
    * @param cache flag (currently work for files only)
    * @param convert tabs to space flag
    */
    echo PhpPrettify\Highlight::render('code.txt', true, true, true);
    
```
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/dark.png)
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/light.png)