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

        bx_import('BxDolPermalinks');

        $oModuleMain = BxDolModule::getInstance('bx_persons');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-persons-profile&id=' . $aData['id']);

        $sUrlPicture = $this->urlPicture ($aData);
        $sUrlPreview = $this->urlPreview ($aData);
        $sUrlPictureChange = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=edit-persons-profile&id=' . $aData['id']);

        $sUrlCover = $this->urlCover ($aData);
        $sUrlCoverChange = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=edit-persons-cover&id=' . $aData['id']);        

        $sCoverPopup = '';
        if ($aData[BxPersonsConfig::$FIELD_COVER]) {
            bx_import('BxTemplFunctions');
            $sCoverPopup = BxTemplFunctions::getInstance()->transBox($this->parseHtmlByName('image_popup.html', array (
                'image_url' => $sUrlCover,
                'bx_if:owner' => array (
                    'condition' => CHECK_ACTION_RESULT_ALLOWED === $oModuleMain->isAllowedChangeCover($aData),
                    'content' => array (
                        'change_image_url' => $sUrlCoverChange,
                    ),
                ),
            )), true);
        }

        $sPicturePopup = '';
        if ($aData[BxPersonsConfig::$FIELD_PICTURE]) {
            bx_import('BxTemplFunctions');
            $sPicturePopup = BxTemplFunctions::getInstance()->transBox($this->parseHtmlByName('image_popup.html', array (
                'image_url' => $sUrlPicture,
                'bx_if:owner' => array (
                    'condition' => CHECK_ACTION_RESULT_ALLOWED === $oModuleMain->isAllowedEdit($aData),
                    'content' => array (
                        'change_image_url' => $sUrlPictureChange,
                    ),
                ),
            )), true);
        }

        // generate html        
        $aVars = array (
            'id' => $aData['id'],
            'content_url' => $sUrl,
            'title' => $aData[BxPersonsConfig::$FIELD_NAME],

            'picture_preview_url' => $sUrlPreview,
            'picture_url' => $sUrlPicture,
            'picture_popup' => $sPicturePopup,
            'picture_href' => !$aData[BxPersonsConfig::$FIELD_PICTURE] && CHECK_ACTION_RESULT_ALLOWED === $oModuleMain->isAllowedEdit($aData) ? $sUrlPictureChange : 'javascript:void(0);',

            'cover_popup' => $sCoverPopup,
            'cover_url' => $sUrlCover,
            'cover_href' => !$aData[BxPersonsConfig::$FIELD_COVER] && CHECK_ACTION_RESULT_ALLOWED === $oModuleMain->isAllowedChangeCover($aData) ? $sUrlCoverChange : 'javascript:void(0);',
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
     * Get profile picture preview url
     */
    function urlPreview ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$FIELD_PICTURE, BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_PREVIEW, 'no-picture-preview.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture url
     */
    function urlPicture ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$FIELD_PICTURE, BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_PICTURE, 'no-picture-preview.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile cover image url
     */
    function urlCover ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$FIELD_COVER, BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_COVER, 'no-picture-cover.png', $aData, $bSubstituteNoImage);
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

