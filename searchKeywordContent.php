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

if (bx_get('keyword')){
    bx_import('BxDolLanguages');

    $sClass = 'BxDolSearch';

    $sElsName = 'bx_elasticsearch';
    $sElsMethod = 'is_configured';
    if(BxDolRequest::serviceExists($sElsName, $sElsMethod) && BxDolService::call($sElsName, $sElsMethod)) {
         $oModule = BxDolModule::getInstance($sElsName);

         bx_import('Search', $oModule->_aModule);
         $sClass = 'BxElsSearch';
    }

    $o = new $sClass(bx_get('section'));
    $o->setLiveSearch(bx_get('live_search') ? 1 : 0);
    $o->setMetaType(bx_process_input(bx_get('type')));
    $o->setCategoryObject(bx_process_input(bx_get('cat')));

    $s = $o->response();
    if (!$s)
        $s = $o->getEmptyResult();

    header('Content-type: text/html; charset=utf-8');
    echo $s;
}
/** @} */
