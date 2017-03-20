<?php

namespace PhpPrettify;

class Highlight
{
    private static $styled = null;
    private static $cache_path = null;

    private static $cast = '038C8C';
    private static $null = '0000FF';
    private static $bool = 'D8C300';
    private static $self = '1D6F0C';
    private static $quote = 'FF0000';
    private static $parent = '1D6F0C';
    private static $number = 'A4AC21';
    private static $comment = 'FEA500';
    private static $tag_open = 'F00000';
    private static $keywords = '008000';
    private static $function = '0000FF';
    private static $variable = '2071ED';
    private static $constant = '8C4D03';
    private static $tag_close = 'F00000';
    private static $operators = '0000FF';
    private static $semi_colon = '000000';
    private static $parenthesis = '038C8C';
    private static $return_type = 'E3093F';
    private static $php_function = '6367A7';
    private static $curly_braces = '7F5217';
    private static $parameter_type = 'E3093F';
    private static $square_bracket = 'F46164';
    private static $custom_function = 'A611AA';
    private static $multi_line_comment = 'FEA500';


    private static $self_ptrn = '/(?<!\$|\w)self/';
    private static $cast_ptrn = '/(\(\s*(int|string|float|array|object|unset|binary|bool)\s*\))/';
    private static $bool_ptrn = '/\b(?<!\$)true|false/i';
    private static $null_ptrn = '/\b(?<!\$)(null)\b/';
    private static $quote_ptrn = '/((?<!\\\)\'(.*?)(?<!\\\)\'|
        (?<!((style|class|label)=)|(\\\))"(?!\s(class|label)=|>).*?(?<!((style|class|label)=)|(\\\))"(?!\s(class|label)=|>))/s';
    private static $parent_ptrn = '/(?<!\$|\w)parent\b/';
    private static $number_ptrn = '/\b(\d+)\b/';
    private static $comment_ptrn = '/(?<!(http(s):))\/\/.*|(?<!color:)#.*/';
    private static $variable_ptrn = '/\$(\$*)[a-zA-Z_]+[a-zA-Z0-9_]*/';
    private static $function_ptrn = '/(?<=\s|^)(function)(?=\s)/';
    private static $constant_ptrn = '/\b(?<!(\#|\$))([A-Z_]+)(?!<\/\w+>\()\b/';
    private static $keywords_ptrn = '/(?<!\$|\w)((a(bstract|nd|rray\s*(?=\()|s))|
        (c(a(llable|se|tch)|l(ass(?!=)|one)|on(st|tinue)))|
        (d(e(clare|fault)|ie|o))|
        (e(cho|lse(if)?|mpty|nd(declare|for(each)?|if|switch|while)|val|x(it|tends)))|
        (f(inal|or(each)?))|
        (g(lobal|oto))|
        (i(f|mplements|n(clude(_once)?|st(anceof|eadof)|terface)|sset))|
        (n(amespace|ew))|
        (p(r(i(nt|vate)|otected)|ublic))|
        (re(quire(_once)?|turn))|
        (s(tatic|witch))|
        (t(hrow|r(ait|y)))|
        (u(nset(?!\s*\))|se))|
        (__halt_compiler|break|list|(x)?or|var|while))\b/';
    private static $operators_ptrn = '/(\=|\.|\!|\+|\%|\-|(?<!https|http)\:|\@|\||\?|&gt;|&lt;|&amp;)/';
    private static $semi_colon_ptrn = '/(?<![&lt|&gt|&amp]);/';
    private static $parenthesis_ptrn = '/\(|\)/';
    private static $return_type_ptrn = '/(?<=\:\<\/span\>)\s*(?:\<\w+ \w+="\w+:#\w+" \w+="\w+"\>\?\<\/\w+\>)*(string|bool|array|float|int|callable|void)/';
    private static $curly_braces_ptrn = '/\{|\}/';
    private static $parameter_type_ptrn = '/(?<!\w)(string|bool|array|float|int|callable)\s*(?=\<\w+ \w+="\w+:#\w+" \w+="\w+"\>\$)/';
    private static $square_bracket_ptrn = '/\[|\]/';
    private static $multi_line_comment_ptrn = '/\/\*(.*?)\*\//';


    /**
     * updates attributes of class property
     *
     * @param string $property
     * @param string $values
     * @return void
     */
    public static function set($property, $values)
    {
        if (property_exists(__CLASS__, $property)) {
            self::${$property} = $values;
        } else {
            throw new RuntimeException(sprintf('%s does not exist in %s', $property, __CLASS__));
        }
    }


    /**
     * adds code to a span tag
     *
     * @param string $color
     * @param string $class
     * @param string $content
     * @return string
     */
    private static function span($color, $class, $content = '$0')
    {
        $span = sprintf('<span style="color:#%s" class="%s">%s</span>', $color, $class, $content);
        return $span;
    }

    /**
     * adds code to a font tag
     *
     * @param $color
     * @param $class
     * @param string $content
     * @return string
     */
    private static function font($color, $class, $content = '$0')
    {
        $font = sprintf('<font style="color:#%s" class="%s">%s</font>', $color, $class, $content);
        return $font;
    }

    /**
     * php preg replace function
     *
     * @param string $pattern
     * @param string $replacement
     * @param string $subject
     * @return mixed
     */
    private static function PR($pattern, $replacement, $subject)
    {
        return preg_replace($pattern, $replacement, $subject);
    }


    /**
     * check and highlight user defined  or php pre defined function
     *
     * @param string $code
     * @return string
     */
    private static function isFunction($code)
    {
        return preg_replace_callback('/(\w+)(?=\s\(|\()/', function ($arg) {
            $func = $arg[1];
            if (function_exists($func)) {
                return self::span(self::$php_function, 'php_function', $func);
            } else {
                return self::span(self::$custom_function, 'custom_function', $func);
            }
        }, $code);
    }

    /**
     * formats strings
     *
     * @param $code
     * @param $file_name
     * @param $cache
     * @param $tabs_to_space
     * @return string
     */
    private static function format($code, $file_name, $cache, $tabs_to_space)
    {
        $code = str_replace(
            array('<?php', '<?=', '?>', '\\\\'),
            array('PP_PHP_LONG_TAG_OPEN', 'PP_PHP_SHORT_TAG_OPEN', 'PP_PHP_CLOSE_TAG', 'PP_PHP_DOUBLE_BACK_SLASH'),
            $code
        );

        $code = htmlspecialchars($code, ENT_NOQUOTES);
        $new_code = null;
        foreach (preg_split('/\n/', $code) as $line_number => $lines) {
            $line_number++;
            $new_code .= '<div class="line_' . $line_number . ' line" label="' . $line_number . '">';

            $pattern = array(
                self::$operators_ptrn,
                self::$number_ptrn,
                trim(preg_replace('/\s\s+/', '', self::$keywords_ptrn)),
                self::$function_ptrn,
                self::$variable_ptrn,
                self::$cast_ptrn
            );

            $replacement = array(
                self::span(self::$operators, 'operators'),
                self::span(self::$number, 'number'),
                self::span(self::$keywords, 'keyword'),
                self::span(self::$function, 'function', '$1'),
                self::span(self::$variable, 'variable'),
                self::span(self::$cast, 'cast')
            );

            $new_line = self::PR($pattern, $replacement, $lines);

            $new_line = self::isFunction($new_line);

            $pattern = array(
                self::$constant_ptrn,
                self::$parenthesis_ptrn,
                self::$curly_braces_ptrn,
                self::$square_bracket_ptrn,
                self::$null_ptrn,
                self::$self_ptrn,
                self::$parent_ptrn,
                self::$bool_ptrn,
                self::$comment_ptrn,
                self::$parameter_type_ptrn,
                self::$return_type_ptrn,
                self::$semi_colon_ptrn,
                '/PP_PHP_LONG_TAG_OPEN/',
                '/PP_PHP_SHORT_TAG_OPEN/',
                '/PP_PHP_CLOSE_TAG/',
                '/PP_PHP_DOUBLE_BACK_SLASH/'
            );

            $replacement = array(
                self::span(self::$constant, 'constant'),
                self::span(self::$parenthesis, 'parenthesis'),
                self::span(self::$curly_braces, 'curly_braces'),
                self::span(self::$square_bracket, 'square_bracket'),
                self::span(self::$null, 'null'),
                self::span(self::$self, 'self'),
                self::span(self::$parent, 'parent'),
                self::span(self::$bool, 'bool'),
                self::span(self::$comment, 'strip comment'),
                self::span(self::$parameter_type, 'parameter_type'),
                self::span(self::$return_type, 'return_type'),
                self::span(self::$semi_colon, 'semi_colon'),
                self::span(self::$tag_open, 'tag long', '&lt;?php'),
                self::span(self::$tag_open, 'tag short', '&lt;?='),
                self::span(self::$tag_close, 'tag clode', '?>'),
                '\\\\\\'
            );
            $new_code .= self::PR($pattern, $replacement, $new_line) . '</div>';

        }

        $pattern = array(
            self::$multi_line_comment_ptrn,
            trim(preg_replace('/\s\s+/', '', self::$quote_ptrn))
        );

        $replacement = array(
            self::font(self::$multi_line_comment, 'strip multi_line_comment', '$0'),
            self::font(self::$quote, 'strip quote', '$0')
        );

        $new_code = self::PR($pattern, $replacement, $new_code);
        $new_code = str_replace(array('\"', '\\\''), array('"', '\''), $new_code);

        if ($tabs_to_space) {
            $new_code = preg_replace('/\t/', '&nbsp;&nbsp;&nbsp;&nbsp;', $new_code);
        }

        $style = '.strip font,.strip span{color:inherit !important}' . self::$styled;
        $pretty = '<pre>' . $new_code . '</pre><style>' . $style . '</style>';

        if ($cache) {
            self::cache($file_name, $pretty);
        }
        return $pretty;
    }


    /**
     * caches formatted strings and handles gc
     *
     * @param $name
     * @param string $new_cache
     * @return string
     */
    private static function cache($name, $new_cache = null)
    {
        $file = self::$cache_path . DIRECTORY_SEPARATOR . '_x86';
        if ($new_cache == null) {
            @$content = file_get_contents($file);
            $content = (array)json_decode($content);

            if ((isset($content[$name])) && ($content[$name][0] == filemtime($name))) {
                return file_get_contents(self::$cache_path . DIRECTORY_SEPARATOR . $content[$name][1]);
            }
        } else {
            if (!is_dir(self::$cache_path)) {
                mkdir(self::$cache_path);
                file_put_contents($file, '');
            }
            @$content = file_get_contents($file);
            $content = (array)json_decode($content);
            if ((!isset($content[$name])) || ((isset($content[$name])) && ($content[$name][0] != filemtime($name)))) {
                $_name = time() + mt_rand(10, 200);
                $content[$name] = array(filemtime($name), $_name);
                file_put_contents($file, json_encode($content));
                file_put_contents(self::$cache_path . DIRECTORY_SEPARATOR . $_name, $new_cache);
            }
        }
        return '';
    }


    /**
     * check if code is a file or a string then renders accordingly
     *
     * @param string $code
     * @param bool $is_file
     * @return string
     */
    public static function render($code, $is_file = false, $cache = false, $expire = null, $tabs_to_space = true)
    {
        if ($is_file) {
            self::$cache_path = __DIR__ . DIRECTORY_SEPARATOR . '.caches';
            if ($is_file) {
                if ($cache) {
                    $cached = self::cache($code);
                    if ($cached != '') {
                        return $cached;
                    }
                }
                return self::format(file_get_contents($code), $code, $cache, $tabs_to_space);
            }
        }
        return self::format($code, null, false, true);
    }


    /**
     * sets formatted string out layer style
     *
     * @param array $style
     * @param bool $line
     * @param bool $line_number
     */
    public static function setStyle(array $style, $line = true, $line_number = true)
    {
        @$margin = $style['line_number_width'];
        @$padding = $style['line_padding'];
        @$line_border = $style['line_border_color'];
        @$line_no_border = $style['line_number_border_color'];
        @$line_no_color = $style['line_number_color'];
        @$line_no_border_scale = $style['line_no_border_scale'];
        @$line_border_scale = $style['line_border_scale'];

        $styled = '.line{
		  ' . (($line) ? 'border-bottom: ' . $line_border_scale . 'px solid #' . $line_border . ';' : '') . '
		  margin-left:' . $margin . 'px;
		  padding:' . $padding . 'px 0;
		}';

        if ($line_number) {
            $styled .= '.line::before{
			  content:attr(label);
			  position: absolute;
			  padding:' . ($padding + 1) . 'px 0;
			  width:' . $margin . 'px;
			  margin-top: -6px;
			  margin-left: -' . ($margin + 5) . 'px;
			  color: #' . $line_no_color . ';
			  border-right: ' . $line_no_border_scale . 'px solid #' . $line_no_border . ';
			 }';
        }
        self::$styled = trim(preg_replace('/\s\s+/', '', $styled));
    }
}