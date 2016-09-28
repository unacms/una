<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxPaymentUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
			if(!$this->oDb->isFieldExists('bx_payment_providers', 'for_single'))
				$this->oDb->query("ALTER TABLE `bx_payment_providers` ADD `for_single` tinyint(4) NOT NULL default '0' AFTER `for_visitor`");

			if($this->oDb->isFieldExists('bx_payment_providers', 'for_subscription'))
        		$this->oDb->query("ALTER TABLE `bx_payment_providers` CHANGE `for_subscription` `for_recurring` tinyint(4) NOT NULL default '0'");

        	if(!$this->oDb->isFieldExists('bx_payment_providers', 'for_recurring'))
        		$this->oDb->query("ALTER TABLE `bx_payment_providers` ADD `for_recurring` tinyint(4) NOT NULL default '0' AFTER `for_single`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
