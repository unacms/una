<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

define('BX_DOL_STUDIO_LAUNCHER_JS_OBJECT', 'oBxDolStudioLauncher');

bx_import('BxDolStudioLauncher');
bx_import('BxDolStudioTemplate');

class BxBaseStudioLauncher extends BxDolStudioLauncher {
    function BxBaseStudioLauncher() {
        parent::BxDolStudioLauncher();
    }
    function getPageIndex() {
        if(!is_array($this->aPage || empty($this->aPage)))
            return BX_PAGE_DEFAULT;

        return !empty($this->aPage[$this->sPageSelected]) ? $this->aPage[$this->sPageSelected]->getPageIndex() : BX_PAGE_DEFAULT;
    }
    function getPageJs() {
        $aJs = array(
        	'jquery.ui.core.min.js', 
        	'jquery.ui.widget.min.js',
        	'jquery.ui.mouse.min.js',
        	'jquery.ui.sortable.min.js',
        	'jquery.easing.js',
        	'jquery.touch.js',
        	'jquery.cookie.js',
        	'launcher.js'
        );
        foreach($this->aIncludes as $sName => $oInclude)
            $aJs = array_merge($aJs, $oInclude->getJs());

        return $aJs;
    }
    function getPageJsObject() {
        return BX_DOL_STUDIO_LAUNCHER_JS_OBJECT;
    }
    function getPageCss() {
        $aCss = array('launcher.css');
        foreach($this->aIncludes as $sName => $oInclude)
            $aCss = array_merge($aCss, $oInclude->getCss());

        return $aCss;
    }
    function getPageCode($bHidden = false) {
        if(empty($this->aPage) || !is_array($this->aPage))
            return '';

        $sIncludes = '';
        foreach($this->aIncludes as $sName => $oInclude)
            $sIncludes .= $oInclude->getJsCode();

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('launcher.html', array(
        	'js_object' => $this->getPageJsObject(),
            'includes' => $sIncludes,
        	'items' => parent::getPageCode($bHidden)
        ));
    }
}
/** @} */