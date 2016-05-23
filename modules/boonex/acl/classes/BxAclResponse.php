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

class BxAclResponse extends BxDolAlertsResponse
{
	protected $MODULE;
	protected $_oModule;

    public function __construct()
    {
    	$this->MODULE = 'bx_acl';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct();
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
    	if($oAlert->sUnit != 'system' || !in_array($oAlert->sAction, array('page_output_block_acl_level')))
    		return;

		switch($oAlert->sAction) {
			case 'page_output_block_acl_level':
				$oAlert->aExtras['block_code'] .= $this->_oModule->serviceGetMembershipActions((int)$oAlert->aExtras['block_owner']);
				break;
		}
    }
}

/** @} */
