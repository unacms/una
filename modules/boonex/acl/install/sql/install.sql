SET @sName = 'bx_acl';


-- TABLES
CREATE TABLE `bx_acl_level_prices` (
  `id` int(11) NOT NULL auto_increment,
  `level_id` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL DEFAULT '',
  `days` int(10) unsigned NOT NULL default '1',
  `price` float unsigned NOT NULL default '1',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `type` (`level_id`,`days`)
);


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_acl_price', @sName, '_bx_acl_form_price', '', '', 'do_submit', 'bx_acl_level_prices', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_acl_price_add', @sName, 'bx_acl_price', '_bx_acl_form_price_display_add', 0),
('bx_acl_price_edit', @sName, 'bx_acl_price', '_bx_acl_form_price_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_acl_price', @sName, 'id', '', '', 0, 'hidden', '_bx_acl_form_price_input_sys_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_acl_price', @sName, 'level_id', '', '', 0, 'hidden', '_bx_acl_form_price_input_sys_level_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_acl_price', @sName, 'days', '', '', 0, 'text', '_bx_acl_form_price_input_sys_days', '_bx_acl_form_price_input_days', '_bx_acl_form_price_input_inf_days', 1, 0, 0, '', '', '', 'Avail', '', '_bx_acl_form_price_input_err_days', 'Int', '', 0, 0),
('bx_acl_price', @sName, 'price', '', '', 0, 'text', '_bx_acl_form_price_input_sys_price', '_bx_acl_form_price_input_price', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_acl_form_price_input_err_price', 'Float', '', 0, 0),
('bx_acl_price', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_acl_price', @sName, 'do_submit', '_bx_acl_form_price_input_do_submit', '', 0, 'submit', '_bx_acl_form_price_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_acl_price', @sName, 'do_cancel', '_bx_acl_form_price_input_do_cancel', '', 0, 'button', '_bx_acl_form_price_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_acl_price_add', 'id', 2147483647, 0, 1),
('bx_acl_price_add', 'level_id', 2147483647, 1, 2),
('bx_acl_price_add', 'days', 2147483647, 1, 3),
('bx_acl_price_add', 'price', 2147483647, 1, 4),
('bx_acl_price_add', 'controls', 2147483647, 1, 5),
('bx_acl_price_add', 'do_submit', 2147483647, 1, 6),
('bx_acl_price_add', 'do_cancel', 2147483647, 1, 7),

('bx_acl_price_edit', 'id', 2147483647, 1, 1),
('bx_acl_price_edit', 'level_id', 2147483647, 1, 2),
('bx_acl_price_edit', 'days', 2147483647, 1, 3),
('bx_acl_price_edit', 'price', 2147483647, 1, 4),
('bx_acl_price_edit', 'controls', 2147483647, 1, 5),
('bx_acl_price_edit', 'do_submit', 2147483647, 1, 6),
('bx_acl_price_edit', 'do_cancel', 2147483647, 1, 7);


-- GRIDS
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_acl_administration', 'Sql', 'SELECT * FROM `bx_acl_level_prices` WHERE 1 ', 'bx_acl_level_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'days,price', '', 'like', '', '', 'BxAclGridAdministration', 'modules/boonex/acl/classes/BxAclGridAdministration.php'),
('bx_acl_view', 'Sql', 'SELECT `tlp`.*, `tl`.`Name` AS `level_name` FROM `bx_acl_level_prices` AS `tlp` LEFT JOIN `sys_acl_levels` AS `tl` ON `tlp`.`level_id`=`tl`.`ID` WHERE `tl`.`Active`=''yes'' AND `tl`.`Purchasable`=''yes'' ', 'bx_acl_level_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'days,price', 'level_name', 'like', '', '', 'BxAclGridView', 'modules/boonex/acl/classes/BxAclGridView.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_acl_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_acl_administration', 'order', '', '2%', 0, '', '', 2),
('bx_acl_administration', 'days', '_bx_acl_grid_column_days', '36%', 0, 16, '', 3),
('bx_acl_administration', 'price', '_bx_acl_grid_column_price', '40%', 0, 16, '', 4),
('bx_acl_administration', 'actions', '', '20%', 0, '', '', 5),

('bx_acl_view', 'level_name', '_bx_acl_grid_column_level_name', '40%', 1, 36, '', 1),
('bx_acl_view', 'days', '_bx_acl_grid_column_days', '20%', 0, 16, '', 2),
('bx_acl_view', 'price', '_bx_acl_grid_column_price', '20%', 0, 16, '', 3),
('bx_acl_view', 'actions', '', '20%', 0, '', '', 4);

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
