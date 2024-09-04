-- TABLES
CREATE TABLE IF NOT EXISTS `bx_credits_withdrawals` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `performer_id` int(11) unsigned NOT NULL default '0',
  `profile_id` int(11) unsigned NOT NULL default '0',
  `amount` float NOT NULL DEFAULT '0',
  `rate` float NOT NULL DEFAULT '0',
  `message` text NOT NULL default '',
  `order` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `confirmed` int(11) unsigned NOT NULL default '0',
  `status` enum('requested', 'canceled', 'confirmed') NOT NULL DEFAULT 'requested',
  PRIMARY KEY (`id`)
);


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_credits_credit_withdraw_confirm';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_credits_credit_withdraw_confirm';
