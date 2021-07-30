<?php

namespace ghostff\Highlight;

class Highlight
{
    private $highlight = array();
    private $start_line = 0;
    private $line_selectable = true;
    private $cache_path;
    private $show_line_number = false;
    private $style = '.strip span{color:inherit !important;all:initial !important;all:unset !important}td[unselectable]{ user-select:none;-ms-user-select:none;-moz-user-select:none;-webkit-user-select:none; }';

    private $cast = 'color:#038C8C';
    private $null = 'color:#0000FF';
    private $bool = 'color:#D8C300';
    private $self = 'color:#1D6F0C';
    private $quote = 'color:#FF0000';
    private $class = 'color:#000000';
    private $parent = 'color:#1D6F0C';
    private $number = 'color:#A4AC21';
    private $attribute = 'opacity:0.5';
    private $comment = 'color:#FEA500';
    private $tag_open = 'color:#F00000';
    private $keywords = 'color:#008000;';
    private $function = 'color:#0000FF';
    private $variable = 'color:#2071ED';
    private $constant = 'color:#8C4D03';
    private $tag_close = 'color:#F00000';
    private $operators = 'color:#0000FF';
    private $semi_colon = 'color:#000000';
    private $parenthesis = 'color:#038C8C';
    private $return_type = 'color:#E3093F';
    private $php_function = 'color:#6367A7';
    private $curly_braces = 'color:#7F5217';
    private $parameter_type = 'color:#E3093F';
    private $square_bracket = 'color:#F46164';
    private $custom_function = 'color:#A611AA';
    private $multi_line_comment = 'color:#FEA500';

