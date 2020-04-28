<?php defined('BX_DOL') or die('hack attempt');
/**
* Copyright (c) UNA, Inc - https://una.io
* MIT License - https://opensource.org/licenses/MIT
*
* @defgroup    MediaManager MediaManager
* @ingroup     UnaModules
*
* @{
*/

/**for 
 * MediaManager module
 */     
class BxMediaModule extends BxDolModule
{         
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
     /**
    * Action methods
    */
          
    public function actionCopyFile()
    {
       
        $sFileName = bx_get('FileName');
        $sStorageObject = bx_get('StorageObject');
        $sUniqId = bx_get('UniqId'); 
        $sUploaderInstanceName = bx_get('UploaderInstanceName');
        $bPrivate = bx_get('isPrivate');
        $iContentId = bx_get('ContentId');
        $sUploaderObject = "bx_media_uploader";
        $oUploader = BxDolUploader::getObjectInstance($sUploaderObject, $sStorageObject, $sUniqId);
        $isMultiple = (bool)bx_get('isMultiple');
        if ($sStorageObject && $sFileName){
            $iProfileId = bx_get_logged_profile_id();
            $oStorage = BxDolStorage::getObjectInstance($sStorageObject);
            if (!$isMultiple){
                $oUploader->deleteGhostsForProfile($iProfileId, $iContentId && $oUploader);
            }
            $iId = $oStorage->storeFileFromUrl($sFileName, $bPrivate, $iProfileId, $iContentId);
            if (!$iId)
                $oUploader->appendUploadErrorMessage(_t('_sys_uploader_err_msg', $sFileName, $oStorage->getErrorString()));
            echo '<script>window.parent.' . $sUploaderInstanceName . '.onUploadCompleted(\'' . bx_js_string($oUploader->getUploadErrorMessages(), BX_ESCAPE_STR_APOS) . '\');</script>';
        }
    }
    
    /**
     * @page service Service Calls
     * @section bx_media Anonymous Follow
     * @subsection bx_media-page_blocks Page Blocks
     * @subsubsection bx_media-include_js include_js
     * 
     * @code bx_srv('bx_media', 'include_js', [...]); @endcode
     * 
     * Add js to injection in head
     *
     * @param $aParams an array with search params.
     * @return void
     * 
     * @see BxMediaModule::serviceIncludeJs
     */
    /** 
     * @ref bx_media-include_js "include_js"
     */
    public function serviceIncludeJs ()
    {
        return $this->_oTemplate->parseHtmlByName('MediaUploaderSettings.js', array());
    }
}

/** @} */