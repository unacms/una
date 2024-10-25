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

        $sStorage = '';
        if(!empty($aParams['storage']))
            $sStorage = $aParams['storage'];
        else if(!empty($CNF['OBJECT_STORAGE']))
            $sStorage = $CNF['OBJECT_STORAGE'];
        else 
            return false;

        $oStorage = BxDolStorage::getObjectInstance($sStorage);
        if(!$oStorage)
            return false;

        $sFieldFileId = '';
        if(!empty($aParams['field']))
            $sFieldFileId = $aParams['field'];
        else if(!empty($CNF['FIELD_FILE_ID']))
            $sFieldFileId = $CNF['FIELD_FILE_ID'];
        else 
            return false;

        return $oStorage->getFile($aContentInfo[$sFieldFileId]);
    }
}

/** @} */
