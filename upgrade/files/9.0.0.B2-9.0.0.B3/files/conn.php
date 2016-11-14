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

check_logged();

$sObj = bx_get('obj') ? bx_get('obj') : '';
$sAct = bx_get('act') && preg_match ('/^[A-Za-z_]+$/', bx_get('act')) ? bx_get('act') : '';
$sFmt = bx_get('fmt') ? bx_get('fmt') : 'json';

$oConn = BxDolConnection::getObjectInstance($sObj);

$sMethod = 'action' . $sAct;
if ($oConn && $sAct && method_exists($oConn, $sMethod)) {

    echo $oConn->outputActionResult($oConn->$sMethod(), $sFmt);
    exit;

} else {

    $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->displayPageNotFound();

}

/** @} */
