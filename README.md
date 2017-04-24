# PHP Prettify

Outputs or returns html markup for a syntax highlighted version of the given PHP code using the your defined colors. *includes PHP 7.1 version*

##
```php
 <?php
    
    use PhpPrettify\Highlight;
 
    /*
    * @param (string) file name or string of PHP code to be highlighted
    * @param (bool) specified name is a file flag
    * @param (bool) allow catching of processed text (currently work for files only)
    */
    echo PhpPrettify\Highlight::render('code.txt', true, true);
    
```
## Optional methods
```php
Highlight::showLineNumber(true); #Displays lines number
Highlight::setRange(11, 20, true); #Process text within set range
Highlight::setHighlight(12, ['class' => 'h-classed']); #Add attribute(HTML) to a particular line

```
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/dark.png)
![alt tag](https://github.com/Ghostff/php_prettify/blob/master/images/light.png)