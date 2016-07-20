<?php
class Regex
{
    protected static $qot_arr = '/"(.*?)(?<!\\\\)"|\'(.*?)(?<!\\\\)\'/s';        /* 'var' */
    
    protected static $com_arr = '/\/\/(.*)|#(.*)/';
    
    protected static $var_arr = array('/\$[\w\$]+/');                /* $var */
    
    protected static $tag_arr = array(
                    '/&lt;\?php\b/',         /* <?php */
                    '/(?<!\b)\?&gt;/',        /* ?>*/
    );
    
    protected static $adn_arr = array(
                    '/\bfunction\b/',
                    '/\=/',
                    '/\./',
                    '/\!/',
                    
    );
    
    protected static $stm_arr = array(
                    '/(?<!\$|\w)public\b/',
                    '/(?<!\$|\w)final /',
                    '/(?<!\$|\w)class /',
                    '/(?<!\$|\w)protected /',
                    '/(?<!\$|\w)private /',
                    '/(?<!\$|\w)new /',
                    '/(?<!\$|\w)extends /',
                    '/(?<!\$|\w)echo\b/',
                    '/(?<!\$|\w)static /',
                    '/(?<!\$|\w)foreach\b/',
                    '/(?<!\$|\w)self\b/',
                    '/(?<!\$|\w)return\b/',
                    '/(?<!\$|\w)if\b/',
                    '/(?<!\$|\w)else\b/',
                    '/(?<!\$|\w)else if\b/',
                    '/(?<!\$|\w)elseif\b/',
                    '/(?<!\$|\w)true\b/',
                    '/(?<!\$|\w)false\b/',
                    '/(?<!\$|\w)empty\b/',
                    '/(?<!\$|\w)isset\b/',
                    '/(?<!\$|\w)unlink/',
                    '/(?<!\$|\w)unset\b/',
                    '/(?<!\$|\w)NULL\b/',
                    '/(?<!\$|\w)break /',
                    '/(?<!\$|\w)exit\b/',
                    '/(?<!\$|\w)die\b/',
                    '/(?<!\$|\w)as /',
                    '/(?<!\$|\w)array\b/',
                    '/(?<!\$|\w)global /',
                    '/(?<!\$|\w)namespace /',
                    '/(?<!\$|\w)use /',
                    '/(?<!\$|\w)const /',
                    '/(?<!\$|\w)require_once\b/',
                    '/(?<!\$|\w)require\b/',
                    '/(?<!\$|\w)include\b/',
                    '/(?<!\$|\w)include_once\b/',
                    '/(?<!\$|\w)finally\b/',
                    '/(?<!\$|\w)trait /',
                    '/(?<!\$|\w)insteadof /',
                    '/(?<!\$|\w)for\b/',
                    '/(?<!\$|\w)do\b/',
                    '/(?<!\$|\w)while\b/',
                    '/(?<!\$|\w)callable /',
                    '/(?<!\$)parent\b/',
                    '/(?<!\$|\w)try\b/',
                    '/(?<!\$|\w)throw\b/',
                    '/(?<!\$|\w)catch\b/',
                    '/(?<!\$|\w)print\b/',
                    '/(?<!\$|\w)implements /',
                    '/(?<!\$|\w)abstract /',
                    '/(?<!\$|\w)interface /',
                    '/(?<!\$|\w)case\b/',
                    '/(?<!\$|\w)break\b/',
                    '/(?<!\$|\w)default\b/',
                    '/(?<!\$|\w)switch\b/',
                    '/(?<!\$|\w)list\b/'
    );
    
}