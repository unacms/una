<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDonationsStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_donations';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems['manage'] = array('name' => 'manage', 'icon' => 'wrench', 'title' => '_bx_donations_lmi_cpt_manage');
    }

    protected function getManage()
    {
        if (!isAdmin()) {
            $this->_oTemplate->displayAccessDenied();
            return;
        }

        $CNF = &$this->_oModule->_oConfig->CNF;

        $sGrid = $CNF['OBJECT_GRID_TYPES'];
        $oGrid = BxDolGrid::getObjectInstance($sGrid, BxDolStudioTemplate::getInstance());
        if(!$oGrid)
            return '';

        $sContent = '';
        if($this->_oModule->_oConfig->getOwner() == 0)
            $sContent .= $this->_oModule->_oTemplate->displayEmptyOwner();

        $sContent .= $oGrid->getCode();

        return $sContent;
    }
}

/** @} */
