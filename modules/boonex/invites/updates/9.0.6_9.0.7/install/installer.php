<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxInvUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_inv_invites', 'date_seen'))
        		$this->oDb->query("ALTER TABLE `bx_inv_invites` ADD `date_seen` int(11) DEFAULT NULL AFTER `date`");
			if(!$this->oDb->isFieldExists('bx_inv_invites', 'date_joined'))
        		$this->oDb->query("ALTER TABLE `bx_inv_invites` ADD `date_joined` int(11) DEFAULT NULL AFTER `date_seen`");
			if(!$this->oDb->isFieldExists('bx_inv_invites', 'joined_account_id'))
        		$this->oDb->query("ALTER TABLE `bx_inv_invites` ADD `joined_account_id` int(11) DEFAULT NULL AFTER `date_joined`");
			if(!$this->oDb->isFieldExists('bx_inv_invites', 'request_id'))
        		$this->oDb->query("ALTER TABLE `bx_inv_invites` ADD `request_id` int(11) DEFAULT NULL AFTER `joined_account_id`");
			if(!$this->oDb->isIndexExists('bx_inv_invites', 'bx_inv_invites_request_id'))
				$this->oDb->query("ALTER TABLE `bx_inv_invites` ADD INDEX `bx_inv_invites_request_id` (`request_id`)");

			if(!$this->oDb->isFieldExists('bx_inv_requests', 'status'))
        		$this->oDb->query("ALTER TABLE `bx_inv_requests` ADD `status` TINYINT(4) DEFAULT '0' AFTER `date`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
