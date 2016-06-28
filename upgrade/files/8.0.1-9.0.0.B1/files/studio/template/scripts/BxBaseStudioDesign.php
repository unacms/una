<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioDesign extends BxDolStudioDesign
{
    protected $aMenuItems = array(
        BX_DOL_STUDIO_TEMPL_TYPE_SETTINGS => array('caption' => '_adm_lmi_cpt_settings', 'icon' => 'cogs'),
        BX_DOL_STUDIO_TEMPL_TYPE_LOGO => array('caption' => '_adm_lmi_cpt_logo', 'icon' => 'pencil')
    );

    function __construct($sTemplate = "", $sPage = "")
    {
        parent::__construct($sTemplate, $sPage);
    }
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('settings.css'));
    }
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('jquery.form.min.js', 'jquery.webForms.js', 'settings.js', 'design.js'));
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
        foreach($this->aMenuItems as $sName => $aItem)
            $aMenu[] = array(
                'name' => $sName,
                'icon' => $aItem['icon'],
                'link' => bx_append_url_params($this->sManageUrl, array('page' => $sName)),
                'title' => _t($aItem['caption']),
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

    protected function getSettings($mixedCategory = '', $sMix = '')
    {
        $oPage = new BxTemplStudioSettings($this->sTemplate, $mixedCategory, $sMix);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('design.html', array(
            'content' => $oPage->getPageCode()
        ));
    }

    protected function getLogo()
    {
    	$oPage = $this->getObjectDesigner();

		$oTemplate = BxDolStudioTemplate::getInstance();
        $oTemplate->addJs($oPage->getPageJs());
        $oTemplate->addCss($oPage->getPageCss());
    	return $oTemplate->parseHtmlByName('design.html', array(
            'content' => $oPage->getPageCode()
        ));
    }
}

/** @} */
