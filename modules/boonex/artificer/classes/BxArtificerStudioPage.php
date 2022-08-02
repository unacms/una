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

require_once('BxArtificerStudioOptions.php');

define('BX_ARTIFICER_STUDIO_TEMPL_TYPE_STYLES', 'styles');

class BxArtificerStudioPage extends BxTemplStudioDesign
{
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems = bx_array_insert_after([
            BX_ARTIFICER_STUDIO_TEMPL_TYPE_STYLES => ['title' => '_bx_artificer_lmi_cpt_styles', 'icon' => 'paint-brush']
        ], $this->aMenuItems, BX_DOL_STUDIO_TEMPL_TYPE_SETTINGS);
    }

    protected function getSettings($mixedCategory = '', $sMix = '')
    {
    	return parent::getSettings('bx_artificer_system', $sMix);
    }

    protected function getStyles($mixedCategory = '', $sMix = '')
    {
    	$sPrefix = $this->sModule;

        if(empty($mixedCategory))
            $mixedCategory = [
                $sPrefix . '_styles_custom',
            ];

    	$oOptions = new BxArtificerStudioOptions($this->sModule, $mixedCategory, $sMix);

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss(), [BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css']);
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs(), ['codemirror/codemirror.min.js']);
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('design.html', [
            'content' => $oOptions->getCode(),
            'js_content' => $this->getPageJsCode([
                'sCodeMirror' => "textarea[name='" . $sPrefix . "_styles_custom'],textarea[name='" . $sPrefix . "_images_custom']"
            ])
        ]);
    }
}

/** @} */
