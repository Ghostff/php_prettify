# PHP Prettify

Renders your display PHP code into a beautiful colored code. 



1: passing as a file
```php
 <?php
    require 'CodeHighlight.php';

    //using custom color
    //CodeHighlight::set('stm', 'purple');
    echo CodeHighlight::render('code.txt', true);
    
    ![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/dark.png)
    ![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/light.png)
```