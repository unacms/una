<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleTemplate');

/*
 * Persons module representation.
 */
class BxPersonsTemplate extends BxDolModuleTemplate {

    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb) {
        parent::__construct($oConfig, $oDb);        
        $this->addCss ('main.css');
    }

    /**
     * Get profile unit
     */
    function unit ($aData, $sTemplateName = 'unit.html') {

        // get person's url
        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-persons-profile&id=' . $aData['id']);

        // generate html
        $aVars = array (
            'id' => $aData['id'],
            'thumb_url' => $this->thumb ($aData),
            'content_url' => $sUrl,
            'title' => $aData[BxPersonsConfig::$FIELD_NAME],
        );

        return $this->parseHtmlByName($sTemplateName, $aVars);

    }

    /**
     * Get profile cover
     */
    function cover ($aData, $sTemplateName = 'cover.html') {

        // get person's url
        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-persons-profile&id=' . $aData['id']);

        // generate html
        $aVars = array (
            'id' => $aData['id'],
            'cover_url' => $this->urlCover ($aData),
            'preview_url' => $this->urlPreview ($aData),
            'content_url' => $sUrl,
            'title' => $aData[BxPersonsConfig::$FIELD_NAME],
        );

        return $this->parseHtmlByName($sTemplateName, $aVars);

    }

    /**
     * Get profile picture thumb url
     */
    function thumb ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$FIELD_PICTURE, BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_THUMB, 'no-picture-thumb.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function icon ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$FIELD_PICTURE, BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_ICON, 'no-picture-icon.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function urlPreview ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$FIELD_PICTURE, BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_PREVIEW, 'no-picture-preview.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile cover image url
     */
    function urlCover ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$FIELD_COVER, BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_COVER, 'no-picture-cover.jpg', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function _image ($sField, $sTranscodeObject, $sNoImage, $aData, $bSubstituteNoImage = true) {
        $sImageUrl = false;
        if ($aData[$sField]) {
            bx_import('BxDolImageTranscoder');                    
            $oImagesTranscoder = BxDolImageTranscoder::getObjectInstance($sTranscodeObject);
            if ($oImagesTranscoder)
                $sImageUrl = $oImagesTranscoder->getImageUrl($aData[$sField]);
        }
        return $bSubstituteNoImage && !$sImageUrl? $this->getImageUrl($sNoImage) : $sImageUrl;
    }
}

/** @} */ 

