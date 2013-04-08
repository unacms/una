<? defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleTemplate');

class BxDevTemplate extends BxDolModuleTemplate {
    function BxDevTemplate(&$oConfig, &$oDb) {
        parent::BxDolModuleTemplate($oConfig, $oDb);

        $this->addStudioCss(array('main.css'));       
    }

    function displayPageContent(&$oContent) {
        $this->addStudioCss($oContent->getPageCss(), false, false);
        $this->addStudioJs($oContent->getPageJs(), false, false);
        return $this->parseHtmlByName('page_content.html', array(
            'page_menu_code' => $oContent->getPageMenu(),
        	'page_main_code' => $oContent->getPageCode()
        ));
    } 
}

/** @} */