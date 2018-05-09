<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

class BxSpacesUploaderCoverCrop extends BxBaseModGroupsUploaderCoverCrop
{
    public function __construct ($aObject, $sStorageObject, $sUniqId, $oTemplate)
    {
        $this->_oModule = BxDolModule::getInstance('bx_spaces');
        parent::__construct($aObject, $sStorageObject, $sUniqId, $oTemplate);
    }
}

/** @} */
