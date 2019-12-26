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

bx_import('BxDolLanguages');

check_logged();

$sSys = isset($_REQUEST['sys']) ? bx_process_input($_REQUEST['sys']) : '';
$iObjectId = isset($_REQUEST['id']) ? bx_process_input($_REQUEST['id'], BX_DATA_INT) : 0;
$sAction = isset($_REQUEST['action']) && preg_match ('/^[A-Za-z_-]+$/', $_REQUEST['action']) ? bx_process_input($_REQUEST['action']) : '';

$oCmts = BxDolCmts::getObjectInstance($sSys, $iObjectId, true);

if ($oCmts && $sSys && $iObjectId) {
    if($sAction) {
        header('Content-Type: text/html; charset=utf-8');
        $sMethod = 'action' . $sAction;
        echo $oCmts->$sMethod();
        exit;
    }

    if(bx_get('cmt_id') !== false) {
        $oPage = BxDolPage::getObjectInstance('sys_cmts_view');
        if($oPage)
            $oPage->displayPage();
        else
            BxDolTemplate::getInstance()->displayPageNotFound();
    }
}

/** @} */
