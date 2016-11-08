<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMarketUploaderSimple extends BxTemplUploaderSimple
{
    protected $_oModule;

    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_market');
    }

    protected function getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder)
    {
        return $this->_oModule->getGhostTemplateVars($aFile, $iProfileId, $iContentId, $oStorage, $oImagesTranscoder);
    }
}

/** @} */
