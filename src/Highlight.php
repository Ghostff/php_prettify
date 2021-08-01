<?php declare(strict_types=1);

namespace ghostff\Highlight;

class Highlight
{
    private array  $highlight        = [];
    private int    $start_line       = 0;
    private bool   $line_selectable  = true;
    private string $cache_path       = __DIR__ . DIRECTORY_SEPARATOR . '.cache';
    private bool   $show_line_number = false;
    private string $style            = '.strip span{color:inherit !important;all:initial !important;all:unset !important}td[unselectable]{ user-select:none;-ms-user-select:none;-moz-user-select:none;-webkit-user-select:none; }';

    private string $cast               = 'color:#038C8C';
    private string $null               = 'color:#0000FF';
    private string $bool               = 'color:#D8C300';
    private string $self               = 'color:#1D6F0C';
    private string $quote              = 'color:#FF0000';
    private string $class              = 'color:#000000';
    private string $parent             = 'color:#1D6F0C';
    private string $number             = 'color:#A4AC21';
    private string $attribute          = 'opacity:0.5';
    private string $comment            = 'color:#FEA500';
    private string $tag_open           = 'color:#F00000';
    private string $keywords           = 'color:#008000;';
    private string $function           = 'color:#0000FF';
    private string $variable           = 'color:#2071ED';
    private string $constant           = 'color:#8C4D03';
    private string $tag_close          = 'color:#F00000';
    private string $operators          = 'color:#0000FF';
    private string $semi_colon         = 'color:#000000';
    private string $parenthesis        = 'color:#038C8C';
    private string $return_type        = 'color:#E3093F';
    private string $php_function       = 'color:#6367A7';
    private string $curly_braces       = 'color:#7F5217';
    private string $parameter_type     = 'color:#E3093F';
    private string $square_bracket     = 'color:#F46164';
    private string $custom_function    = 'color:#A611AA';
    private string $multi_line_comment = 'color:#FEA500';

    private string $self_ptrn               = '/(?<!\$|\w)self/';
    private string $cast_ptrn               = '/(\(\s*(int|string|float|array|object|unset|binary|bool)\s*\))/';
    private string $bool_ptrn               = '/\b(?<!\$)true|false/i';
    private string $null_ptrn               = '/\b(?<!\$)(null)\b/';
    private string $class_ptrn              = '/(class|extends|implements|enum)\s+([\w\\\]+)/';
    private string $quote_ptrn              = '/(.*?)(?<!\\\\)(\'|(?<!((style)=))")/';
    private string $parent_ptrn             = '/(?<!\$|\w)parent\b/';
    private string $number_ptrn             = '/(?<! style="color:#)\b(\d+)\b/';
    private string $comment_ptrn            = '/(?<!http:|https:)\/\/.*|(?<!color:)#(?!\s*\[).*/';
    private string $attribute_ptrn          = '/(?<!color:)#\s*\[.*\]/';
    private string $variable_ptrn           = '/\$(\$*)[a-zA-Z_]+[a-zA-Z0-9_]*/';
    private string $function_ptrn           = '/(?<=\s|^)(function)(?=\s)/';
    private string $constant_ptrn           = '/\b(?<!(\#|\$))([A-Z_]+)(?!<\/\w+>\()\b/';
    private string $keywords_ptrn           = '/(?<!\$|\w)((a(bstract|nd|rray\s*(?=\()|s))|
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
    private string $operators_ptrn          = '/((?<! (style|class))\=|\.|\!|\+|\%|-(?!\w+:)|(?<!https|http)[^a-z+]:|\@|\||\?|&gt;|&lt;|&amp;)/';
    private string $semi_colon_ptrn         = '/(?<![&lt|&gt|&amp]);/';
    private string $parenthesis_ptrn        = '/\(|\)/';
    private string $return_type_ptrn        = '/(?<=\<\/span\>\:|\:\<\/span\>)\s*(?:\<span.*?\>\:<span>)*(string|bool|array|float|int|callable|void)/';
    private string $curly_braces_ptrn       = '/[\{\}]/';
    private string $parameter_type_ptrn     = '/(?<!\w)(string|bool|array|float|int|callable)\s*(?=\<span.*?class="(variable|operators)"\>[\$|&amp;])/';
    private string $square_bracket_ptrn     = '/\[|\]/';
    private string $multi_line_comment_ptrn = '/\/\*|\*\//';

