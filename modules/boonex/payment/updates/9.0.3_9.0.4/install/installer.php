<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
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
			if(!$this->oDb->isFieldExists('bx_payment_transactions', 'new'))
        		$this->oDb->query("ALTER TABLE `bx_payment_transactions` ADD `new` tinyint(1) NOT NULL default '1' AFTER `date`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
