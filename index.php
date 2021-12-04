<?php

include_once 'src/Highlight.php';

echo '<pre>',
    (new ghostff\Highlight\Highlight())
        ->setTheme('bittr')                       // Sets code highlight theme.
        ->setStyle('body {margin:0;padding:0}')     // Append css to default to style.
//        ->setHighlight(22, ['style' => 'color:red'])    // Add html attributes to selected line(tr).
//        ->showLineNumber(1, false)                      // Show line number starting from line 1 and prevent selection of line number.
        ->render('code.txt', true),
'</pre>';

?>