# PHP Prettify 

Creates a syntax highlighted version of the given PHP code.

```php
<?php

use PhpPrettify\Highlight;

echo '<pre>', (new Highlight)->render('$name = "foobar"'), '</pre>';
````

#### Highlighting a file
```php
<?php

use PhpPrettify\Highlight;

echo '<pre>', (new Highlight)
        ->setTheme('bittr')                             // Sets code highlight theme.
        ->setStyle('body {margin:0;padding:0}')         // Append css to default to style.
        ->setHighlight(22, ['style' => 'color:red'])    // Add html attributes to selected line(tr).
        ->showLineNumber(1, false)                      // Show line number starting from line 1 and prevent selection of line number.
        ->render('code.txt', true),
    '</pre>';
```

![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/dark.png)   
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/light.png)
