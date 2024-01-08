SET @sName = 'bx_payment';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_payment_stripe_payments_pending` (
  `id` int(11) NOT NULL auto_increment,
  `subscription_id` varchar(32) NOT NULL default '',
  `amount` float NOT NULL default '0',
  `currency` varchar(4) NOT NULL default '',
  PRIMARY KEY(`id`),
  UNIQUE KEY `subscription_id` (`subscription_id`)
);
