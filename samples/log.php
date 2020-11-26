<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreSamples Samples
 * @{
 */

/**
 * @page samples
 * @section logs Logs object
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");


$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Logs");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $ret = null;
    if ($o = BxDolLogs::getObjectInstance('sys_db_err'))
        $ret = $o->add('Hello world!');
    // $ret = bx_log('sys_db_err', 'Hello world!');

    var_dump($ret);

    if ($o->isGetAvail())
        echoDbg($o->get(30, 'world3'));
    else
        echo 'get logs n/a';

    return DesignBoxContent("Logs", ob_get_clean(), BX_DB_PADDING_DEF);
}

/** @} */
