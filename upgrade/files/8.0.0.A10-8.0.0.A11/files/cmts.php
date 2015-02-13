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

    $iCmtId = bx_get('cmt_id');
    if($iCmtId !== false) {
        $sObjectTitle = $oCmts->getObjectTitle($iObjectId);

        $sHeader = _t('_cmt_page_view_header', $sObjectTitle);
        $sTitle = _t('_cmt_page_view_title', $oCmts->getBaseUrl(), $sObjectTitle);
        $sContent = DesignBoxContent($sTitle, $oCmts->getCommentBlock($iCmtId), BX_DB_PADDING_DEF);

        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex(BX_PAGE_DEFAULT);
        $oTemplate->setPageHeader($sHeader);
        $oTemplate->setPageContent('page_main_code', $sContent);
        $oTemplate->getPageCode();
    }
}

/** @} */
