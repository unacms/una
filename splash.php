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

bx_import('BxDolAcl');
bx_import('BxDolLanguages');

function getPageMainCode()
{
    $oTemplate = BxDolTemplate::getInstance();    

    $bEnabled = getParam('sys_site_splash_enabled');
    if(!$bEnabled)
    	$oTemplate->displayPageNotFound();

    $sJoinForm = BxDolService::call('system', 'create_account_form', array(), 'TemplServiceAccount');
    $sLoginForm = BxDolService::call('system', 'login_form', array(), 'TemplServiceLogin');

    $oPermalink = BxDolPermalinks::getInstance();

    $oTemplate->addJs(array('lottie.min.js'));
    $s = $oTemplate->parseHtmlByContent(getParam('sys_site_splash_code'), array(
        'logo' => BxTemplFunctions::getInstance()->getMainLogo(array('attrs' => array('class' => 'bx-def-font-color'))),
    	'join_link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=create-account'),
        'join_form' => $sJoinForm,
        'join_form_in_box' => DesignBoxContent(_t('_sys_txt_splash_join'), $sJoinForm, BX_DB_PADDING_DEF),
        'login_link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=login'),
    	'login_form' => $sLoginForm,
        'login_form_in_box' => DesignBoxContent(_t('_sys_txt_splash_login'), $sLoginForm, BX_DB_PADDING_DEF)
    ));

    return bx_process_macros($s);
}

check_logged();

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex(BX_PAGE_DEFAULT);
$oTemplate->setPageType(BX_PAGE_TYPE_DEFAULT_WO_HF);
$oTemplate->setPageHeader(bx_replace_markers(_t('_sys_page_title_home'), array('site_title' => getParam('site_title'))));
$oTemplate->setPageContent('page_main_code', getPageMainCode());
$oTemplate->getPageCode();

/** @} */
