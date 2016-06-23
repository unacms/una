<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ACL ACL
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAclStudioPage extends BxTemplStudioModule
{
	protected $MODULE;
	protected $_oModule;

    function __construct($sModule = "", $sPage = "")
    {
    	$this->MODULE = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sModule, $sPage);
    }

    protected function getSettings()
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
