<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Files module
 */
class BxFilesModule extends BxBaseModTextModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function checkAllowedSetThumb ()
    {
        return _t('_sys_txt_access_denied');
    }

    public function actionDownload($iContentId, $sToken = '') 
    {
        $CNF = $this->_oConfig->CNF;
        
        $aData = $this->_oDb->getContentInfoById((int)$iContentId);
        if (!$aData) {
            $this->_oTemplate->displayPageNotFound();
            return;
        }

        if (CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedView($aData)) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }

        $aFile = $this->getContentFile($aData);
        if (!$aFile) {
            $this->_oTemplate->displayPageNotFound();
            return;
        }
            
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if (!$oStorage) {
            $this->_oTemplate->displayErrorOccured();
            return;
        }

        if (!$oStorage->download($aFile['remote_id'], $sToken)) {
            $this->_oTemplate->displayErrorOccured();
            return;
        }
        
        exit;   
    }
    
    public function getContentFile($aData) 
    {
        $CNF = $this->_oConfig->CNF;

        if (!isset($aData[$CNF['FIELD_AUTHOR']]) || !isset($aData[$CNF['FIELD_ID']]))
            return false;

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if (!$oStorage)
            return false;

        $aGhostFiles = $oStorage->getGhosts ($aData[$CNF['FIELD_AUTHOR']], $aData[$CNF['FIELD_ID']]);
        if (!$aGhostFiles)
            return false;

        return array_pop($aGhostFiles);
    }

    public function serviceEntityFilePreview($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryFilePreview', $iContentId);
    }
}

/** @} */
