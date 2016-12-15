<?php


class Highlight
{

    private static $cast = '038C8C';
    private static $null = '0000FF';
    private static $bool = 'D8C300';
    private static $self = '1D6F0C';
    private static $quote = 'FF0000';
    private static $number = 'A4AC21';
    private static $comment = 'FEA500';
    private static $tag_open = 'F00000';
    private static $keywords = '008000';
    private static $function = '0000FF';
    private static $variable = '2071ED';
    private static $constant = '8C4D03';
    private static $tag_close = 'F00000';
    private static $operators = '0000FF';
    private static $parenthesis = '038C8C';
    private static $php_function = '6367A7';
    private static $curly_braces = '7F5217';
    private static $square_bracket = 'F46164';
    private static $custom_function = 'A611AA';
    private static $multi_line_comment = 'FEA500';



    private static $self_ptrn = '/self/';
    private static $cast_ptrn = '/(\(\s*(int|string|float|array|object|unset|binary|bool)\s*\))/';
    private static $bool_ptrn = '/\b(?<!\$)true|false/i';
    private static $null_ptrn = '/\b(?<!\$)(null)\b/';
    private static $quote_ptrn = '/([style=|class=]*)".*?"|\'.*?\'/';
    private static $number_ptrn = '/\b(\d+)\b/';
    private static $comment_ptrn = '/\/\/(.*)?|(?<!color:)#(.*)?/';
    private static $variable_ptrn = '/\$(\$*)[a-zA-Z_]+[a-zA-Z0-9_]*/';
    private static $function_ptrn = '/(?<=\s)(function)(?=\s)/';
    private static $constant_ptrn = '/\b([A-Z_]+)\b/';
    private static $tag_open_ptrn = '/<.*>(&lt;)<.*><.*>(\?)<.*>(php)/';
    private static $keywords_ptrn = '/(?<!\$|\w)((a(bstract|nd|rray(?!\s*\))|s))|
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
    private static $tag_close_ptrn = '/<.*>(\?)<.*><.*>(\&gt;)<.*>/';
    private static $operators_ptrn = '/(\=|\.|\!|\+|\%|\-|\:|\@|\||\?|&gt;|&lt;|&amp;)/';
    private static $parenthesis_ptrn = '/\(|\)/';
    private static $curly_braces_ptrn = '/\{|\}/';
    private static $square_bracket_ptrn = '/\[|\]/';
    private static $multi_line_comment_ptrn = '/\/\*(.*?)\*\//s';



    /**
     * check if code is a file or a string then renders accordingly
     *
     * @param string $code
     * @param bool $is_file
     * @return string
     */
    public static function render($code, $is_file = false)
	{
        if ($is_file) {
            $code = file_get_contents($code);
        }
        return self::format($code);
	}


    /**
     * updates attributes of class property
     *
     * @param array|string $prop_name
     * @param array|string $values
     * @return void
     */
    public static function set($prop_name = array(), $values = array())
    {
        if ( is_array($prop_name)) {
            if ( is_string($values)) {
                $values = array($values);
            }
            foreach ( $prop_name as $key => $properties)
            {
                if (isset($values[$key])) {
                    self::$$properties = $values[$key];
                }
            }
        }
        else{
            self::$$prop_name = $values;
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
     * php preg replace function
     *
     * @param string $pattern
     * @param string $replacement
     * @param string $subject
     * @return mixed
     */
    private static function PR($pattern, $replacement, $subject)
    {
        $pattern = trim(preg_replace('/\s\s+/', '', $pattern));
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
        return preg_replace_callback('/(\w+)(?=\s\(|\()/', function ($arg)
        {
            $func = $arg[1];
            if (function_exists($func)) {
                return self::span(self::$php_function, 'php_function', $func);
            }
            else {
                return self::span(self::$custom_function, 'custom_function', $func);
            }
        }, $code);
    }


    /**
     * creates a script that strips out all tag inside the comment or quote tag
     *
     * @return string
     */
    private static function stripCodes()
    {
        return '<script> var p = document.getElementsByClassName("strip");
            for(var i = 0; i < p.length; i++) {
                console.log(p[i].innerHTML);
                p[i].innerHTML = p[i].innerHTML.replace(/<(?!br\s*\/?)[^>]+>/g, "");
            } </script>';
    }


    /**
     * checks and highlight codes inside a quote
     *
     * @param $code
     * @return string
     */
    private static function isQuote($code)
    {
        return preg_replace_callback(self::$quote_ptrn, function($args)
        {
            if ( ! isset($args[1]) || ! in_array($args[1], array('class=', 'style='))) {
                return self::span(self::$quote, 'strip quote', $args[0]);
            }
            else{
                return $args[0];
            }
        }, $code);

    }


    /**
     * highlights code
     *
     * @param $code
     * @return string
     */
    private static function format($code)
    {
        $code = htmlspecialchars($code, ENT_NOQUOTES);
        $new_code = null;
        foreach (preg_split('/\n/', $code) as $lines)
        {
            $new_line = self::PR(self::$operators_ptrn, self::span(self::$operators, 'operators'), $lines);
            $new_line = self::PR(self::$number_ptrn, self::span(self::$number, 'number'), $new_line);
            $new_line = self::PR(self::$keywords_ptrn, self::span(self::$keywords, 'keyword'), $new_line);
            $new_line = self::PR(self::$function_ptrn, self::span(self::$function, 'function', '$1'), $new_line);
            $new_line = self::PR(self::$variable_ptrn, self::span(self::$variable, 'variable'), $new_line);
            $new_line = self::PR(self::$cast_ptrn, self::span(self::$cast, 'cast'), $new_line);
            $new_line = self::isFunction($new_line);

            $new_line = self::PR(self::$parenthesis_ptrn, self::span(self::$parenthesis, 'parenthesis'), $new_line);
            $new_line = self::PR(self::$curly_braces_ptrn, self::span(self::$curly_braces, 'curly_braces'), $new_line);
            $new_line = self::PR(self::$square_bracket_ptrn, self::span(self::$square_bracket, 'square_bracket'), $new_line);
            $new_line = self::PR(self::$null_ptrn, self::span(self::$null, 'null'), $new_line);
            $new_line = self::PR(self::$constant_ptrn, self::span(self::$constant, 'constant'), $new_line);
            $new_line = self::PR(self::$comment_ptrn, self::span(self::$comment, 'strip comment'), $new_line);
            $new_line = self::PR(self::$self_ptrn, self::span(self::$self, 'self'), $new_line);
            $new_line = self::PR(self::$bool_ptrn, self::span(self::$bool, 'bool'), $new_line);

            $new_line = self::PR(self::$tag_open_ptrn, self::span(self::$tag_open, 'tag', '$1$2$4'), $new_line);
            $new_line = self::PR(self::$tag_close_ptrn, self::span(self::$tag_close, 'tag', '$1$2'), $new_line);
            $new_code .= $new_line;
        }
        $new_code = self::PR(self::$multi_line_comment_ptrn, self::span(self::$multi_line_comment, 'strip multi_line_comment'), $new_code);
        $new_code = self::isQuote($new_code);




        return sprintf('<pre>%s</pre>%s', $new_code, self::stripCodes());
    }

}