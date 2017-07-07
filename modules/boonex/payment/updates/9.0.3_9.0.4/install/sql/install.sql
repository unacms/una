SET @sName = 'bx_payment';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_payment_subscriptions_deleted` (
  `id` int(11) NOT NULL auto_increment,
  `pending_id` int(11) NOT NULL default '0',
  `customer_id` varchar(32) NOT NULL default '',
  `subscription_id` varchar(32) NOT NULL default '',
  `paid` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `reason` varchar(16) NOT NULL default '',
  `deleted` int(11) NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `pending_id` (`pending_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='stripe' LIMIT 1);
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId AND `name`='strp_cancellation_email';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'strp_cancellation_email', 'text', '_bx_payment_strp_cancellation_email_cpt', '', '', '', '', '', 9);


-- GRIDS
UPDATE `sys_objects_grid` SET `object`='bx_payment_grid_sbs_list_my' WHERE `object`='bx_payment_grid_sbs_list';

DELETE FROM `sys_objects_grid` WHERE `object`='bx_payment_grid_sbs_list_all';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_grid_sbs_list_all', 'Sql', 'SELECT `ttp`.`id` AS `id`, `ttp`.`client_id` AS `client_id`, `ttp`.`seller_id` AS `seller_id`, `ts`.`customer_id` AS `customer_id`, `ts`.`subscription_id` AS `subscription_id`, `ttp`.`provider` AS `provider`, `ts`.`paid` AS `paid`, `ts`.`date` AS `date` FROM `bx_payment_subscriptions` AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ', 'bx_payment_subscriptions', 'id', 'date', '', '', 100, NULL, 'start', '', 'ts`.`customer_id,ts`.`subscription_id,ts`.`date', '', 'auto', '', '', 192, 'BxPaymentGridSbsAdministration', 'modules/boonex/payment/classes/BxPaymentGridSbsAdministration.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_payment_grid_sbs_list', 'bx_payment_grid_sbs_list_my', 'bx_payment_grid_sbs_list_all');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list_my', 'checkbox', '', '2%', 0, '', '', 1),
('bx_payment_grid_sbs_list_my', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '20%', 0, '24', '', 2),
('bx_payment_grid_sbs_list_my', 'customer_id', '_bx_payment_grid_column_title_sbs_customer_id', '20%', 0, '24', '', 3),
('bx_payment_grid_sbs_list_my', 'subscription_id', '_bx_payment_grid_column_title_sbs_subscription_id', '20%', 0, '24', '', 4),
('bx_payment_grid_sbs_list_my', 'provider', '_bx_payment_grid_column_title_sbs_provider', '10%', 0, '16', '', 5),
('bx_payment_grid_sbs_list_my', 'date', '_bx_payment_grid_column_title_sbs_date', '14%', 0, '10', '', 6),
('bx_payment_grid_sbs_list_my', 'actions', '', '14%', 0, '', '', 7),

('bx_payment_grid_sbs_list_all', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '15%', 0, '24', '', 1),
('bx_payment_grid_sbs_list_all', 'client_id', '_bx_payment_grid_column_title_sbs_client_id', '15%', 0, '24', '', 2),
('bx_payment_grid_sbs_list_all', 'customer_id', '_bx_payment_grid_column_title_sbs_customer_id', '15%', 0, '18', '', 3),
('bx_payment_grid_sbs_list_all', 'subscription_id', '_bx_payment_grid_column_title_sbs_subscription_id', '15%', 0, '18', '', 4),
('bx_payment_grid_sbs_list_all', 'provider', '_bx_payment_grid_column_title_sbs_provider', '10%', 0, '16', '', 5),
('bx_payment_grid_sbs_list_all', 'paid', '_bx_payment_grid_column_title_sbs_paid', '4%', 0, '4', '', 6),
('bx_payment_grid_sbs_list_all', 'date', '_bx_payment_grid_column_title_sbs_date', '10%', 0, '10', '', 7),
('bx_payment_grid_sbs_list_all', 'actions', '', '16%', 0, '', '', 8);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_payment_grid_sbs_list', 'bx_payment_grid_sbs_list_my', 'bx_payment_grid_sbs_list_all');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_payment_grid_sbs_list_my', 'single', 'view_order', '_bx_payment_grid_action_title_sbs_view_order', 'ellipsis-h', 1, 0, 1),
('bx_payment_grid_sbs_list_my', 'single', 'actions', '_bx_payment_grid_action_title_sbs_actions', 'cog', 1, 0, 2),

('bx_payment_grid_sbs_list_all', 'single', 'view_order', '_bx_payment_grid_action_title_sbs_view_order', 'ellipsis-h', 1, 0, 1),
('bx_payment_grid_sbs_list_all', 'single', 'cancel', '_bx_payment_grid_action_title_sbs_cancel', 'times', 1, 1, 2);


-- PRE-VALUES
UPDATE `sys_form_pre_lists` SET `extendable`='0' WHERE `key`='bx_payment_currencies';
