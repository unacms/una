<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Developer Developer
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDevTemplate extends BxDolModuleTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);

        $this->addStudioCss(['main.css']);
    }

    function displayPageSettings($sPage, $oContent, $sGetPageCodeMethod = 'getPageCode')
    {
        $this->addStudioCss($oContent->getCss(), false, false);
        $this->addStudioJs($oContent->getJs(), false, false);

        $this->addStudioInjection('injection_body_style', 'text', ' bx-dev-page-body-single');
        return $oContent->getCode();
    }

    function displayPageContent($sPage, $oContent, $sGetPageCodeMethod = 'getPageCode')
    {
        $this->addStudioCss($oContent->getPageCss(), false, false);
        $this->addStudioJs($oContent->getPageJs(), false, false);

        $sMenu = $oContent->getPageMenu();
        $sContent = $oContent->getPageJsCode() . $oContent->$sGetPageCodeMethod();
        if(empty($sMenu)) {
            $this->addStudioInjection('injection_body_style', 'text', ' bx-dev-page-body-single');
            return $sContent;
        }

        $this->addStudioInjection('injection_body_style', 'text', ' bx-dev-page-body-columns');
        return $this->parseHtmlByName('page_content.html', [
            'page_menu_code' => $sMenu,
            'page_main_code' => $sContent
        ]);
    }
}

/** @} */
