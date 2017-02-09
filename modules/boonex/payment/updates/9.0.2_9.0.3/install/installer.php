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
			if (!$this->oDb->isIndexExists('bx_payment_subscriptions', 'pending_id'))
				$this->oDb->query("ALTER TABLE `bx_payment_subscriptions` ADD UNIQUE KEY `pending_id` (`pending_id`)");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
