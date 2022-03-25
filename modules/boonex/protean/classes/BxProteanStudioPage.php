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
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems[BX_PROTEAN_STUDIO_TEMPL_TYPE_STYLES] = ['title' => '_bx_protean_lmi_cpt_styles', 'icon' => 'paint-brush'];
        unset($this->aMenuItems[BX_DOL_STUDIO_TEMPL_TYPE_LOGO]);
    }

    protected function getSettings($mixedCategory = '', $sMix = '')
    {
    	return parent::getSettings('bx_protean_system', $sMix);
    }

    protected function getStyles($mixedCategory = '', $sMix = '')
    {
    	$sPrefix = $this->sModule;

        if(empty($mixedCategory))
            $mixedCategory = [
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
                $sPrefix . '_styles_large_button_primary',
                $sPrefix . '_styles_button',
                $sPrefix . '_styles_button_primary',
                $sPrefix . '_styles_small_button',
                $sPrefix . '_styles_small_button_primary',
                $sPrefix . '_styles_font',
                $sPrefix . '_styles_custom',
                $sPrefix . '_viewport_tablet',
                $sPrefix . '_viewport_mobile'
            ];

        return parent::getStyles($mixedCategory, $sMix);
    }
}

/** @} */
