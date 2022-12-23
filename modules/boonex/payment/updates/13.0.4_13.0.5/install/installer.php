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
            if(!$this->oDb->isFieldExists('bx_payment_transactions', 'currency'))
                $this->oDb->query("ALTER TABLE `bx_payment_transactions` ADD `currency` varchar(4) NOT NULL default '' AFTER `amount`");

            if(!$this->oDb->isFieldExists('bx_payment_transactions_pending', 'currency'))
                $this->oDb->query("ALTER TABLE `bx_payment_transactions_pending` ADD `currency` varchar(4) NOT NULL default '' AFTER `amount`");
            if(!$this->oDb->isFieldExists('bx_payment_transactions_pending', 'data'))
                $this->oDb->query("ALTER TABLE `bx_payment_transactions_pending` ADD `data` text NOT NULL AFTER `order`");

            if(!$this->oDb->isFieldExists('bx_payment_invoices', 'currency'))
                $this->oDb->query("ALTER TABLE `bx_payment_invoices` ADD `currency` varchar(4) NOT NULL default '' AFTER `amount`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
