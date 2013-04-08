<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');


function explodeTags( $text ) {

    //$text = preg_replace( '/[^a-zA-Z0-9_\'-]/', ' ', $text );

    $aTags = preg_split( '/[' . BX_DOL_TAGS_DIVIDER . ']/', $text, 0, PREG_SPLIT_NO_EMPTY );

    foreach( $aTags as $iInd => $sTag )
    {
        if( strlen( $sTag ) < 3 )
            unset( $aTags[$iInd] );
        else
            $aTags[$iInd] = trim(mb_strtolower( $sTag , 'UTF-8'));
    }
    $aTags = array_unique( $aTags );
    $sTagsNotParsed = getParam( 'tags_non_parsable' );
    $aTagsNotParsed = preg_split( '/[' . BX_DOL_TAGS_DIVIDER . ']/', $sTagsNotParsed, 0, PREG_SPLIT_NO_EMPTY );

    $aTags = array_diff( $aTags, $aTagsNotParsed ); //drop non parsable tags

    return $aTags;
}

function storeTags( $iID, $sTags, $sType ) {
    $oDb = BxDolDb::getInstance();

    $sQuery = $oDb->prepare("DELETE FROM `sys_tags` WHERE `ID` = ? AND `Type` = ?", $iID, $sType);
    $oDb->res($sQuery);

    $aTags = explodeTags($sTags);
    foreach ($aTags as $sTag) {
        $sQuery = $oDb->prepare("INSERT INTO `sys_tags` VALUES (?, ?, ?, CURRENT_TIMESTAMP)", $sTag, $iID, $sType);
        $oDb->res($sQuery, 0);
    }
}

function reparseObjTags( $sType, $iID ) {
    bx_import('BxDolTags');
    $oTags = new BxDolTags();
    $oTags->reparseObjTags($sType, $iID);
}

