<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinView Dolphin Studio Representation classes
 * @ingroup     DolphinStudio
 * @{
 */

bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioDesign');

class BxBaseStudioDesign extends BxDolStudioDesign
{
    protected $aMenuItems = array(
        'general' => '_adm_lmi_cpt_general'
    );

    function __construct($sTemplate = "", $sPage = "")
    {
        parent::__construct($sTemplate, $sPage);
    }
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array());
    }
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('settings.js', 'design.js'));
    }
    function getPageJsObject()
    {
        return 'oBxDolStudioDesign';
    }
    function getPageCaption()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
            'content' => parent::getPageCaption(),
        );
        return $oTemplate->parseHtmlByName('dsn_page_caption.html', $aTmplVars);
    }
    function getPageAttributes()
    {
        if((int)$this->aTemplate['enabled'] == 0)
            return 'style="display:none"';

        return parent::getPageAttributes();
    }
    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        foreach($this->aMenuItems as $sName => $sCaption)
            $aMenu[] = array(
                'name' => $sName,
                'icon' => 'mi-templ-' . $sName . '.png',
                'link' => BX_DOL_URL_STUDIO . 'design.php?name=' . $this->sTemplate . '&page=' . $sName,
                'title' => _t($sCaption),
                'selected' => $sName == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }
    function getPageCode($bHidden = false)
    {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        if((int)$this->aTemplate['enabled'] != 1)
            BxDolStudioTemplate::getInstance()->addInjection('injection_bg_style', 'text', ' bx-std-page-bg-empty');

        return $this->$sMethod();
    }

    protected function getGeneral()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        bx_import('BxTemplStudioSettings');
        $oPage = new BxTemplStudioSettings($this->sTemplate);

        $aTmplVars = array(
            'bx_repeat:blocks' => $oPage->getPageCode(),
        );
        return $oTemplate->parseHtmlByName('design.html', $aTmplVars);
    }
}

/** @} */
