<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

bx_import('BxDolAcl');
bx_import('BxDolLanguages');

check_logged();

$sSys = isset($_REQUEST['sys']) ? $_REQUEST['sys'] : '';
$iId = (int)$_REQUEST['id'];
$sAction = isset($_REQUEST['action']) && preg_match ('/^[A-Za-z_-]+$/', $_REQUEST['action']) ? $_REQUEST['action'] : '';

bx_import ('BxTemplCmtsView');
$oCmts = new BxTemplCmtsView($sSys, $iId);

if ($oCmts && $sSys && $iId) {
	if($sAction) {
	    header('Content-Type: text/html; charset=utf-8');
	    $sMethod = 'action' . $sAction;
	    echo $oCmts->$sMethod();
	    exit;
	}

	$iCmtId = bx_get('cmt_id');
	if($iCmtId !== false) {
		$sTitle = _t('_cmt_page_header_view');
		$sContent = DesignBoxContent($sTitle, $oCmts->getCommentBlock($iCmtId), BX_DB_PADDING_DEF);

		$oTemplate = BxDolTemplate::getInstance();
		$oTemplate->setPageNameIndex(BX_PAGE_DEFAULT);
		$oTemplate->setPageHeader($sTitle);
		$oTemplate->setPageContent('page_main_code', $sContent);
		$oTemplate->getPageCode();
	}
}
