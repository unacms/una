<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Artificer Artificer template
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_ARTIFICER_STUDIO_TEMPL_TYPE_STYLES', 'styles');

class BxArtificerStudioPage extends BxTemplStudioDesign
{
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->MODULE = 'bx_artificer';

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems = bx_array_insert_after(array(
            BX_ARTIFICER_STUDIO_TEMPL_TYPE_STYLES => array('title' => '_bx_artificer_lmi_cpt_styles', 'icon' => 'paint-brush')
        ), $this->aMenuItems, BX_DOL_STUDIO_TEMPL_TYPE_SETTINGS);
    }

    protected function getSettings($mixedCategory = '', $sMix = '')
    {
    	return parent::getSettings('bx_artificer_system', $sMix);
    }

    protected function getStyles($sMix = '')
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

    	$sPrefix = $this->MODULE;
    	$aCategories = array(
            $sPrefix . '_styles_custom',
        );
    	$oPage = new BxTemplStudioSettings($this->sModule, $aCategories, $sMix);

    	$oTemplate->addJs(array('codemirror/codemirror.min.js'));
        $oTemplate->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css');

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('design.html', array(
            'content' => $oPage->getFormCode(),
            'js_content' => $this->getPageJsCode(array(
                'sCodeMirror' => "textarea[name='" . $sPrefix . "_styles_custom']"
            ))
        ));
    }
}

/** @} */
