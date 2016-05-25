SET @sName = 'bx_acl';


-- TABLES
CREATE TABLE `bx_acl_level_prices` (
  `id` int(11) NOT NULL auto_increment,
  `level_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `period` int(11) unsigned NOT NULL default '1',
  `period_unit` varchar(32) NOT NULL default '',
  `price` float unsigned NOT NULL default '1',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `type` (`level_id`,`period`, `period_unit`)
);


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_acl_price', @sName, '_bx_acl_form_price', '', '', 'do_submit', 'bx_acl_level_prices', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_acl_price_add', @sName, 'bx_acl_price', '_bx_acl_form_price_display_add', 0),
('bx_acl_price_edit', @sName, 'bx_acl_price', '_bx_acl_form_price_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_acl_price', @sName, 'id', '', '', 0, 'hidden', '_bx_acl_form_price_input_sys_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_acl_price', @sName, 'level_id', '', '', 0, 'hidden', '_bx_acl_form_price_input_sys_level_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_acl_price', @sName, 'period', '', '', 0, 'text', '_bx_acl_form_price_input_sys_period', '_bx_acl_form_price_input_period', '_bx_acl_form_price_input_inf_period', 1, 0, 0, '', '', '', 'Avail', '', '_bx_acl_form_price_input_err_period', 'Int', '', 1, 0),
('bx_acl_price', @sName, 'period_unit', '', '#!bx_acl_period_units', 0, 'select', '_bx_acl_form_price_input_sys_period_unit', '_bx_acl_form_price_input_period_unit', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_acl_form_price_input_err_period_unit', 'Xss', '', 1, 0),
('bx_acl_price', @sName, 'price', '', '', 0, 'text', '_bx_acl_form_price_input_sys_price', '_bx_acl_form_price_input_price', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_acl_form_price_input_err_price', 'Float', '', 1, 0),
('bx_acl_price', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_acl_price', @sName, 'do_submit', '_bx_acl_form_price_input_do_submit', '', 0, 'submit', '_bx_acl_form_price_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_acl_price', @sName, 'do_cancel', '_bx_acl_form_price_input_do_cancel', '', 0, 'button', '_bx_acl_form_price_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_acl_price_add', 'id', 2147483647, 0, 1),
('bx_acl_price_add', 'level_id', 2147483647, 1, 2),
('bx_acl_price_add', 'period', 2147483647, 1, 3),
('bx_acl_price_add', 'period_unit', 2147483647, 1, 4),
('bx_acl_price_add', 'price', 2147483647, 1, 5),
('bx_acl_price_add', 'controls', 2147483647, 1, 6),
('bx_acl_price_add', 'do_submit', 2147483647, 1, 7),
('bx_acl_price_add', 'do_cancel', 2147483647, 1, 8),

('bx_acl_price_edit', 'id', 2147483647, 1, 1),
('bx_acl_price_edit', 'level_id', 2147483647, 1, 2),
('bx_acl_price_edit', 'period', 2147483647, 1, 3),
('bx_acl_price_edit', 'price', 2147483647, 1, 4),
('bx_acl_price_edit', 'controls', 2147483647, 1, 5),
('bx_acl_price_edit', 'do_submit', 2147483647, 1, 6),
('bx_acl_price_edit', 'do_cancel', 2147483647, 1, 7);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_acl_period_units', '_bx_acl_pre_lists_period_units', 'bx_acl', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_acl_period_units', '', 0, '_sys_please_select', ''),
('bx_acl_period_units', 'day', 1, '_bx_acl_pre_values_day', ''),
('bx_acl_period_units', 'week', 2, '_bx_acl_pre_values_week', ''),
('bx_acl_period_units', 'month', 3, '_bx_acl_pre_values_month', ''),
('bx_acl_period_units', 'year', 4, '_bx_acl_pre_values_year', '');


-- GRIDS
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_acl_administration', 'Sql', 'SELECT * FROM `bx_acl_level_prices` WHERE 1 ', 'bx_acl_level_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'period,period_unit,price', '', 'like', '', '', 'BxAclGridAdministration', 'modules/boonex/acl/classes/BxAclGridAdministration.php'),
('bx_acl_view', 'Sql', 'SELECT `tlp`.*, `tl`.`Name` AS `level_name` FROM `bx_acl_level_prices` AS `tlp` LEFT JOIN `sys_acl_levels` AS `tl` ON `tlp`.`level_id`=`tl`.`ID` WHERE `tl`.`Active`=''yes'' AND `tl`.`Purchasable`=''yes'' ', 'bx_acl_level_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'period,period_unit,price', 'tl`.`Name', 'like', '', '', 'BxAclGridView', 'modules/boonex/acl/classes/BxAclGridView.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_acl_administration', 'checkbox', '_sys_select', '1%', 0, '', '', 1),
('bx_acl_administration', 'order', '', '1%', 0, '', '', 2),
('bx_acl_administration', 'period', '_bx_acl_grid_column_period', '26%', 0, 16, '', 3),
('bx_acl_administration', 'period_unit', '_bx_acl_grid_column_period_unit', '26%', 0, 16, '', 4),
('bx_acl_administration', 'price', '_bx_acl_grid_column_price', '26%', 0, 16, '', 5),
('bx_acl_administration', 'actions', '', '20%', 0, '', '', 6),

('bx_acl_view', 'level_name', '_bx_acl_grid_column_level_name', '35%', 1, 36, '', 1),
('bx_acl_view', 'period', '_bx_acl_grid_column_period', '15%', 0, 16, '', 2),
('bx_acl_view', 'period_unit', '_bx_acl_grid_column_period_unit', '15%', 0, 16, '', 3),
('bx_acl_view', 'price', '_bx_acl_grid_column_price', '15%', 0, 16, '', 4),
('bx_acl_view', 'actions', '', '20%', 0, '', '', 5);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_acl_administration', 'independent', 'add', '_bx_acl_grid_action_add', '', 0, 0, 1),
('bx_acl_administration', 'single', 'edit', '_bx_acl_grid_action_edit', 'pencil', 1, 0, 1),
('bx_acl_administration', 'single', 'delete', '_bx_acl_grid_action_delete', 'remove', 1, 1, 2),
('bx_acl_administration', 'bulk', 'delete', '_bx_acl_grid_action_delete', '', 0, 1, 1),

('bx_acl_view', 'single', 'buy', '_bx_acl_grid_action_buy', 'cart-plus', 1, 0, 1),
('bx_acl_view', 'single', 'subscribe', '_bx_acl_grid_action_subscribe', 'thumb-tack', 1, 0, 2);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_acl', '_bx_acl', 'bx_acl@modules/boonex/acl/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_acl', '', 'bx_acl@modules/boonex/acl/|std-wi.png', '_bx_acl', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
