<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

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
