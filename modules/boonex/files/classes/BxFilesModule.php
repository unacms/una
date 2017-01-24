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

    /**
     * Display entries of the author
     * @return HTML string
     */
    public function serviceBrowseGroupAuthor ($iProfileId = 0, $aParams = array())
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return '';
        if (!($oGroupProfile = BxDolProfile::getInstance($iProfileId)))
            return '';
        if (!BxDolService::call($oGroupProfile->getModule(), 'is_group_profile'))
            return '';
        
        return $this->_serviceBrowse ('group_author', array_merge(array('author' => $iProfileId), $aParams), BX_DB_PADDING_DEF, true);
    }


    public function serviceIsAllowedAddContentToProfile($iGroupProfileId)
    {
        if (!$iGroupProfileId || !($oProfile = BxDolProfile::getInstance((int)$iGroupProfileId)))
            return false;

        if ($iGroupProfileId == bx_get_logged_profile_id())
            return true;

        $sProfileModule = $oProfile->getModule();
        if (BxDolService::call($sProfileModule, 'is_group_profile') && BxDolService::call($sProfileModule, 'is_fan', array($iGroupProfileId)))
            return true;

        return false;
    }

    public function serviceMyEntriesActions ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId || !($oProfile = BxDolProfile::getInstance($iProfileId)))
            return false;

        $sProfileModule = $oProfile->getModule();
        if ($iProfileId != $this->_iProfileId && !(BxDolService::call($sProfileModule, 'is_group_profile') && BxDolService::call($sProfileModule, 'is_fan', array($iProfileId))))
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_MY_ENTRIES']);
        return $oMenu ? $oMenu->getCode() : false;
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

        if (CHECK_ACTION_RESULT_ALLOWED !== $this->checkAllowedView($aData)) {
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

        if (isset($aData[$CNF['FIELD_FILE_ID']]) && $aData[$CNF['FIELD_FILE_ID']]) 
            return $oStorage->getFile($aData[$CNF['FIELD_FILE_ID']]);
        
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
