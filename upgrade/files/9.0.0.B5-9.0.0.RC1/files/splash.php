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

function getPageMainCode()
{
    $oTemplate = BxDolTemplate::getInstance();    

    $bEnabled = getParam('sys_site_splash_enabled');
    if(!$bEnabled)
    	$oTemplate->displayPageNotFound();

    $oTemplate->addJs(array('skrollr/skrollr.min.js'));
    $s = $oTemplate->parseHtmlByContent(getParam('sys_site_splash_code'), array(
    	'join_link' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=create-account'),
    	'login_form' => BxDolService::call('system', 'login_form', array(), 'TemplServiceLogin')
    )); 

    if (getParam('add_to_mobile_homepage') == 'on')
        $s .= BxDolService::call('system', 'add_to_mobile_homepage', array(), 'TemplServices');
        
    return $s;
}

check_logged();

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex(BX_PAGE_DEFAULT);
$oTemplate->setPageContent ('page_main_code', getPageMainCode());
$oTemplate->getPageCode();

/** @} */
