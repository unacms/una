<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseFile Base classes for files modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModFilesModule extends BxBaseModTextModule
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
    
    public function serviceGetFile ($iContentId, $aParams = []) 
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return false;

        $sStorage = !empty($aParams['storage']) ? $aParams['storage'] : $CNF['OBJECT_STORAGE'];
        $oStorage = BxDolStorage::getObjectInstance($sStorage);
        if(!$oStorage)
            return false;

        $sFieldFileId = !empty($aParams['field']) ? $aParams['field'] : $CNF['FIELD_FILE_ID'];
        return $oStorage->getFile($aContentInfo[$sFieldFileId]);
    }
}

/** @} */
