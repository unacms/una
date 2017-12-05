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

        $aContentInfo = BxDolService::call($sProfileModule, 'get_content_info_by_id', array($oProfile->getContentId()));
        if (BxDolService::call($sProfileModule, 'is_group_profile') && (BxDolService::call($sProfileModule, 'is_fan', array($iGroupProfileId)) || bx_get_logged_profile_id() == $aContentInfo['author']))
            return true;

        return false;
    }

    public function serviceMyEntriesActions ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

        if (!$this->serviceIsAllowedAddContentToProfile($iProfileId))
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_MY_ENTRIES']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    public function checkAllowedSetThumb ($iContentId = 0)
    {
        return _t('_sys_txt_access_denied');
    }

    public function actionDownload($sToken, $iContentId) 
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
            return $this->_addFileFields($oStorage->getFile($aData[$CNF['FIELD_FILE_ID']]));
        
        $aGhostFiles = $oStorage->getGhosts ($aData[$CNF['FIELD_AUTHOR']], $aData[$CNF['FIELD_ID']]);
        if (!$aGhostFiles)
            return false;

        return $this->_addFileFields(array_pop($aGhostFiles));
    }

    protected function _addFileFields($aFile) 
    {
        if (is_array($aFile))
            $aFile['is_image'] = in_array($aFile['ext'], array('jpg', 'jpeg', 'png', 'gif', /* when ImageMagick is used - 'tif', 'tiff', 'bmp', 'ico', 'psd' */));
        return $aFile;
    }

    public function serviceEntityFilePreview($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryFilePreview', $iContentId);
    }

    public function serviceProcessFilesData($iNumberOfFilesToProcessAtOnce = 3)
    {
        $CNF = $this->_oConfig->CNF;
        
        if (!defined('BX_SYSTEM_JAVA') || !constant('BX_SYSTEM_JAVA'))
            return;
        
        $a = $this->_oDb->getNotProcessedFiles($iNumberOfFilesToProcessAtOnce);
        if (!$a)
            return;

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if (!$oStorage)
            return false;
        
        foreach ($a as $aContentInfo) {
            $aFile = $this->getContentFile($aContentInfo);
            if (!$aFile) {
                $this->_oDb->updateFileData ($aContentInfo[$CNF['FIELD_ID']], '');
                continue;
            }

            $sFileUrl = $oStorage->getFileUrlById($aFile['id']);
            $sFilePath = BX_DIRECTORY_PATH_TMP . $aFile['remote_id'] . '.' . $aFile['ext'];
            @file_put_contents($sFilePath, file_get_contents($sFileUrl));
            if (!file_exists($sFilePath)) {
                $this->_oDb->updateFileData ($aContentInfo[$CNF['FIELD_ID']], '');
                continue;
            }
            
            $sCommand = '"' . constant('BX_SYSTEM_JAVA') . '" -jar "' . $this->_oConfig->getHomePath() . 'data/tika-app.jar" --encoding=UTF-8 --text "' . $sFilePath . '"';
            $sData = `$sCommand`;

            @unlink($sFilePath);

            $this->_oDb->updateFileData ($aContentInfo[$CNF['FIELD_ID']], $sData);
        }
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
        $aResult = parent::_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams);

        $aFile = $this->getContentFile($aContentInfo);
        if (!$aFile['is_image']) {
            $oStorage = BxDolStorage::getObjectInstance($this->_oConfig->CNF['OBJECT_STORAGE']);
            $sIcon = $oStorage ? $oStorage->getFontIconNameByFileName($aFile['file_name']) : 'file-o';

            $aResult['raw'] = $this->_oTemplate->parseHtmlByName('timeline_post.html', array(
                'title' => $aResult['title'],
                'title_attr' => bx_html_attribute($aResult['title']),
                'url' => $aResult['url'],
                'icon' => $sIcon,
            ));
            $aResult['title'] = '';
        }

        return $aResult;
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $aFile = $this->getContentFile($aContentInfo);
        if (!$aFile)
            return array();

        if (!$aFile['is_image'])
            return array();

        $sPhotoThumb = '';
        if ($oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($this->_oConfig->CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']))
            $sPhotoThumb = $oImagesTranscoder->getFileUrl($aFile['id']);

        return array(
		    array('url' => $sUrl, 'src' => $sPhotoThumb),
		);
    }
}

/** @} */
