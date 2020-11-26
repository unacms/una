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
            $sIcon = $oStorage ? $oStorage->getFontIconNameByFileName($aFile['file_name']) : 'far file';

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
            array('id' => $aFile['id'], 'url' => $sUrl, 'src' => $sPhotoThumb),
        );
    }

    public function actionBookmark($iContentId) {
        if (!$this->isLogged()) return;

        $aData = $this->_oDb->getContentInfoById((int)$iContentId);
        if (!$aData) return;

        $this->_oDb->bookmarkFile((int)$iContentId, bx_get_logged_profile_id());
    }

    public function actionEntryPreview($iContentId) {
        $aData = $this->_oDb->getContentInfoById((int)$iContentId);

        $sJsCss = '';

        $sPreviewCode = $this->_serviceTemplateFunc ('entryFilePreview', $iContentId);

        // various init scripts could be there, which fail to work properly
        // because of a fact that it is inside a popup which is at the moment is hidden
        // so we need a way to postpone that initialization
        $aScripts = [];
        $sPreviewCode = preg_replace_callback('/<script>(.*)<\/script>/s', function($aMatch) use (&$aScripts) {
            $aScripts[] = ['code' => $aMatch[1]];
            return '';
        }, $sPreviewCode);

        // if there is a script then most likely it is a text + codemirror initialization, so include it here.
        if ($aScripts) {
            $sJsCss .= $this->_oTemplate->addJs('codemirror/codemirror-ext.min.js', true);
            $sJsCss .= $this->_oTemplate->addCss(BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'codemirror/|codemirror.css', true);
        }

        $sJsCss .= $this->_oTemplate->addCss('file_handler.css', true);

        $sPopupCode = $this->_oTemplate->parseHtmlByName('entry-popup.html', [
            'content' => $sPreviewCode,
            'bx_repeat:init_scripts' => $aScripts,
        ]);

        echoJson([
            'popup' => [
                'html' => PopupBox('bx-files-popup', bx_process_output($aData['title']), $sJsCss.$sPopupCode),
                'options' => [
                    'onShow' => 'bx_files_popup_init()',
                ],
            ],
        ]);
    }

    public function actionEntryInfo($iContentId) {
        $aData = $this->_oDb->getContentInfoById((int)$iContentId);

        echoJson([
            'popup' => [
                'html' => PopupBox('bx-files-popup', bx_process_output($aData['title']), $this->_oTemplate->entryInfoPopup($aData)),
            ],
        ]);
    }

    public function actionEntryEditTitle($iContentId) {
        $iContentId = intval($iContentId);
        $aData = $this->_oDb->getContentInfoById($iContentId);

        $CNF = &$this->_oConfig->CNF;

        if (!$aData) {
            echoJson(['message' => _t('_bx_files_txt_error')]);
            exit;
        }

        if ($aData[$CNF['FIELD_AUTHOR']] != bx_get_logged_profile_id()) {
            echoJson(['message' => _t('_bx_files_txt_permission_denied')]);
            exit;
        }

        if (isset($_POST['title'])) {
            if ($sNewTitle = bx_get('title')) {
                $this->_oDb->updateEntryTitle($iContentId, $sNewTitle);
                $aData = $this->_oDb->getContentInfoById($iContentId);
                echo bx_process_output($aData[$CNF['FIELD_TITLE']]);
            }
            exit;
        }

        $sActionUrl = BX_DOL_URL_ROOT.$this->_oConfig->getBaseUri();
        echoJson([
            'eval' => "bx_prompt('"._t('_bx_files_form_entry_input_title')."', '".bx_js_string($aData[$CNF['FIELD_TITLE']])."', function(val) {                
                $.post('{$sActionUrl}entry_edit_title/{$iContentId}', {title: val.getValue()}, function(sTitle) {
                    if (sTitle)
                        $('.bx-file-entry[file_id=".$aData[$CNF['FIELD_ID']]."] .bx-files-filename a').html(sTitle);
                });                
            })",
        ]);
    }

    public function actionEntryDelete($iContentId) {
        $iContentId = intval($iContentId);
        $aData = $this->_oDb->getContentInfoById($iContentId);

        $CNF = &$this->_oConfig->CNF;

        if (!$aData) {
            echoJson(['message' => _t('_bx_files_txt_error')]);
            exit;
        }

        if ($aData[$CNF['FIELD_AUTHOR']] != bx_get_logged_profile_id()) {
            echoJson(['message' => _t('_bx_files_txt_permission_denied')]);
            exit;
        }

        $this->serviceDeleteEntity($iContentId);

        echoJson([
            'eval' => "
                $('.bx-file-entry[file_id={$iContentId}]').fadeOut();
            ",
        ]);
    }

    public function serviceDeleteEntitiesByAuthor ($iProfileId) {
        $this->_oDb->deleteProfileBookmarks($iProfileId);
        return parent::serviceDeleteEntitiesByAuthor ($iProfileId);
    }
}

/** @} */
