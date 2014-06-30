<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinEndAdmin Dolphin Studio End Admin Pages 
 * @ingroup     DolphinStudio
 * @{
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');
require_once(BX_DOL_DIR_STUDIO_INC . 'utils.inc.php');

bx_import('BxDolLanguages');

bx_require_authentication(true);

$sName = bx_get('name');
if($sName === false)
    $sName = bx_get('mod_value');
$sName = $sName !== false ? bx_process_input($sName) : '';   

$sPage = bx_get('page');
$sPage = $sPage !== false ? bx_process_input($sPage) : '';

$oPage = getPageObject($sName, $sPage);
$oPage->init();

bx_import('BxDolStudioTemplate');
$oTemplate = BxDolStudioTemplate::getInstance();
$oTemplate->setPageNameIndex($oPage->getPageIndex());
$oTemplate->setPageHeader($oPage->getPageHeader());
$oTemplate->setPageContent('page_caption_code', $oPage->getPageCaption());
$oTemplate->setPageContent('page_attributes', $oPage->getPageAttributes());
$oTemplate->setPageContent('page_menu_code', $oPage->getPageMenu());
$oTemplate->setPageContent('page_main_code', $oPage->getPageCode());
$oTemplate->addCss($oPage->getPageCss());
$oTemplate->addJs($oPage->getPageJs());
$oTemplate->getPageCode();

function getPageObject($sName, $sPage) {
    bx_import('BxDolModuleQuery');
    $oModuleDb = BxDolModuleQuery::getInstance();

    if($sName != '' && $oModuleDb->isModuleByName($sName)) {
        $aModule = $oModuleDb->getModuleByName($sName);

        if(file_exists(BX_DIRECTORY_PATH_MODULES . $aModule['path'] . 'classes/' . $aModule['class_prefix'] . 'StudioPage.php')) {
            bx_import('StudioPage', $aModule);

            $sClass = $aModule['class_prefix'] . 'StudioPage';
            return new $sClass($sName, $sPage);
        }   
    }

    bx_import('BxTemplStudioModule');
    return new BxTemplStudioModule($sName, $sPage);
}
/** @} */
