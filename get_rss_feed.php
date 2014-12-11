<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');

$sObject = bx_process_input(bx_get('object'));
$mixedId = bx_process_input(bx_get('id'));
$iUserId = bx_process_input(bx_get('member'), BX_DATA_INT);

bx_import('BxDolRss');
$oRss = BxDolRss::getObjectInstance($sObject);

if ($oRss && ($s = $oRss->getFeed($mixedId, $iUserId))) {

    header('Content-type: text/xml; charset=utf-8');
	echo $s;

} else {

    bx_import('BxDolLanguages');
    bx_import('BxDolTemplate');
    BxDolTemplate::getInstance()->displayPageNotFound();
    
}

/** @} */
