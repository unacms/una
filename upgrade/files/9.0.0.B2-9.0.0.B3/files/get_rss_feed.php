<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$sObject = bx_process_input(bx_get('object'));
$mixedId = bx_process_input(bx_get('id'));
$iUserId = bx_process_input(bx_get('member'), BX_DATA_INT);

$oRss = BxDolRss::getObjectInstance($sObject);

if ($oRss && ($s = $oRss->getFeed($mixedId, $iUserId))) {

    header('Content-type: text/xml; charset=utf-8');
	echo $s;

} else {

    BxDolTemplate::getInstance()->displayPageNotFound();
    
}

/** @} */
