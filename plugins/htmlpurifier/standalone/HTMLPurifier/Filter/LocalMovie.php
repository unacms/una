<?php

class HTMLPurifier_Filter_LocalMovie extends HTMLPurifier_Filter
{
    public $name = 'LocalMovie';

    public function preFilter($html, $config, $context) {
        $localhost = BX_DOL_URL_ROOT . "flash/XML.php"; 
        $pre_regex = '#<object[^>]+>.+?'.$localhost.'([A-Za-z0-9\-_;&=\.]+).+?</object>#s';
        $pre_replace = '<span class="localmovie-embed">\1</span>';
        return preg_replace($pre_regex, $pre_replace, $html);
    }
    
    public function postFilter($html, $config, $context) {
        $localhost = BX_DOL_URL_ROOT . "flash/XML.php";
        $post_regex = '#<span class="localmovie-embed">([A-Za-z0-9\-_;&=\.]+)(module=)([A-Za-z0-9]+)([A-Za-z0-9\-_;&=\.]+)</span>#';
        $post_replace = '<object style="display:block;" width="486" height="400" data="'.BX_DOL_URL_ROOT.'flash/modules/global/app/holder_as3.swf">'.
            '<param name="movie" value="'.BX_DOL_URL_ROOT.'flash/modules/global/app/holder_as3.swf"></param>'.
            '<param name="allowScriptAccess" value="always"></param>'.
            '<param name="allowFullScreen" value="true"></param>'.
            '<param name="base" value="'.BX_DOL_URL_ROOT.'flash/modules/\3/"></param>'.
            '<param name="bgcolor" value="#FFFFFF"></param>'.
            '<param name="wmode" value="opaque"></param>'.
            '<param name="flashVars" value="url='.$localhost.'\1\2\3\4"></param>'.
            '<embed src="'.BX_DOL_URL_ROOT.'flash/modules/global/app/holder_as3.swf"'.
            ' type="application/x-shockwave-flash"'.
            ' width="486" height="400"'.
            ' allowScriptAccess="always" allowFullScreen="true"'.
            ' base="'.BX_DOL_URL_ROOT.'flash/modules/\3/"'.
            ' bgcolor="#FFFFFF" wmode="opaque"'.
            ' flashVars="url='.$localhost.'\1\2\3\4"></embed>'.
            '</object>';
        return preg_replace($post_regex, $post_replace, $html);
    }
    
}
