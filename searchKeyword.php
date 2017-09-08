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

$sClass = 'BxTemplSearch';

$sElsName = 'bx_elasticsearch';
$sElsMethod = 'is_configured';
if(BxDolRequest::serviceExists($sElsName, $sElsMethod) && BxDolService::call($sElsName, $sElsMethod)) {
     $oModule = BxDolModule::getInstance($sElsName);

     bx_import('Search', $oModule->_aModule);
     $sClass = 'BxElsSearch';
}

$oSearch = new $sClass(bx_get('section'));
$oSearch->setLiveSearch(bx_get('live_search') ? 1 : 0);
$oSearch->setMetaType(bx_process_input(bx_get('type')));
$oSearch->setCategoryObject(bx_process_input(bx_get('cat')));

$sCode = '';
if (bx_get('keyword') !== false) {
    $sCode = $oSearch->response();
    if (!$sCode)
        $sCode = $oSearch->getEmptyResult();
}

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader (_t("_Search"));
$oTemplate->setPageContent ('page_main_code', $oSearch->getForm() . $oSearch->getResultsContainer($sCode));
$oTemplate->getPageCode();

/** @} */
