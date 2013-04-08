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

    function unit ($aData, $sTemplateName = 'unit.html') {

        // get picture thumb url
        $sPictureThumb = '';
        if ($aData[BxPersonsConfig::$FIELD_PICTURE]) {
            bx_import('BxDolImageTranscoder');                    
            $oImagesTranscoder = BxDolImageTranscoder::getObjectInstance(BxPersonsConfig::$OBJECT_IMAGES_TRANSCODER_THUMB);
            if ($oImagesTranscoder)
                $sPictureThumb = $oImagesTranscoder->getImageUrl($aData[BxPersonsConfig::$FIELD_PICTURE]);
        }

        // get person's url
        bx_import('BxDolPermalinks');
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=view-persons-profile&id=' . $aData['id']);

        // generate html
        $aVars = array (
            'id' => $aData['id'],
            'thumb_url' => $sPictureThumb ? $sPictureThumb : $this->getImageUrl('no-picture-thumb.png'),
            'content_url' => $sUrl,
            'title' => $aData[BxPersonsConfig::$FIELD_NAME],
        );

        return $this->parseHtmlByName($sTemplateName, $aVars);

    }

}

/** @} */ 

