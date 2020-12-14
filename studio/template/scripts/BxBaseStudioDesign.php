<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioDesign extends BxDolStudioDesign
{
    protected $aMenuItems = array(
        BX_DOL_STUDIO_TEMPL_TYPE_SETTINGS => array('title' => '_adm_lmi_cpt_settings', 'icon' => 'cogs'),
        BX_DOL_STUDIO_TEMPL_TYPE_LOGO => array('title' => '_adm_lmi_cpt_logo', 'icon' => 'pencil-alt')
    );

    public function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->oHelper = BxTemplStudioDesigns::getInstance();
    }

    public function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('jquery.form.min.js', 'jquery.webForms.js'));
    }

    protected function getSettings($mixedCategory = '', $sMix = '')
    {
        $oPage = new BxTemplStudioSettings($this->sModule, $mixedCategory, $sMix);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('design.html', array(
            'content' => $oPage->getFormCode(),
            'js_content' => $this->getPageJsCode(),
        ));
    }

    protected function getLogo()
    {
    	$oPage = $this->getObjectDesigner();

        $oTemplate = BxDolStudioTemplate::getInstance();
        $oTemplate->addJs($oPage->getPageJs());
        $oTemplate->addCss($oPage->getPageCss());
    	return $oTemplate->parseHtmlByName('design.html', array(
            'content' => $oPage->getPageCode(BX_DOL_STUDIO_TEMPL_TYPE_LOGO, false),
            'js_content' => $this->getPageJsCode()
        ));
    }
}

/** @} */
