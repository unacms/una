<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
 *
 * @{
 */

define('BX_DOL_STUDIO_MOD_TYPE_MANAGE', 'manage');

class BxAccntStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        $this->_sModule = 'bx_accounts';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems[BX_DOL_STUDIO_MOD_TYPE_MANAGE] = array('name' => BX_DOL_STUDIO_MOD_TYPE_MANAGE, 'icon' => 'wrench', 'title' => '_bx_accnt_menu_item_title_manage');
    }

    protected function getManage()
    {
        $sType = 'administration';

        $sGrid = $this->_oModule->_oConfig->getGridObject($sType);
        $oGrid = BxDolGrid::getObjectInstance($sGrid, BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        $this->_oModule->_oTemplate->addStudioCss(array('manage_tools.css', 'main.css'));
        $this->_oModule->_oTemplate->addStudioJs(array('manage_tools.js', 'main.js'));
        $this->_oModule->_oTemplate->addStudioJsTranslation(array('_sys_grid_search'));
        return $this->_oModule->_oTemplate->getJsCode('manage_tools', array('sObjNameGrid' => $sGrid)) . $oGrid->getCode();
    }
}

/** @} */
