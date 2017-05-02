<?php

use PhpPrettify\Highlight;
require 'src/PHP5.3/Highlight.php';

Highlight::showLineNumber(true);
#echo '<pre>', Highlight::render('code.txt', 1, 0), '</pre>';exit;

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8"><title>Dayle Rees Colour Scheme Previews</title>
        <style>
            * {margin: 0; padding: 0;}
            table{ border-collapse: collapse;}
            pre {font-family:Monaco, monospace; padding:2em; font-size: 12px; padding: 0; margin: 0;}
            .code .name { padding: 5px 10px;font-size: 19px; text-align: center;font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif}
            table td:first-child { text-align: center}
            table td:last-child { padding-left: 10px;}
            .default table td:last-child { padding-left: 10px;}
            .bittr table td:first-child { background: #272b34;}
            .dark table td:first-child { background: #151515;}
            .owerri table td:first-child { background: #403c37;}
            .light table td:first-child { background: #eeeeee;}
            .steady table td:first-child { background: #45403B;}
            .git table td:first-child { background: #eeeeee;}
            .default table td:first-child { background: #efefef};
        </style>
    </head>
    <body>
        <div class="code default">
            <div class="name" style="background-color:#efefef; color: #666;">default</div>
            <div class="sample" style="color: #000;">
                <pre><?php Highlight::theme("default"); echo Highlight::render('code.txt', 1, 0); ?></pre>
            </div>
        </div>
        <div class="code bittr">
            <div class="name" style="background-color:#272b34; color: #dee3ec;">bittr</div>
            <div class="sample" style="color: #fff;">
                <pre><?php Highlight::theme("bittr"); echo Highlight::render('code.txt', 1, 0); ?></pre>
            </div>
        </div>
        <div class="code dark">
            <div class="name" style="background-color:#151515; color: #dee3ec;">dark</div>
            <div class="sample" style="color: #FFF;">
                <pre><?php Highlight::theme("dark"); echo Highlight::render('code.txt', 1, 0); ?></pre>
            </div>
        </div>
        <div class="code owerri">
            <div class="name" style="background-color:#403c37; color: #dee3ec;">owerri</div>
            <div class="sample" style="color: #FFF;">
                <pre><?php Highlight::theme("owerri"); echo Highlight::render('code.txt', 1, 0); ?></pre>
            </div>
        </div>
        <div class="code light">
            <div class="name" style="background-color:#eeeeee; color: #333;">light</div>
            <div class="sample" style="color: #292929;">
                <pre><?php Highlight::theme("light"); echo Highlight::render('code.txt', 1, 0); ?></pre>
            </div>
        </div>
        <div class="code steady">
            <div class="name" style="background-color:#45403B; color: #EBD1B7;">steady</div>
            <div class="sample" style="color: #EBD1B7;">
                <pre><?php Highlight::theme("steady"); echo Highlight::render('code.txt', 1, 0); ?></pre>
            </div>
        </div>
        <div class="code git">
            <div class="name" style="background-color:#eeeeee; color: #555555;">git</div>
            <div class="sample" style="color: #555555;">
                <pre><?php Highlight::theme("git"); echo Highlight::render('code.txt', 1, 0); ?></pre>
            </div>
        </div>

    </body>
</html>
