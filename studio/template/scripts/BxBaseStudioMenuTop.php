<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioMenuTop extends BxDolStudioMenuTop implements iBxDolSingleton
{
    function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    public static function getInstance()
    {
        if (!isset($GLOBALS['bxDolClasses']['BxBaseStudioMenuTop']))
            $GLOBALS['bxDolClasses']['BxBaseStudioMenuTop'] = new BxTemplStudioMenuTop();

        return $GLOBALS['bxDolClasses']['BxBaseStudioMenuTop'];
    }

    function getCss()
    {
        return array('menu_top.css');
    }

    function getJs()
    {
        return array('menu_top.js');
    }

    function getJsObject()
    {
        return 'oBxDolStudioMenuTop';
    }

    function getCode()
    {
        $aTmplVars = array();
        foreach($this->aItems as $sPosition => $mixedItems) {
            if(!$this->aVisible[$sPosition])
                continue;

            $sContent = "";
            if(is_array($mixedItems)) {
                $oMenu = new BxTemplStudioMenu(array('template' => 'menu_top_toolbar.html', 'menu_items' => $mixedItems));
                $sContent = $oMenu->getCode();
            } else if(is_string($mixedItems) && !empty($mixedItems))
                $sContent = $mixedItems;

            $aTmplVars[] = array(
                'name' => $sPosition,
                'content' => $sContent
            );
        }

        if(empty($aTmplVars))
            return '';

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oTemplate->addJs($this->getJs());
        $oTemplate->addCss($this->getCss());
        return $oTemplate->parseHtmlByName('menu_top.html', array('bx_repeat:menus' => $aTmplVars));
    }
}

/** @} */
