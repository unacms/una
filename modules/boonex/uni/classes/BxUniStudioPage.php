<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Uni Uni
 * @ingroup     TridentModules
 *
 * @{
 */

define('BX_UNI_STUDIO_TEMPL_TYPE_STYLES', 'styles');

class BxUniStudioPage extends BxTemplStudioDesign
{
    function __construct($sModule = "", $sPage = "")
    {
    	$this->MODULE = 'bx_uni';
        parent::__construct($sModule, $sPage);

        $this->aMenuItems[BX_UNI_STUDIO_TEMPL_TYPE_STYLES] = array('caption' => '_bx_uni_lmi_cpt_styles', 'icon' => 'paint-brush');
        unset($this->aMenuItems[BX_DOL_STUDIO_TEMPL_TYPE_LOGO]);
    }

    protected function getSettings($mixedCategory = '', $sMix = '')
    {
    	return parent::getSettings('bx_uni_system', $sMix);
    }

	protected function getStyles($sMix = '')
    {
    	$sPrefix = $this->MODULE;
    	$aCategories = array(
			$sPrefix . '_styles_header',
			$sPrefix . '_styles_footer',
			$sPrefix . '_styles_body',
			$sPrefix . '_styles_block',
			$sPrefix . '_styles_card',
			$sPrefix . '_styles_popup',
			$sPrefix . '_styles_menu_slide',
			$sPrefix . '_styles_form',
			$sPrefix . '_styles_large_button',
			$sPrefix . '_styles_small_button',
			$sPrefix . '_styles_font',
			$sPrefix . '_viewport_tablet',
			$sPrefix . '_viewport_mobile'
		);
    	$oPage = new BxTemplStudioSettings($this->sTemplate, $aCategories, $sMix);
    	$oPage->enableMixes(true);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('design.html', array(
            'content' => $oPage->getPageCode()
        ));
    }
}

/** @} */
