# PHP Prettify

Renders your display PHP code into a beautiful colored code. 



1: passing as a file
```php
 <?php
    require 'CodeHighlight.php';

    /*
    *
    * @param file name or string of php code
    * @param use to identify if code is a file(true) or string(false) default: false
    *
    */
    
    echo CodeHighlight::render('code.txt', true);
    
```
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/dark.png)
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/light.png)