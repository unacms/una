<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Comments.
 */
class BxBaseUploaderServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * @page service Service Calls
     * @section bx_system_cmts System Services 
     * @subsection bx_system_cmts-general General
     * @subsubsection bx_system_cmts-get_data_api get_data_api
     * 
     * @code bx_srv('system', 'get_data_api', [], 'TemplCmtsServices'); @endcode
     * 
     * Get comments data
     * @param $aParams array with paramenters :
     *         "module":"bx_posts","object_id":3,"start_from":5,"order_way":"desc","is_form":false
     * 
     * @see TemplCmtsServices::serviceGetDataApi
     */
    /** 
     * @ref bx_system_cmts-get_data_api "get_data_api"
     * @api @ref bx_system_cmts-get_data_api "get_data_api"
     */
    
    public function serviceGetDataApi($aParams)
    {
        $sUploaderObject = bx_process_input(bx_get('uo'));
        $sStorageObject = bx_process_input(bx_get('so'));
        $sUniqId = preg_match("/^[\d\w]+$/", bx_get('uid')) ? bx_get('uid') : '';
        $isMultiple = bx_get('m') ? true : false;

        $sFormat = bx_process_input(bx_get('f'));
        if ($sFormat != 'html' &&  $sFormat != 'json')
            $sFormat = 'html';

        $iContentId = bx_get('c');
        if (false === $iContentId || '' === $iContentId)
            $iContentId = false;
        else
            $iContentId = bx_process_input($iContentId, BX_DATA_INT);

        
        $isPrivate = (int)bx_get('p') ? 1 : 0;

        $oUploader = BxDolUploader::getObjectInstance($sUploaderObject, $sStorageObject, $sUniqId);

        $sAction = bx_process_input(bx_get('a'));

        switch ($sAction) {

            case 'restore_ghosts':
                $sImagesTranscoder = bx_process_input(bx_get('img_trans'));
                $oData = $oUploader->getGhostsWithOrder((int)bx_get_logged_profile_id(), $sFormat, $sImagesTranscoder, $iContentId);
                $aRv = isset($oData['g']) ? [$oData['g'], $oData['o']] : [];
                return $aRv;
                break;

            case 'delete':
                header('Content-type: text/html; charset=utf-8');
                $iFileId = bx_process_input(bx_get('id'), BX_DATA_INT);
                return $oUploader->deleteGhost($iFileId, bx_get_logged_profile_id());
                break;

            case 'upload':
                return $oUploader->handleUploads(bx_get_logged_profile_id(), isset($_FILES['f']) ? $_FILES['f'] : null, $isMultiple, $iContentId, $isPrivate);
                break;
                
            case 'upload_inline':
                $sStorageObject = bx_process_input(bx_get('o'));
                $sFile = bx_process_input(bx_get('f'));

                $oStorage = BxDolStorage::getObjectInstance($sStorageObject);
                
                $iProfileId = bx_get_logged_profile_id();

                if (!($iId = $oStorage->storeFileFromForm($_FILES['file'], false, $iProfileId))) {
                    return array('error' => '1');
                    exit;
                }

                $oStorage->afterUploadCleanup($iId, $iProfileId);

                $aFileInfo = $oStorage->getFile($iId);
                if ($aFileInfo && in_array($aFileInfo['ext'], array('jpg', 'jpeg', 'jpe', 'png'))) {
                    $oTranscoder = BxDolTranscoderImage::getObjectInstance(bx_get('t'));
                    $sUrl = $oTranscoder->getFileUrl($iId);
                }
                else {
                    $sUrl = $oStorage->getFileUrlById($iId);
                }
                return ['link' => $sUrl];
                break;

        }
    }
}

/** @} */
