<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    FontAwesome Font Awesome Pro integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFontAwesomeModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceSwitchFont($sFont)
    {
        $this->_oDb->switchFont($sFont);
        BxDolCacheUtilities::getInstance()->clear('css');
        BxDolCacheUtilities::getInstance()->clear('db');
    }

    public function serviceInjection()
    {
        // preload active font, so page will load faster
        $aMap = array(
            'fonts-light.css' => array('fa-light-300.woff2'),
            'fonts-duotone.css' => array('fa-duotone-900.woff2'),
            'fonts-all.css' => array('fa-regular-400.woff2', 'fa-solid-900.woff2'),
        );
        $sCss = $this->_oDb->getActiveFont();
        if (!isset($aMap[$sCss]))
            return '';

        $aFonts = $aMap[$sCss];
        $s = '';
        foreach($aFonts as $sFont) {
            $sFontUrl = BX_DOL_URL_MODULES . 'boonex/fontawesome/template/fonts/' . $sFont;
            $s .= '<link rel="preload" as="font" type="font/woff2" crossorigin href="' . $sFontUrl . '" />';
        }
        return $s;
    }
}

/** @} */
