<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

check_logged();

$sObj = bx_get('obj') ? bx_get('obj') : '';
$sAct = bx_get('act') && preg_match ('/^[A-Za-z_]+$/', bx_get('act')) ? bx_get('act') : '';
$sFmt = bx_get('fmt') ? bx_get('fmt') : 'json';

bx_import('BxDolConnection');
$oConn = BxDolConnection::getObjectInstance($sObj);

$sMethod = 'action' . $sAct;
if ($oConn && $sAct && method_exists($oConn, $sMethod)) {

    echo $oConn->outputActionResult($oConn->$sMethod(), $sFmt);
    exit;

} else {

    bx_import('BxDolLanguages');
    bx_import('BxDolTemplate');

    $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->displayPageNotFound();

}

/** @} */
