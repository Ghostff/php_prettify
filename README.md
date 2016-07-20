# PHP Prettify

Renders your display PHP code into a beautiful colored code. 



1: passing as a file
```php
 <?php
    require 'CodeHighlight.php';

    //using custom color
    //CodeHighlight::setColor('stm', 'purple');
    echo CodeHighlight::render('code.txt', true);
```