    private $self_ptrn = '/(?<!\$|\w)self/';
    private $cast_ptrn = '/(\(\s*(int|string|float|array|object|unset|binary|bool)\s*\))/';
    private $bool_ptrn = '/\b(?<!\$)true|false/i';
    private $null_ptrn = '/\b(?<!\$)(null)\b/';
    private $class_ptrn = '/(class|extends|implements|enum)\s+([\w\\\]+)/';
    private $quote_ptrn = '/(.*?)(?<!\\\\)(\'|(?<!((style)=))")/';
    private $parent_ptrn = '/(?<!\$|\w)parent\b/';
    private $number_ptrn = '/(?<! style="color:#)\b(\d+)\b/';
    private $comment_ptrn = '/(?<!http:|https:)\/\/.*|(?<!color:)#(?!\s*\[).*/';
    private $attribute_ptrn = '/(?<!color:)#\s*\[.*\]/';
    private $variable_ptrn = '/\$(\$*)[a-zA-Z_]+[a-zA-Z0-9_]*/';
    private $function_ptrn = '/(?<=\s|^)(function)(?=\s)/';
    private $constant_ptrn = '/\b(?<!(\#|\$))([A-Z_]+)(?!<\/\w+>\()\b/';
    private $enum_patrn = '';
    private $keywords_ptrn = '/(?<!\$|\w)((a(bstract|nd|rray\s*(?=\()|s))|
        (c(a(llable|se|tch)|l(ass\s+|one)|on(st|tinue)))|
        (d(e(clare|fault)|ie|o))|
        (e(cho|lse(if)?|mpty|nd(declare|for(each)?|if|switch|while)|num|val|x(it|tends)))|
        (f(inal|or(each)?))|
        (g(lobal|oto))|
        (i(f|mplements|n(clude(_once)?|st(anceof|eadof)|terface)|sset))|
        (n(amespace|ew))|
        (p(r(i(nt|vate)|otected)|ublic))|
        (re(quire(_once)?|turn))|
        (s(tatic|witch))|
        (t(hrow|r(ait|y)))|
        (u(nset(?!\s*\))|se))|
        (__halt_compiler|break|list|(x)?or|var|while|match))\b/';
    private $operators_ptrn = '/((?<! (style|class))\=|\.|\!|\+|\%|-(?!\w+:)|(?<!https|http)[^a-z+]:|\@|\||\?|&gt;|&lt;|&amp;)/';
    private $semi_colon_ptrn = '/(?<![&lt|&gt|&amp]);/';
    private $parenthesis_ptrn = '/\(|\)/';
    private $return_type_ptrn = '/(?<=\<\/span\>\:|\:\<\/span\>)\s*(?:\<span.*?\>\:<span>)*(string|bool|array|float|int|callable|void)/';
    private $curly_braces_ptrn = '/[\{\}]/';
    private $parameter_type_ptrn = '/(?<!\w)(string|bool|array|float|int|callable)\s*(?=\<span.*?class="(variable|operators)"\>[\$|&amp;])/';
    private $square_bracket_ptrn = '/\[|\]/';
    private $multi_line_comment_ptrn = '/\/\*|\*\//';

    /**
     * Highlight constructor.
     */
    public function __construct()
    {
        $this->cache_path = __DIR__ . DIRECTORY_SEPARATOR . '.cache';
    }

    /**
     * check and highlight user defined  or php pre defined function.
     *
     * @param $code
     * @return array|string|string[]|null
     */
    private function isFunction($code)
    {
        return preg_replace_callback('/([n|t]?.?)\b(\w+)(?=\s\(|\()/', function (array $arg)
        {
            $back = $arg[1];
            $func = $arg[2];
            if ($back == 'n ' || $back == 't;' || $back == ':' && $func != 'array') {
                return "{$back}<span style=\"{$this->custom_function}\" class=\"custom_function\">{$func}</span>";
            }
            elseif (function_exists($func)) {
                return "{$back}<span style=\"{$this->php_function}\" class=\"php_function\">{$func}</span>";
            }

            return $arg[0];
        }, $code);
    }

    /**
     * processes supplied text
     *
     * @param $code
     * @param $file_name
     * @param $cache
     * @return string
     */
    private function format($code, $file_name, $cache)
    {
        $code = str_replace(
            array('<?php', '<?=', '?>', '\\\\'),
            array('PP_PHP_LONG_TAG_OPEN', 'PP_PHP_SHORT_TAG_OPEN', 'PP_PHP_CLOSE_TAG', 'PP_PHP_DOUBLE_BACK_SLASH'),
            $code
        );

        $code         = htmlspecialchars($code, ENT_NOQUOTES);
        $new_code     = null;
        $start_number = $this->start_line;
        $is_MLQ       = false; #is_multi_line_quote
        $is_MLC       = false; #is_multi_line_comment
        $QO           = false; #quote_opened
        $QT           = null; #qoute_type
        $separator    = "\n";
        $token        = strtok($code, $separator);

        while ($token !== false)
        {
            $value = rtrim($token, PHP_EOL);
            if ($value == '') {
                $value = '  ';
            }

            $line = preg_replace($this->semi_colon_ptrn, "<span style=\"{$this->semi_colon}\" class=\"semi_colon\">\$0</span>", $value);
            $gui_line_number = $this->show_line_number ? "<td" . ($this->line_selectable ? '' : ' unselectable="on"') . ">{$start_number}</td><td>" : '<td>';

            if (isset($this->highlight[$start_number]))
            {
                $highlight_attr = '';
                foreach ($this->highlight[$start_number] as $key => $values) {
                    $highlight_attr .= " {$key}=\"{$values}\"";
                }
                $gui_highlight = "<tr{$highlight_attr}>";
            }
            else
            {
                $gui_highlight = '<tr>';
            }

            $new_code .= "{$gui_highlight}{$gui_line_number}";

            if ( ! $is_MLQ)
            {
                if ($is_MLC) {
                    $line = "<span style=\"{$this->multi_line_comment}\" class=\"strip multi_line_comment\">{$line}</span>";
                }

                $comment = $this->multi_line_comment;
                $line = preg_replace_callback($this->multi_line_comment_ptrn, function(array $matches) use (&$is_MLC, $comment) {
                    if ($matches[0] == '*/') {
                        $is_MLC = false;
                        return "{$matches[0]}</span>";
                    }

                    $is_MLC = true;
                    return "<span style=\"{$comment}\" class=\"strip multi_line_comment\">{$matches[0]}";
                }, $line);
            }

            if ( ! $is_MLC)
            {
                if ($is_MLQ) {
                    $line = "<span style=\"{$this->quote}\" class=\"strip quote\">{$line}</span>";
                }

                #single line comment
                $SLC = false;
                $line = preg_replace_callback($this->quote_ptrn, function(array $matches) use (&$QO, &$is_MLQ, &$SLC, &$QT)
                {
                    if ($QO) {
                        if ($QT == $matches[2]) {
                            $is_MLQ = false;
                            $QO     = false;

                            return "{$matches[0]}</span>";
                        }

                        return $matches[0];
                    }
                    else {
                        if ((strpos($matches[1], '//') !== false) || (strpos($matches[1], '#') !== false) || $SLC) {
                            $SLC = true;
                            return $matches[0];
                        }

                        $QO     = true;
                        $QT     = $matches[2];
                        $is_MLQ = true;

                        return "{$matches[1]}<span style=\"{$this->quote}\" class=\"strip quote\">{$matches[2]}";
                    }
                }, $line);
            }

            $pattern = array(
                $this->operators_ptrn,
                $this->number_ptrn,
                $this->class_ptrn,
                preg_replace('/\s\s+/', '', $this->keywords_ptrn),
                $this->function_ptrn,
                $this->variable_ptrn,
                $this->cast_ptrn,
                $this->constant_ptrn,
                $this->parenthesis_ptrn,
                $this->curly_braces_ptrn,
                $this->null_ptrn,
                $this->self_ptrn,
                $this->parent_ptrn,
                $this->bool_ptrn,
                $this->attribute_ptrn,
                $this->comment_ptrn,
                $this->square_bracket_ptrn,
                $this->parameter_type_ptrn,
                $this->return_type_ptrn,
                '/PP_PHP_LONG_TAG_OPEN/',
                '/PP_PHP_SHORT_TAG_OPEN/',
                '/PP_PHP_CLOSE_TAG/',
                '/PP_PHP_DOUBLE_BACK_SLASH/'
            );

            $replacement = array(
                "<span style=\"{$this->operators}\" class=\"operators\">\$0</span>",
                "<span style=\"{$this->number}\" class=\"number\">\$0</span>",
                "<span style=\"{$this->class}\" class=\"number\">\$0</span>",
                "<span style=\"{$this->keywords}\" class=\"keyword\">\$0</span>",
                "<span style=\"{$this->function}\" class=\"function\">\$1</span>",
                "<span style=\"{$this->variable}\" class=\"variable\">\$0</span>",
                "<span style=\"{$this->cast}\" class=\"cast\">\$0</span>",
                "<span style=\"{$this->constant}\" class=\"constant\">\$0</span>",
                "<span style=\"{$this->parenthesis}\" class=\"parenthesis\">\$0</span>",
                "<span style=\"{$this->curly_braces}\" class=\"curly_braces\">\$0</span>",
                "<span style=\"{$this->null}\" class=\"null\">\$0</span>",
                "<span style=\"{$this->self}\" class=\"self\">\$0</span>",
                "<span style=\"{$this->parent}\" class=\"parent\">\$0</span>",
                "<span style=\"{$this->bool}\" class=\"bool\">\$0</span>",
                "<span style=\"{$this->attribute}\" class=\"attribute\">\$0</span>",
                "<span style=\"{$this->comment}\" class=\"strip comment\">\$0</span>",
                "<span style=\"{$this->square_bracket}\" class=\"square_bracket\">\$0</span>",
                "<span style=\"{$this->parameter_type}\" class=\"parameter_type\">\$0</span>",
                "<span style=\"{$this->return_type}\" class=\"return_type\">\$0</span>",
                "<span style=\"{$this->tag_open}\" class=\"tag long\">&lt;?php</span>",
                "<span style=\"{$this->tag_open}\" class=\"tag short\">&lt;?=</span>",
                "<span style=\"{$this->tag_close}\" class=\"tag clode\">?></span>",
                '\\\\\\',
            );

            $line     = $this->isFunction($line);
            $line     = preg_replace($pattern, $replacement, $line);
            $new_code .= "{$line}</td></tr>";
            $token    = strtok($separator);
            $start_number++;
        }

        $new_code .= '<tr class="last-map"><td></td><td></td></tr>';
        $new_code = str_replace(array('\"', '\\\'', '  '), array('"', '\'', '&nbsp;&nbsp;'), $new_code);
        $pretty   = "<table width='100%' class='CH-php'>{$new_code}</table><style>{$this->style}</style>";

        if ($cache) {
            $this->cache($file_name, $pretty);
        }

        return $pretty;
    }

    /**
     * caches formatted strings and handles gc
     *
     * @param $file_name
     * @param null $content
     * @return string|null
     */
    private function cache($file_name, $content = null)
    {
        $file_name = $this->cache_path . DIRECTORY_SEPARATOR . "{$file_name}.cache";
        if ($content == null) {
            return is_readable($file_name) ? file_get_contents($file_name) : null;
        }

        if (! is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0775, true);
        }

        if (is_writable($this->cache_path)) {
            file_put_contents($file_name, $content);
        }

        return null;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setCachePath($path)
    {
        $this->cache_path = $path;

        return $this;
    }

    /**
     * Sets global styles. (This should always be called after setTheme(if using any))
     *
     * @param $css
     * @param bool $override Keep default css or not.
     * @return $this
     */
    public function setStyle($css, $override = false)
    {
        if (! $override) {
            $css = "{$this->style}{$css}";
        }

        $this->style = $css;

        return $this;
    }

    /**
     * displays line numbers
     *
     * @param int $start_line
     * @param bool $selectable  If line should be selectable.
     * @return Highlight
     */
    public function showLineNumber($start_line = 1, $selectable = true)
    {
        $this->start_line = $start_line;
        $this->line_selectable = $selectable;
        $this->show_line_number = true;

        return $this;
    }


    /**
     * adds html attributes to line table > tr
     *
     * @param int $line
     * @param array $attributes
     * @return Highlight
     */
    public function setHighlight($line, array $attributes = array())
    {
        $this->highlight[$line] = $attributes;

        return $this;
    }

    /**
     * @param string $name
     * @return Highlight
     * @throws \Exception
     */
    public function setTheme($name)
    {
        $theme_file = __DIR__ . DIRECTORY_SEPARATOR . 'theme.json';
        if (file_exists($theme_file))
        {
            $all_themes = json_decode(file_get_contents($theme_file), true);
            if ($all_themes === null) {
                throw new \Exception('Invalid/Corrupted theme.json file');
            }

            if (! isset($all_themes[$name])) {
                throw new \Exception("\"{$name}\" is not a valid theme name");
            }

            $buildStyle = function (array $styles) {
                $style = '';
                foreach ($styles as $n => $v) {
                    $style .= "{$n}:{$v};";
                }
                return $style;
            };

            foreach ($all_themes[$name] as $name => &$value) {
                $this->{$name} = is_array($value) ? $buildStyle($value) : $value;
            }
        }

        return $this;
    }

    /**
     * check if code is a file or a string then renders accordingly
     *
     * @param string $code
     * @param bool $is_file
     * @return string
     */
    public function render($code, $is_file = false, $cache = false)
    {
        if ($is_file)
        {
            if ($cache && ($cached = $this->cache($code)) !== null) {
                return $cached;
            }

            return $this->format(file_get_contents($code), $code, $cache);
        }

        return $this->format($code, $is_file, $cache);
    }

}
