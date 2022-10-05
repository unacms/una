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

    public function getIncludeCssJs($sType)
    {
        $sResult = '';

        switch($sType) {
            case 'head':
                $this->addCss([
                    'main.css'
                ]);

                $this->addJs([
                    'utils.js'
                ]);
                $sResult .= $this->_oModule->_oTemplate->getJsCode('utils', [
                    'sColorScheme' => $this->_oConfig->getColorScheme()
                ]);

                $sCss = trim(getParam($this->_oConfig->getName() . '_styles_custom'));
                if(!empty($sCss))
                    $sCss = $this->_wrapInTagCssCode($sCss);
                
                $sResult .= $sCss;
                break;

            case 'footer':
                $sResult .= $this->addJs([
                    'sidebar.js'
                ], true);
                break;
        }

        return $sResult;
    }

    public function getHeader()
    {
        return $this->parseHtmlByName('header.html', []);
    }
}

/** @} */
