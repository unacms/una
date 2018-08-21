<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Froala Froala editor integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFroalaModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function actionUpload()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!($oStorage = BxDolStorage::getObjectInstance('bx_froala_files'))) {
            echo json_encode(array('error' => '1'));
            exit;
        }

        $iProfileId = bx_get_logged_profile_id();

        if (!($iId = $oStorage->storeFileFromForm($_FILES['file'], false, $iProfileId))) {
            echo json_encode(array('error' => '1'));
            exit;
        }

        $oStorage->afterUploadCleanup($iId, $iProfileId);

        $aFileInfo = $oStorage->getFile($iId);
        if ($aFileInfo && in_array($aFileInfo['ext'], array('jpg', 'jpeg', 'jpe', 'png'))) {
            $oTranscoder = BxDolTranscoderImage::getObjectInstance('bx_froala_image');
            $sUrl = $oTranscoder->getFileUrl($iId);
        }
        else {
            $sUrl = $oStorage->getFileUrlById($iId);
        }

        echo json_encode(array('link' => $sUrl));
    }
}

/** @} */
