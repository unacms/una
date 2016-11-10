SET @sName = 'bx_payment';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_payment_subscriptions` (
  `id` int(11) NOT NULL auto_increment,
  `pending_id` int(11) NOT NULL default '0',
  `customer_id` varchar(32) NOT NULL default '',
  `subscription_id` varchar(32) NOT NULL default '',
  `paid` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `tt`.`seller_id` AS `seller_id`, `ttp`.`order` AS `transaction`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''single'' ' WHERE `object`='bx_payment_grid_orders_history';

DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_payment_grid_sbs_list', 'bx_payment_grid_sbs_history');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_grid_sbs_list', 'Sql', 'SELECT `ttp`.`id` AS `id`, `ttp`.`seller_id` AS `seller_id`, `ts`.`customer_id` AS `customer_id`, `ts`.`subscription_id` AS `subscription_id`, `ttp`.`provider` AS `provider`, `ts`.`date` AS `date` FROM `bx_payment_subscriptions` AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ', 'bx_payment_subscriptions', 'id', 'date', '', '', 100, NULL, 'start', '', 'ts`.`customer_id,ts`.`subscription_id,ts`.`date', '', 'auto', '', '', 2147483647, 'BxPaymentGridSbsList', 'modules/boonex/payment/classes/BxPaymentGridSbsList.php'),
('bx_payment_grid_sbs_history', 'Sql', 'SELECT `tt`.`id` AS `id`, `tt`.`seller_id` AS `seller_id`, `ttp`.`order` AS `transaction`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ', 'bx_payment_transactions', 'id', 'date', '', '', 100, NULL, 'start', '', 'ttp`.`order,tt`.`license,tt`.`amount,tt`.`date', '', 'auto', '', '', 2147483647, 'BxPaymentGridSbsHistory', 'modules/boonex/payment/classes/BxPaymentGridSbsHistory.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_payment_grid_sbs_list', 'bx_payment_grid_sbs_history');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list', 'checkbox', '', '2%', 0, '', '', 1),
('bx_payment_grid_sbs_list', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '20%', 0, '24', '', 2),
('bx_payment_grid_sbs_list', 'customer_id', '_bx_payment_grid_column_title_sbs_customer_id', '25%', 0, '24', '', 3),
('bx_payment_grid_sbs_list', 'subscription_id', '_bx_payment_grid_column_title_sbs_subscription_id', '25%', 0, '24', '', 4),
('bx_payment_grid_sbs_list', 'provider', '_bx_payment_grid_column_title_sbs_provider', '10%', 0, '16', '', 5),
('bx_payment_grid_sbs_list', 'actions', '', '18%', 0, '', '', 6),

('bx_payment_grid_sbs_history', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '24%', 0, '20', '', 1),
('bx_payment_grid_sbs_history', 'transaction', '_bx_payment_grid_column_title_sbs_transaction', '22%', 0, '18', '', 2),
('bx_payment_grid_sbs_history', 'license', '_bx_payment_grid_column_title_sbs_license', '22%', 0, '18', '', 3),
('bx_payment_grid_sbs_history', 'amount', '_bx_payment_grid_column_title_sbs_amount', '10%', 1, '10', '', 4),
('bx_payment_grid_sbs_history', 'date', '_bx_payment_grid_column_title_sbs_date', '10%', 0, '10', '', 5),
('bx_payment_grid_sbs_history', 'actions', '', '12%', 0, '', '', 6);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_payment_grid_sbs_list', 'bx_payment_grid_sbs_history');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_payment_grid_sbs_list', 'single', 'view_order', '_bx_payment_grid_action_title_sbs_view_order', 'ellipsis-h', 1, 0, 1),
('bx_payment_grid_sbs_list', 'single', 'actions', '_bx_payment_grid_action_title_sbs_actions', 'cog', 1, 0, 2),

('bx_payment_grid_sbs_history', 'single', 'view_order', '_bx_payment_grid_action_title_sbs_view_order', 'ellipsis-h', 1, 0, 1);


-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_payment_form_strp_card';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_form_strp_card', @sName, '_bx_payment_form_strp_card', '', '', 'do_submit', '', '', '', '', '', 0, 1, '', '');

DELETE FROM `sys_form_displays` WHERE `object`='bx_payment_form_strp_card' AND `display_name`='bx_payment_form_strp_card_add';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_payment_form_strp_card_add', @sName, 'bx_payment_form_strp_card', '_bx_payment_form_strp_card_display_add', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_payment_form_strp_card';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_payment_form_strp_card', @sName, 'card_number', '', '', 0, 'text', '_bx_payment_form_strp_card_input_card_number_sys', '_bx_payment_form_strp_card_input_card_number', '_bx_payment_form_strp_card_input_card_number_inf', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_strp_card', @sName, 'card_expire', '', '', 0, 'text', '_bx_payment_form_strp_card_input_card_expire_sys', '_bx_payment_form_strp_card_input_card_expire', '_bx_payment_form_strp_card_input_card_expire_inf', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_strp_card', @sName, 'card_cvv', '', '', 0, 'text', '_bx_payment_form_strp_card_input_card_cvv_sys', '_bx_payment_form_strp_card_input_card_cvv', '_bx_payment_form_strp_card_input_card_cvv_inf', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_strp_card', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_strp_card', @sName, 'do_submit', '_bx_payment_form_strp_card_input_submit', '', 0, 'submit', '_bx_payment_form_strp_card_input_submit_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_strp_card', @sName, 'do_cancel', '_bx_payment_form_strp_card_input_cancel', '', 0, 'button', '_bx_payment_form_strp_card_input_cancel_sys', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_payment_form_strp_card_add';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_payment_form_strp_card_add', 'card_number', 2147483647, 1, 1),
('bx_payment_form_strp_card_add', 'card_expire', 2147483647, 1, 2),
('bx_payment_form_strp_card_add', 'card_cvv', 2147483647, 1, 3),
('bx_payment_form_strp_card_add', 'controls', 2147483647, 1, 4),
('bx_payment_form_strp_card_add', 'do_submit', 2147483647, 1, 5),
('bx_payment_form_strp_card_add', 'do_cancel', 2147483647, 1, 6);


-- STUDIO PAGE & WIDGET
UPDATE `sys_std_pages` SET `icon`='bx_payment@modules/boonex/payment/|std-icon.svg' WHERE `name`=@sName;
UPDATE `sys_std_widgets` SET `icon`='bx_payment@modules/boonex/payment/|std-icon.svg' WHERE `module`=@sName AND `caption`='_bx_payment';