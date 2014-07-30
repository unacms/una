<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Notifications Notifications
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplPageDashboard');

/**
 * Entry create/edit pages
 */
class BxNtfsPageView extends BxTemplPageDashboard
{
	protected $sModule;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

    	$this->sModule = 'bx_notifications';        

    	bx_import('BxDolMenu');
        BxDolMenu::getObjectInstance('sys_account_dashboard_submenu')->setSelected($this->sModule, 'account-dashboard-notifications');
    }
}

/** @} */
