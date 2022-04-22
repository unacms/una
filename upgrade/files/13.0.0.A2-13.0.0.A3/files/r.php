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

$sRequest = ltrim($_GET['_q'], '/');
$sPath = parse_url(BX_DOL_URL_ROOT, PHP_URL_PATH);
if ($sPath && '/' != $sPath)
    $sRequest = bx_ltrim_str($sRequest, rtrim($sPath, '/'));

$aRewriteRules = BxDolRewriteRulesQuery::getActiveRules();
foreach ($aRewriteRules as $a) {
    if (preg_match('#'.$a['preg'].'#i', $sRequest, $aMatches)) {
        BxDolService::callSerialized($a['service'], $aMatches);
        exit;
    }
}

if (!BxDolPage::processSeoLink($sRequest)) {
    BxDolTemplate::getInstance()->displayPageNotFound();
}

/** @} */
