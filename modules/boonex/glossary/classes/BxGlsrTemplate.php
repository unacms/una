<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Glossary Glossary 
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module representation.
 */
class BxGlsrTemplate extends BxBaseModTextTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_glossary';
        parent::__construct($oConfig, $oDb);
    }

    /**
     * Use Gallery image for both because currently there is no Unit types with small thumbnails.
     */
    protected function getUnitThumbAndGallery ($aData)
    {
        list($sPhotoThumb, $sPhotoGallery) = parent::getUnitThumbAndGallery($aData);
        return array($sPhotoGallery, $sPhotoGallery);
    }
    
    function getAlphabeticalList($aLetterData, $sContentList)
    {
        $aVars = array();
        foreach ($aLetterData as $aLetter)
            array_push($aVars, array ('letter' => $aLetter, 'url' => $_SERVER['REQUEST_URI'] . '#' . $aLetter));
        return $this->parseHtmlByName('alphabetical_list.html', array ('bx_repeat:items' => $aVars, 'content_list' => $sContentList));
    }
    
    function getAlphabeticalAnchor($sLetter)
    {
        return $this->parseHtmlByName('alphabetical_anchor.html', array ('letter' => $sLetter));
    }
}

/** @} */
