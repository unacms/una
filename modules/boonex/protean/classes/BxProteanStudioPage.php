<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Protean Protean template
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_PROTEAN_STUDIO_TEMPL_TYPE_STYLES', 'styles');

class BxProteanStudioPage extends BxTemplStudioDesign
{
    function __construct($sModule = "", $sPage = "")
    {
    	$this->MODULE = 'bx_protean';
        parent::__construct($sModule, $sPage);

        $this->aMenuItems[BX_PROTEAN_STUDIO_TEMPL_TYPE_STYLES] = array('caption' => '_bx_protean_lmi_cpt_styles', 'icon' => 'paint-brush');
        unset($this->aMenuItems[BX_DOL_STUDIO_TEMPL_TYPE_LOGO]);
    }

    protected function getSettings($mixedCategory = '', $sMix = '')
    {
    	return parent::getSettings('bx_protean_system', $sMix);
    }

	protected function getStyles($sMix = '')
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

    	$sPrefix = $this->MODULE;
    	$aCategories = array(
    		$sPrefix . '_styles_general',
			$sPrefix . '_styles_header',
			$sPrefix . '_styles_footer',
			$sPrefix . '_styles_body',
			$sPrefix . '_styles_cover',
			$sPrefix . '_styles_block',
			$sPrefix . '_styles_card',
			$sPrefix . '_styles_popup',
			$sPrefix . '_styles_menu_main',
			$sPrefix . '_styles_menu_account',
			$sPrefix . '_styles_menu_page',
			$sPrefix . '_styles_menu_slide',
			$sPrefix . '_styles_form',
			$sPrefix . '_styles_large_button',
			$sPrefix . '_styles_small_button',
			$sPrefix . '_styles_font',
			$sPrefix . '_styles_custom',
			$sPrefix . '_viewport_tablet',
			$sPrefix . '_viewport_mobile'
		);
    	$oPage = new BxTemplStudioSettings($this->sTemplate, $aCategories, $sMix);
    	$oPage->enableReadOnly(true);
    	$oPage->enableMixes(true);

    	$oTemplate->addJs(array('codemirror/codemirror.min.js'));
        $oTemplate->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css');

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('design.html', array(
            'content' => $oPage->getPageCode(),
        	'js_content' => $this->getPageJsCode(array(
				"sCodeMirror" => "textarea[name='" . $sPrefix . "_styles_custom']"
			))
        ));
    }
}

/** @} */
