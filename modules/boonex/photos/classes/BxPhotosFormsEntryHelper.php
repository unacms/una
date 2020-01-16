<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxPhotosFormsEntryHelper extends BxBaseModFilesFormsEntryHelper
{
    public function __construct($oModule)
    {
		$this->_sDisplayForFormAdd ='bx_photos_entry_upload';
		$this->_sObjectNameForFormAdd ='bx_photos_upload';
        parent::__construct($oModule);
    }
    
    public function addDataForm ($sDisplay = false, $sCheckFunction = false)
    {
        $mixedContent = $this->addDataFormAction($sDisplay, $sCheckFunction);
        if (is_array($mixedContent) && $mixedContent['need_redirect_after_action']){
            $CNF = &$this->_oModule->_oConfig->CNF;

            $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_AUTHOR_ENTRIES'] . '&profile_id=' . bx_get_logged_profile_id());
            if($this->_bAjaxMode) {
                echoJson($this->prepareResponse($sUrl, $this->_bAjaxMode, 'redirect'));
                exit;
            }
            else
                $this->_redirectAndExit($sUrl);
        }
        else {
                return $mixedContent;
        }
    }
    
    public function onDataEditAfter ($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm)
    {
        $this->updateExif($iContentId, $aContentInfo);
        return parent::onDataEditAfter($iContentId, $aContentInfo, $aTrackTextFieldsChanges, $oProfile, $oForm);
    }
    
    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo){
             $this->updateExif($iContentId, $aContentInfo);
        }
       
        return parent::onDataAddAfter($iAccountId, $iContentId);
    }
            
    function updateExif($iContentId, $aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        
        if (!$oStorage || !$oTranscoder)
            return false;
        $aInfo = getExifAndSizeInfo($oStorage, $oTranscoder, $aContentInfo[$CNF['FIELD_THUMB']]);
        $this->_oModule->_oDb->updateEntries(array($CNF['FIELD_EXIF'] => $aInfo['exif']), array($CNF['FIELD_ID'] => $iContentId));
    }
}

/** @} */
