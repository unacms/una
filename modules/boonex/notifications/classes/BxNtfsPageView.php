<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolMenu');
bx_import('BxDolModule');
bx_import('BxTemplPage');

class BxNtfsPageView extends BxTemplPage
{
	protected $_sModule;
	protected $_oModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

    	$this->_sModule = 'bx_notifications';
		$this->_oModule = BxDolModule::getInstance($this->_sModule);

		$this->_oModule->setSubmenu('notifications-all');
    }
}

/** @} */
