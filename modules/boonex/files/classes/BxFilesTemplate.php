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

/*
 * Module representation.
 */
class BxFilesTemplate extends BxBaseModTextTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_files';
        parent::__construct($oConfig, $oDb);
    }

    protected function getUnitThumbAndGallery ($aData)
    {
        $aFile = BxDolModule::getInstance($this->MODULE)->getContentFile($aData);

        if (!in_array($aFile['ext'], array('jpg', 'jpeg', 'png', 'gif', /* when ImageMagick is used - 'tif', 'tiff', 'bmp', 'ico', 'psd' */)))
            return array('', '');

        $sPhotoThumb = '';
        if ($oImagesTranscoder = BxDolTranscoderImage::getObjectInstance(BxDolModule::getInstance($this->MODULE)->_oConfig->CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']))
            $sPhotoThumb = $oImagesTranscoder->getFileUrl($aFile['id']);

        return array($sPhotoThumb, $sPhotoThumb);
    }
    
    function unit ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html', $aParams = array())
    {
    	$sResult = $this->checkPrivacy ($aData, $isCheckPrivateContent, $this->getModule(), $sTemplateName);
    	if($sResult)
            return $sResult;

        $CNF = &BxDolModule::getInstance($this->MODULE)->_oConfig->CNF;

        $aVars = $this->getUnit($aData, $aParams);

        $aFile = BxDolModule::getInstance($this->MODULE)->getContentFile($aData);
        
        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);

        $aVars['icon'] = $oStorage ? $oStorage->getFontIconNameByFileName($aFile['file_name']) : 'file-o';
        if ($sTemplateName == 'unit_gallery.html') 
            $aVars['bx_if:no_thumb']['content']['icon'] = $aVars['icon'];

		return $this->parseHtmlByName($sTemplateName, $aVars);
    }

    function entryFilePreview ($aData)
    {
        $oModule = BxDolModule::getInstance($this->MODULE);
        $CNF = $oModule->_oConfig->CNF;

        $sNoPreview = MsgBox(_t('_bx_files_txt_preview_not_available'));
        if (!($aFile = $oModule->getContentFile($aData)))
            return $sNoPreview;
        if (!($oFileHandler = BxDolFileHandler::getObjectInstanceByFile($aFile['file_name'])))
            return $sNoPreview;
        if (!($oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE'])))
            return $sNoPreview;
        if (!($sFileUrl = $oStorage->getFileUrlById($aFile['id'])))
            return $sNoPreview;

        return $oFileHandler->display($sFileUrl, $aFile);
    }
}

/** @} */
