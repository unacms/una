<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioModule extends BxDolStudioModule
{
    protected $aMenuItems = array(
        array('name' => BX_DOL_STUDIO_MOD_TYPE_SETTINGS, 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings')
    );

    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);
    }

    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('module.css'));
    }

    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('settings.js', 'module.js'));
    }

    function getPageJsObject()
    {
        return 'oBxDolStudioModule';
    }

    function getPageCaption()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aTmplVars = array(
            'js_object' => $this->getPageJsObject(),
            'content' => parent::getPageCaption(),
        );
        return $oTemplate->parseHtmlByName('mod_page_caption.html', $aTmplVars);
    }

    function getPageAttributes()
    {
        if((int)$this->aModule['enabled'] == 0)
            return 'style="display:none"';

        return parent::getPageAttributes();
    }

    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        foreach($this->aMenuItems as $aItem)
            $aMenu[] = array(
                'name' => $aItem['name'],
                'icon' => $aItem['icon'],
                'link' => isset($aItem['link'])  ? $aItem['link'] : BX_DOL_URL_STUDIO . 'module.php?name=' . $this->sModule . '&page=' . $aItem['name'],
                'title' => _t($aItem['title']),
                'selected' => $aItem['name'] == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }

    function getPageCode($bHidden = false)
    {
        $sMethod = 'get' . ucfirst($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        if((int)$this->aModule['enabled'] != 1)
            BxDolStudioTemplate::getInstance()->addInjection('injection_bg_style', 'text', ' bx-std-page-bg-empty');

        return $this->$sMethod();
    }

    protected function getSettings()
    {
        $oPage = new BxTemplStudioSettings($this->sModule);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('module.html', array(
            'content' => $oPage->getPageCode(),
        ));
    }
}

/** @} */
