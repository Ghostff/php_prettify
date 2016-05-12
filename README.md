# PHP Prettify

Renders your display PHP code into a beautiful IDE type color and indented code. 

Note: this is a sub-version thus lesser functionality referre to [php prettify external](http://ghostff.com/oop/?Php_Code_Color=php#ext) for full function.

1: passing as a string

```php
require_once('funcs.php');
    $raw_string = 'public static function lang($_key, $key = null){
		if($key !== null){
			@$str = $lang[$_key][$key];
		}
		else if($key === null){
			@$str = $lang[$_key];
		}
		if($str === ""){
			return ("throw error");
		}
		else{
			return $str;
		}
	}
	echo in_code_view::load_color($raw_string);
```

2: passing as a file
```php
     require_once('funcs.php');
     echo in_code_view::load_color('filename.ext', 'f');
```