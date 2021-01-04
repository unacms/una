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
            if(!$this->oDb->isFieldExists('bx_payment_providers', 'for_owner_only'))
                $this->oDb->query("ALTER TABLE `bx_payment_providers` ADD `for_owner_only` tinyint(4) NOT NULL default '0' AFTER `for_visitor`");
            if(!$this->oDb->isFieldExists('bx_payment_providers', 'single_seller'))
                $this->oDb->query("ALTER TABLE `bx_payment_providers` ADD `single_seller` tinyint(4) NOT NULL default '0' AFTER `for_recurring`");
            if(!$this->oDb->isFieldExists('bx_payment_providers', 'time_tracker'))
                $this->oDb->query("ALTER TABLE `bx_payment_providers` ADD `time_tracker` tinyint(4) NOT NULL default '0' AFTER `single_seller`");

            if(!$this->oDb->isFieldExists('bx_payment_transactions', 'author_id'))
                $this->oDb->query("ALTER TABLE `bx_payment_transactions` ADD `author_id` int(11) NOT NULL default '0' AFTER `seller_id`");

            if(!$this->oDb->isFieldExists('bx_payment_subscriptions', 'period'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` ADD `period` int(11) unsigned NOT NULL default '1' AFTER `subscription_id`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions', 'period_unit'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` ADD `period_unit` varchar(32) NOT NULL default '' AFTER `period`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions', 'trial'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` ADD `trial` int(11) unsigned NOT NULL default '0' AFTER `period_unit`");
            if($this->oDb->isFieldExists('bx_payment_subscriptions', 'date'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` CHANGE `date` `date_add` int(11) NOT NULL default '0'");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions', 'date_next'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` ADD `date_next` int(11) NOT NULL default '0' AFTER `date_add`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions', 'pay_attempts'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` ADD `pay_attempts` tinyint(4) NOT NULL default '0' AFTER `date_next`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions', 'status')) {
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` ADD `status` varchar(32) NOT NULL default 'unpaid' AFTER `pay_attempts`");
                
                $this->oDb->query("UPDATE `bx_payment_subscriptions` SET `status`='active' WHERE `paid` <> 0");

                if($this->oDb->isFieldExists('bx_payment_subscriptions', 'paid'))
                    $this->oDb->query("ALTER TABLE `bx_payment_subscriptions` DROP `paid`");
            }

            if(!$this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'period'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` ADD `period` int(11) unsigned NOT NULL default '1' AFTER `subscription_id`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'period_unit'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` ADD `period_unit` varchar(32) NOT NULL default '' AFTER `period`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'trial'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` ADD `trial` int(11) unsigned NOT NULL default '0' AFTER `period_unit`");
            if($this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'date'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` CHANGE `date` `date_add` int(11) NOT NULL default '0'");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'date_next'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` ADD `date_next` int(11) NOT NULL default '0' AFTER `date_add`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'pay_attempts'))
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` ADD `pay_attempts` tinyint(4) NOT NULL default '0' AFTER `date_next`");
            if(!$this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'status')) {
                $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` ADD `status` varchar(32) NOT NULL default 'unpaid' AFTER `pay_attempts`");
                
                $this->oDb->query("UPDATE `bx_payment_subscriptions_deleted` SET `status`='active' WHERE `paid` <> 0");

                if($this->oDb->isFieldExists('bx_payment_subscriptions_deleted', 'paid'))
                    $this->oDb->query("ALTER TABLE `bx_payment_subscriptions_deleted` DROP `paid`");
            }
        }

        return parent::actionExecuteSql($sOperation);
    }
}
