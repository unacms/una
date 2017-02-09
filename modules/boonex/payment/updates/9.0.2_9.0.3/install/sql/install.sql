SET @sName = 'bx_payment';


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_sbs_list';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list', 'checkbox', '', '2%', 0, '', '', 1),
('bx_payment_grid_sbs_list', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '20%', 0, '24', '', 2),
('bx_payment_grid_sbs_list', 'customer_id', '_bx_payment_grid_column_title_sbs_customer_id', '20%', 0, '24', '', 3),
('bx_payment_grid_sbs_list', 'subscription_id', '_bx_payment_grid_column_title_sbs_subscription_id', '20%', 0, '24', '', 4),
('bx_payment_grid_sbs_list', 'provider', '_bx_payment_grid_column_title_sbs_provider', '10%', 0, '16', '', 5),
('bx_payment_grid_sbs_list', 'date', '_bx_payment_grid_column_title_sbs_date', '14%', 0, '10', '', 6),
('bx_payment_grid_sbs_list', 'actions', '', '14%', 0, '', '', 7);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_payment_grid_cart' AND `type`='bulk' AND `name` IN ('delete', 'checkout');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_payment_grid_cart', 'bulk', 'checkout', '_bx_payment_grid_action_title_crt_checkout', '', 0, 0, 1);


-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_payment_form_strp_details';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_form_strp_details', @sName, '_bx_payment_form_strp_details', '', '', 'do_submit', '', '', '', '', '', 0, 1, '', '');

DELETE FROM `sys_form_displays` WHERE `display_name`='bx_payment_form_strp_details_edit';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_payment_form_strp_details_edit', @sName, 'bx_payment_form_strp_details', '_bx_payment_form_strp_details_display_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_payment_form_strp_details';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_payment_form_strp_details', @sName, 'item_id', '', '', 0, 'select', '_bx_payment_form_strp_details_input_item_id_sys', '_bx_payment_form_strp_details_input_item_id', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_payment_form_strp_details_input_item_id_err', 'Int', '', 0, 0),
('bx_payment_form_strp_details', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_strp_details', @sName, 'do_submit', '_bx_payment_form_strp_details_input_submit', '', 0, 'submit', '_bx_payment_form_strp_details_input_submit_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_strp_details', @sName, 'do_cancel', '_bx_payment_form_strp_details_input_cancel', '', 0, 'button', '_bx_payment_form_strp_details_input_cancel_sys', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

UPDATE `sys_form_inputs` SET `checker_func`='Avail', `checker_error`='_bx_payment_form_strp_card_input_card_number_err' WHERE `object`='bx_payment_form_strp_card' AND `name`='card_number';
UPDATE `sys_form_inputs` SET `checker_func`='Avail', `checker_error`='_bx_payment_form_strp_card_input_card_expire_err' WHERE `object`='bx_payment_form_strp_card' AND `name`='card_expire';
UPDATE `sys_form_inputs` SET `checker_func`='Avail', `checker_error`='_bx_payment_form_strp_card_input_card_cvv_err' WHERE `object`='bx_payment_form_strp_card' AND `name`='card_cvv';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_payment_form_strp_details_edit';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_payment_form_strp_details_edit', 'item_id', 2147483647, 1, 1),
('bx_payment_form_strp_details_edit', 'controls', 2147483647, 1, 2),
('bx_payment_form_strp_details_edit', 'do_submit', 2147483647, 1, 3),
('bx_payment_form_strp_details_edit', 'do_cancel', 2147483647, 1, 4);


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_payment_currencies';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_payment_currencies', '_bx_payment_pre_lists_currencies', 'bx_payment', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_payment_currencies';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_payment_currencies', 'AUD', 1, 'AUD', 'A&#36;'),
('bx_payment_currencies', 'CAD', 2, 'CAD', 'C&#36;'),
('bx_payment_currencies', 'EUR', 3, 'EUR', '&#128;'),
('bx_payment_currencies', 'GBP', 4, 'GBP', '&#163;'),
('bx_payment_currencies', 'USD', 5, 'USD', '&#36;'),
('bx_payment_currencies', 'YEN', 6, 'YEN', '&#165;');