<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAclStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems['manage'] = array('name' => 'manage', 'icon' => 'wrench', 'title' => '_bx_acl_lmi_cpt_manage');
    }

    protected function getManage()
    {
        if (!isAdmin()) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }

    	$sContent = '';

        $sGrid = $this->_oModule->_oConfig->getGridObject('administration');
        $oGrid = BxDolGrid::getObjectInstance($sGrid, BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return $sContent;

        if($this->_oModule->_oConfig->getOwner() == 0)
            $sContent .= $this->_oModule->_oTemplate->displayEmptyOwner();

        $sContent .= $this->_oModule->_oTemplate->getJsCode('administration', array('sObjNameGrid' => $sGrid));
        $sContent .= $oGrid->getCode();

        return $sContent;
    }
}

/** @} */
