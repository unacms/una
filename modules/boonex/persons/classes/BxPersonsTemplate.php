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

bx_import('BxDolTwigTemplate');

/*
 * Persons module representation.
 */
class BxPersonsTemplate extends BxDolTwigTemplate {

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

        // get picture thumb url
        $sPictureThumb = $this->thumb ($aData);

        // get person's url
        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-persons-profile&id=' . $aData['id']);

        // generate html
        $aVars = array (
            'id' => $aData['id'],
            'thumb_url' => $sPictureThumb,
            'content_url' => $sUrl,
            'title' => $aData[BxPersonsConfig::$FIELD_NAME],
        );

        return $this->parseHtmlByName($sTemplateName, $aVars);

    }

    /**
     * Get profile picture thumb url
     */
    function thumb ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_THUMB, 'no-picture-thumb.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function icon ($aData, $bSubstituteNoImage = true) {
        return $this->_image (BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_ICON, 'no-picture-icon.png', $aData, $bSubstituteNoImage);
    }

    /**
     * Get profile picture icon url
     */
    function _image ($sTranscodeObject, $sNoImage, $aData, $bSubstituteNoImage = true) {
        if ($aData[BxPersonsConfig::$FIELD_PICTURE]) {
            bx_import('BxDolImageTranscoder');                    
            $oImagesTranscoder = BxDolImageTranscoder::getObjectInstance($sTranscodeObject);
            if ($oImagesTranscoder)
                return $oImagesTranscoder->getImageUrl($aData[BxPersonsConfig::$FIELD_PICTURE]);
        }
        return $bSubstituteNoImage ? $this->getImageUrl('no-picture-icon.png') : '';
    }
}

/** @} */ 

