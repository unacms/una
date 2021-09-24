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

bx_import('BxBaseModGeneralTemplate');

class BxArtificerTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_artificer';

        parent::__construct($oConfig, $oDb);
    }

    public function getIncludeCssJs()
    {
        $this->addCss(array(
            //'https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css', 
            'tailwind.min.css',
            'main.css'
        ));

    	$sCss = trim(getParam($this->_oConfig->getName() . '_styles_custom'));
        if(!empty($sCss))
            $sCss = $this->_wrapInTagCssCode($sCss);

        return $sCss;
    }

    public function getHeader()
    {
        return $this->parseHtmlByName('header.html', []);
    }
}

/** @} */
