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
            if(!$this->oDb->isFieldExists('bx_payment_providers', 'active'))
                $this->oDb->query("ALTER TABLE `bx_payment_providers` ADD `active` tinyint(4) NOT NULL default '0' AFTER `for_recurring`");
            if(!$this->oDb->isFieldExists('bx_payment_providers', 'order'))
                $this->oDb->query("ALTER TABLE `bx_payment_providers` ADD `order` tinyint(4) NOT NULL default '0' AFTER `active`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