    /**
     * check and highlight user defined  or php pre defined function.
     *
     * @param string $code
     * @return string
     */
    private function isFunction(string $code): string
    {
        return preg_replace_callback('/([n|t]?.?)\b(\w+)(?=\s\(|\()/', function (array $arg): string
        {
            $back = $arg[1];
            $func = $arg[2];
            if ($back == 'n ' || $back == 't;' || ($back == ':' && $func != 'array')) {
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
     * @param string $code
     * @param string|null $file_name
     * @param bool $cache
     * @return string
     */
    private function format(string $code, ?string $file_name, bool $cache): string
    {
        $code = str_replace(
            ['<?php', '<?=', '?>', '\\\\'],
            ['PP_PHP_LONG_TAG_OPEN', 'PP_PHP_SHORT_TAG_OPEN', 'PP_PHP_CLOSE_TAG', 'PP_PHP_DOUBLE_BACK_SLASH'],
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
            if (! $is_MLQ)
            {
                if ($is_MLC) {
                    $line = "<span style=\"{$this->multi_line_comment}\" class=\"strip multi_line_comment\">{$line}</span>";
                }

                $comment = $this->multi_line_comment;
                $line = preg_replace_callback($this->multi_line_comment_ptrn, function(array $matches) use (&$is_MLC, $comment): string {
                    if ($matches[0] == '*/') {
                        $is_MLC = false;
                        return "{$matches[0]}</span>";
                    }

                    $is_MLC = true;
                    return "<span style=\"{$comment}\" class=\"strip multi_line_comment\">{$matches[0]}";
                }, $line);
            }

            if (! $is_MLC)
            {
                if ($is_MLQ) {
                    $line = "<span style=\"{$this->quote}\" class=\"strip quote\">{$line}</span>";
                }

                #single line comment
                $SLC = false;
                $line = preg_replace_callback($this->quote_ptrn, function(array $matches) use (&$QO, &$is_MLQ, &$SLC, &$QT): string
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

            $pattern = [
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
            ];

            $replacement = [
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
            ];

            $line     = $this->isFunction($line);
            $line     = preg_replace($pattern, $replacement, $line);
            $new_code .= "{$line}</td></tr>";
            $token    = strtok($separator);
            $start_number++;
        }

        $new_code .= '<tr class="last-map"><td></td><td></td></tr>';
        $new_code = str_replace(['\"', '\\\'', '  '], ['"', '\'', '&nbsp;&nbsp;'], $new_code);
        $pretty   = "<table width='100%' class='CH-php'>{$new_code}</table><style>{$this->style}</style>";

        if ($cache) {
            $this->cache($file_name, $pretty);
        }

        return $pretty;
    }

    /**
     * caches formatted strings and handles gc
     *
     * @param string $file_name
     * @param string|null $content
     * @return string
     */
    private function cache(string $file_name, string $content = null): string
    {
        $file_name = $this->cache_path . DIRECTORY_SEPARATOR . "{$file_name}.cache";
        if ($content == null) {
            return is_readable($file_name) ? file_get_contents($file_name) : '';
        }

        if (! is_dir($this->cache_path)) {
            mkdir($this->cache_path, 0775, true);
        }

        if (is_writable($this->cache_path)) {
            file_put_contents($file_name, $content);
        }

        return '';
    }

    /**
     * @param string $path
     * @return $this
     */
    public function setCachePath(string $path): Highlight
    {
        $this->cache_path = $path;

        return $this;
    }

    /**
     * Sets global styles. (This should always be called after setTheme(if using any))
     *
     * @param string $css
     * @param bool $override Keep default css or not.
     * @return $this
     */
    public function setStyle(string $css, bool $override = false): Highlight
    {
        $this->style = $override ? $css : "{$this->style}{$css}";

        return $this;
    }

    /**
     * displays line numbers
     *
     * @param int $start_line
     * @param bool $selectable  If line should be selectable.
     * @return Highlight
     */
    public function showLineNumber(int $start_line = 1, bool $selectable = true): Highlight
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
    public function setHighlight(int $line, array $attributes = []): Highlight
    {
        $this->highlight[$line] = $attributes;

        return $this;
    }

    /**
     * @param string $name
     * @return Highlight
     * @throws \Exception
     */
    public function setTheme(string $name): Highlight
    {
        $theme_file = __DIR__ . DIRECTORY_SEPARATOR . 'theme.json';
        if (file_exists($theme_file))
        {
            $all_themes = json_decode(file_get_contents($theme_file), true, JSON_THROW_ON_ERROR);

            if (! isset($all_themes[$name])) {
                throw new \Exception("\"{$name}\" is not a valid theme name");
            }

            $buildStyle = function (array $styles): string {
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
     * @param bool $cache
     * @return string
     */
    public function render(string $code, bool $is_file = false, bool $cache = false): string
    {
        if ($is_file)
        {
            if ($cache && ($cached = $this->cache($code)) !== null) {
                return $cached;
            }

            return $this->format(file_get_contents($code), $code, $cache);
        }

        return $this->format($code, null, $cache);
    }

}
