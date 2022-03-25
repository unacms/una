<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Marker.io Marker.io
 * @ingroup     UnaModules
 *
 * @{
 */

require_once ('BxMarkerIoStudioOptions.php');

class BxMarkerIoStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_markerio';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);
    }

    protected function getSettings()
    {
        $oOptions = new BxMarkerIoStudioOptions($this->sModule);

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss());
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs());
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('module.html', array(
            'content' => $oOptions->getCode(),
        ));
    }
}

/** @} */
