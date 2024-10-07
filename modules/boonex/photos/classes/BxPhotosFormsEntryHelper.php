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
        $sKey = 'need_redirect_after_action';
        $mixedContent = $this->addDataFormAction($sDisplay, $sCheckFunction);
        if(is_array($mixedContent) && !empty($mixedContent[$sKey])) {
            $sUrl = $this->getRedirectUrlAfterAdd($mixedContent);

            if($this->_bAjaxMode) {
                echoJson($this->prepareResponse($sUrl, $this->_bAjaxMode, 'redirect'));
                exit;
            }
            else
                $this->_redirectAndExit($sUrl);
        }
        else
            return $mixedContent;
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
    
    public function onDataDeleteAfter($iContentId, $aContentInfo, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if (!empty($CNF['OBJECT_METATAGS_MEDIA_CAMERA'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA_CAMERA']);
            $oMetatags->onDeleteContent($aContentInfo['id']);
        }
        return parent::onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile);
    }
            
    function updateExif($iContentId, $aContentInfo)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        
        if (!$oStorage || !$oTranscoder)
            return false;
        $aInfo = bx_get_image_exif_and_size($oStorage, $oTranscoder, $aContentInfo[$CNF['FIELD_THUMB']]);
        $this->_oModule->_oDb->updateEntries(array($CNF['FIELD_EXIF'] => $aInfo['exif']), array($CNF['FIELD_ID'] => $iContentId));
        
        $aExif = unserialize($aInfo['exif']);
        if ($aContentInfo && isset($aExif['Make']) && !empty($CNF['OBJECT_METATAGS_MEDIA_CAMERA'])) {
		    $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA_CAMERA']);
            if ($oMetatags->keywordsIsEnabled()){
		        $oMetatags->keywordsAddOne($aContentInfo['id'], $oMetatags->keywordsCameraModel($aExif));
			}
        }
    }
}

/** @} */
