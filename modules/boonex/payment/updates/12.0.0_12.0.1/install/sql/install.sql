SET @sName = 'bx_payment';

DELETE FROM `bx_payment_modules` WHERE `name`=@sName;
INSERT INTO `bx_payment_modules`(`name`) VALUES
(@sName);

CREATE TABLE IF NOT EXISTS `bx_payment_commissions` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(128) NOT NULL default '',
  `description` varchar(128) NOT NULL default '',
  `acl_id` int(11) NOT NULL default '0',
  `percentage` float NOT NULL default '0',
  `installment` float NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '0',
  `order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `name`(`name`)
);

CREATE TABLE IF NOT EXISTS `bx_payment_invoices` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `commissionaire_id` varchar(32) NOT NULL default '',
  `committent_id` varchar(32) NOT NULL default '',
  `amount` float NOT NULL default '0',
  `period_start` int(11) NOT NULL default '0',
  `period_end` int(11) NOT NULL default '0',
  `date_issue` int(11) NOT NULL default '0',
  `date_due` int(11) NOT NULL default '0',
  `status` varchar(32) NOT NULL default 'unpaid',
  `ntf_exp` tinyint(4) NOT NULL default '0',
  `ntf_due` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `name`(`name`)
);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_payment_grid_commissions', 'bx_payment_grid_invoices');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_grid_commissions', 'Sql', 'SELECT * FROM `bx_payment_commissions` WHERE 1 ', 'bx_payment_commissions', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'name', 'caption,description', 'auto', '', '', 192, 'BxPaymentGridCommissions', 'modules/boonex/payment/classes/BxPaymentGridCommissions.php'),
('bx_payment_grid_invoices', 'Sql', 'SELECT * FROM `bx_payment_invoices` WHERE 1 ', 'bx_payment_invoices', 'id', '', '', '', 100, NULL, 'start', '', '', '', 'auto', '', '', 2147483647, 'BxPaymentGridInvoices', 'modules/boonex/payment/classes/BxPaymentGridInvoices.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_payment_grid_commissions', 'bx_payment_grid_invoices');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_commissions', 'order', '', '2%', 0, '0', '', 1),
('bx_payment_grid_commissions', 'switcher', '', '8%', 0, '0', '', 2),
('bx_payment_grid_commissions', 'caption', '_bx_payment_grid_column_title_cms_caption', '15%', 1, '16', '', 3),
('bx_payment_grid_commissions', 'description', '_bx_payment_grid_column_title_cms_description', '20%', 1, '16', '', 4),
('bx_payment_grid_commissions', 'acl_id', '_bx_payment_grid_column_title_cms_acl_id', '15%', 0, '16', '', 5),
('bx_payment_grid_commissions', 'percentage', '_bx_payment_grid_column_title_cms_percentage', '10%', 0, '0', '', 6),
('bx_payment_grid_commissions', 'installment', '_bx_payment_grid_column_title_cms_installment', '10%', 0, '0', '', 7),
('bx_payment_grid_commissions', 'actions', '', '20%', 0, '0', '', 8),

('bx_payment_grid_invoices', 'checkbox', '', '2%', 0, '0', '', 1),
('bx_payment_grid_invoices', 'commissionaire_id', '_bx_payment_grid_column_title_inv_commissionaire_id', '10%', 1, '0', '', 2),
('bx_payment_grid_invoices', 'committent_id', '_bx_payment_grid_column_title_inv_committent_id', '10%', 1, '0', '', 3),
('bx_payment_grid_invoices', 'name', '_bx_payment_grid_column_title_inv_name', '8%', 1, '0', '', 4),
('bx_payment_grid_invoices', 'period_start', '_bx_payment_grid_column_title_inv_period_start', '10%', 0, '0', '', 5),
('bx_payment_grid_invoices', 'period_end', '_bx_payment_grid_column_title_inv_period_end', '10%', 0, '0', '', 6),
('bx_payment_grid_invoices', 'amount', '_bx_payment_grid_column_title_inv_amount', '6%', 0, '0', '', 7),
('bx_payment_grid_invoices', 'date_issue', '_bx_payment_grid_column_title_inv_date_issue', '10%', 0, '0', '', 8),
('bx_payment_grid_invoices', 'date_due', '_bx_payment_grid_column_title_inv_date_due', '10%', 0, '0', '', 9),
('bx_payment_grid_invoices', 'status', '_bx_payment_grid_column_title_inv_status', '6%', 0, '8', '', 10),
('bx_payment_grid_invoices', 'actions', '', '18%', 0, '0', '', 11);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_payment_grid_commissions', 'bx_payment_grid_invoices');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_payment_grid_commissions', 'independent', 'add', '_bx_payment_grid_action_title_cms_add', '', 0, 0, 1),
('bx_payment_grid_commissions', 'bulk', 'delete', '_bx_payment_grid_action_title_cms_delete', '', 0, 1, 1),
('bx_payment_grid_commissions', 'single', 'edit', '_bx_payment_grid_action_title_cms_edit', 'pencil', 1, 0, 1),
('bx_payment_grid_commissions', 'single', 'delete', '_bx_payment_grid_action_title_cms_delete', 'remove', 1, 1, 2),

('bx_payment_grid_invoices', 'bulk', 'delete', '_bx_payment_grid_action_title_inv_delete', '', 0, 1, 1),
('bx_payment_grid_invoices', 'single', 'pay', '_bx_payment_grid_action_title_inv_pay', 'credit-card', 1, 0, 1),
('bx_payment_grid_invoices', 'single', 'edit', '_bx_payment_grid_action_title_inv_edit', 'pencil', 1, 0, 2),
('bx_payment_grid_invoices', 'single', 'delete', '_bx_payment_grid_action_title_inv_delete', 'remove', 1, 1, 3);


-- FORMS
DELETE FROM `sys_objects_form` WHERE `object` IN ('bx_payment_form_commissions', 'bx_payment_form_invoices');
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_form_commissions', @sName, '_bx_payment_form_commissions_form', '', '', 'do_submit', 'bx_payment_commissions', 'id', '', '', '', 0, 1, 'BxPaymentFormCommissions', 'modules/boonex/payment/classes/BxPaymentFormCommissions.php'),
('bx_payment_form_invoices', @sName, '_bx_payment_form_invoices_form', '', '', 'do_submit', 'bx_payment_invoices', 'id', '', '', '', 0, 1, 'BxPaymentFormInvoices', 'modules/boonex/payment/classes/BxPaymentFormInvoices.php');


DELETE FROM `sys_form_displays` WHERE `object` IN ('bx_payment_form_commissions', 'bx_payment_form_invoices');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_payment_form_commissions_add', @sName, 'bx_payment_form_commissions', '_bx_payment_form_commissions_display_add', 0),
('bx_payment_form_commissions_edit', @sName, 'bx_payment_form_commissions', '_bx_payment_form_commissions_display_edit', 0),
('bx_payment_form_invoices_edit', @sName, 'bx_payment_form_invoices', '_bx_payment_form_invoices_display_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object` IN ('bx_payment_form_commissions', 'bx_payment_form_invoices');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_payment_form_commissions', @sName, 'caption', '0', '', 0, 'text_translatable', '_bx_payment_form_commissions_input_caption_sys', '_bx_payment_form_commissions_input_caption', '', 1, 0, 0, '', '', '', 'AvailTranslatable', 'a:1:{i:0;s:7:"caption";}', '_bx_payment_form_commissions_input_caption_err', 'Xss', '', 0, 0),
('bx_payment_form_commissions', @sName, 'description', '0', '', 0, 'textarea_translatable', '_bx_payment_form_commissions_input_description_sys', '_bx_payment_form_commissions_input_description', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_commissions', @sName, 'acl_id', '0', '', 0, 'select', '_bx_payment_form_commissions_input_acl_id_sys', '_bx_payment_form_commissions_input_acl_id', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_payment_form_commissions', @sName, 'percentage', '0', '', 0, 'text', '_bx_payment_form_commissions_input_percentage_sys', '_bx_payment_form_commissions_input_percentage', '', 0, 0, 0, '', '', '', '', '', '', 'Float', '', 0, 0),
('bx_payment_form_commissions', @sName, 'installment', '0', '', 0, 'text', '_bx_payment_form_commissions_input_installment_sys', '_bx_payment_form_commissions_input_installment', '', 0, 0, 0, '', '', '', '', '', '', 'Float', '', 0, 0),
('bx_payment_form_commissions', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_commissions', @sName, 'do_submit', '_bx_payment_form_commissions_input_submit', '', 0, 'submit', '_bx_payment_form_commissions_input_submit_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_commissions', @sName, 'do_cancel', '_bx_payment_form_commissions_input_cancel', '', 0, 'button', '_bx_payment_form_commissions_input_cancel_sys', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),

('bx_payment_form_invoices', @sName, 'amount', '0', '', 0, 'text', '_bx_payment_form_invoices_input_amount_sys', '_bx_payment_form_invoices_input_amount', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_payment_form_invoices_input_amount_err', 'Float', '', 0, 0),
('bx_payment_form_invoices', @sName, 'date_due', '', '', 0, 'datepicker', '_bx_payment_form_invoices_input_date_due_sys', '_bx_payment_form_invoices_input_date_due', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_payment_form_invoices_input_date_due_err', 'DateUtc', '', 0, 0),
('bx_payment_form_invoices', @sName, 'status', '', '', 0, 'select', '_bx_payment_form_invoices_input_status_sys', '_bx_payment_form_invoices_input_status', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_invoices', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_invoices', @sName, 'do_submit', '_bx_payment_form_invoices_input_submit', '', 0, 'submit', '_bx_payment_form_invoices_input_submit_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_invoices', @sName, 'do_cancel', '_bx_payment_form_invoices_input_cancel', '', 0, 'button', '_bx_payment_form_invoices_input_cancel_sys', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_payment_form_commissions_add', 'bx_payment_form_commissions_edit', 'bx_payment_form_invoices_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_payment_form_commissions_add', 'caption', 2147483647, 1, 1),
('bx_payment_form_commissions_add', 'description', 2147483647, 1, 2),
('bx_payment_form_commissions_add', 'acl_id', 2147483647, 1, 3),
('bx_payment_form_commissions_add', 'percentage', 2147483647, 1, 4),
('bx_payment_form_commissions_add', 'installment', 2147483647, 1, 5),
('bx_payment_form_commissions_add', 'controls', 2147483647, 1, 6),
('bx_payment_form_commissions_add', 'do_submit', 2147483647, 1, 7),
('bx_payment_form_commissions_add', 'do_cancel', 2147483647, 1, 8),

('bx_payment_form_commissions_edit', 'caption', 2147483647, 1, 1),
('bx_payment_form_commissions_edit', 'description', 2147483647, 1, 2),
('bx_payment_form_commissions_edit', 'acl_id', 2147483647, 1, 3),
('bx_payment_form_commissions_edit', 'percentage', 2147483647, 1, 4),
('bx_payment_form_commissions_edit', 'installment', 2147483647, 1, 5),
('bx_payment_form_commissions_edit', 'controls', 2147483647, 1, 6),
('bx_payment_form_commissions_edit', 'do_submit', 2147483647, 1, 7),
('bx_payment_form_commissions_edit', 'do_cancel', 2147483647, 1, 8),

('bx_payment_form_invoices_edit', 'amount', 2147483647, 1, 1),
('bx_payment_form_invoices_edit', 'date_due', 2147483647, 1, 2),
('bx_payment_form_invoices_edit', 'status', 2147483647, 1, 3),
('bx_payment_form_invoices_edit', 'controls', 2147483647, 1, 4),
('bx_payment_form_invoices_edit', 'do_submit', 2147483647, 1, 5),
('bx_payment_form_invoices_edit', 'do_cancel', 2147483647, 1, 6);
