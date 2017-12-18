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
    		if(!$this->oDb->isFieldExists('bx_payment_cart', 'customs'))
        		$this->oDb->query("ALTER TABLE `bx_payment_cart` ADD `customs` text NOT NULL default '' AFTER `items`");

			if(!$this->oDb->isFieldExists('bx_payment_transactions_pending', 'customs'))
        		$this->oDb->query("ALTER TABLE `bx_payment_transactions_pending` ADD `customs` text NOT NULL default '' AFTER `items`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
