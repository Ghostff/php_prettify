<?php
class Regex
{
    protected static $qot_arr = array(
                    '/(?<!\\\\)"(.*?)(?<!\\\\)"|(?<!\\\\)\'(.*?)(?<!\\\\)\'/s'             /* qoutes: "var" */
    );
    
    protected static $mut_arr = '/\*/';                         /* comments: //var or #var */
    
    protected static $com_arr = '
        /http(s?)~SO~~[a-zA-Z0-9]+~SM:~SC\/\/(*SKIP)(*F)|\/\/(.*)?|(?<!~~)#(.*)$/';            /* comments: //var or #var */
    protected static $mcm_arr = '/\/\*(.*?)\*\//s';             /* multy line comments: var  */

    
    protected static $num_arr = '
        /(?:^|\s*)(?<!SO~~|\w)(\d+)(?=\s|\;|\.|\+|\-|\*|\&|\%|\@|\!|\,|\)|\]|\})/';  /* numbers: 0-9 */
                        
    protected static $ocb_arr = '/\(|\)/';                      /* ( and ) */
    
    protected static $occ_arr = '/\{|\}/';                      /* { and } */
    
    protected static $bbk_arr = '/\[|\]/';                      /* { and } */
    
    
    protected static $var_arr = array(
                    '/\$[a-zA-Z_]+[a-zA-Z0-9_]*/'               /* varables: $var */
    );
    
    protected static $tag_arr = array(
                    '/&lt;\?php\b/',                            /* php start tag <?php */
                    '/(?<!\b)\?&gt;/',                          /* php end tag ?>*/
    );
    
    protected static $adn_arr = array(                          /* special characters */
                    '/\bfunction\b/',
                    '/null\b/',
                    '/\=/',
                    '/\./',
                    '/\!/',
                    '/\+/',
                    '/\%/',
                    '/\-/',
                    '/\:/',
                    '/\@/',
                    '/\||\bor\b/',
                    '/\?/',
                    '/&gt;|&lt;/',                              /* < or  > */
                    '/&amp;|\band\b|\bAND\b/',                  /* &, and or AND */
                    
    );
    
    protected static $cst_arr = array(                          /* casting (var) */
                    '/(\(\s*int\s*\))/',
                    '/(\(\s*string\s*\))/',
                    '/(\(\s*float\s*\))/',
                    '/(\(\s*array\s*\))/',
                    '/(\(\s*object\s*\))/',
                    '/(\(\s*unset\s*\))/',
                    '/(\(\s*binary\s*\))/',
                    '/(\(\s*bool\s*\))/'
    );
    
    protected static $stm_arr = array(
                    '/(?<!\$|\w)public\b/',
                    '/(?<!\$|\w)final /',
                    '/(?<!\$|\w)class /',
                    '/(?<!\$|\w)protected\b/',
                    '/(?<!\$|\w)private\b/',
                    '/(?<!\$|\w)new /',
                    '/(?<!\$|\w)extends /',
                    '/(?<!\$|\w)echo\b/',
                    '/(?<!\$)static/',
                    '/(?<!\$|\w)foreach\b/',
                    '/(?<!\$|\w)self\b/',
                    '/(?<!\$|\w)return\b/',
                    '/(?<!\$|\w)if\b/',
                    '/(?<!\$|\w)else\b/',
                    '/(?<!\$|\w)else if\b/',
                    '/(?<!\$|\w)elseif\b/',
                    '/(?<!\$|\w)true|TRUE\b/',
                    '/(?<!\$|\w)false|FALSE\b/',
                    '/(?<!\$|\w)empty\b/',
                    '/(?<!\$|\w)isset\b/',
                    '/(?<!\$|\w)unlink/',
                    '/(?<!\$|\w)unset\b/',
                    '/(?<!\$|\w)null|NULL\b/',
                    '/(?<!\$|\w)break /',
                    '/(?<!\$|\w)exit\b/',
                    '/(?<!\$|\w)die\b/',
                    '/(?<!\$|\w)as /',
                    '/(?<!\$|\w)array\s*\(/',
                    '/(?<!\$|\w)EOD\b/',
                    '/(?<!\$|\w)global /',
                    '/(?<!\$|\w)namespace /',
                    '/(?<!\$|\w)use /',
                    '/(?<!\$|\w)var /',
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
                    '/(?<!\$|\w)list\b/',
					'/(?<!\$|\w)continue\b/'
    );
    
}