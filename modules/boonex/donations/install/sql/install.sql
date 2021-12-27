SET @sName = 'bx_donations';


-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_donations_entries` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `period` int(11) unsigned NOT NULL default '0',
  `period_unit` varchar(32) NOT NULL default '',
  `amount` float unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_donations_entries_deleted` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `type_id` int(11) NOT NULL default '0',
  `period` int(11) unsigned NOT NULL default '0',
  `period_unit` varchar(32) NOT NULL default '',
  `amount` float unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `reason` varchar(16) NOT NULL default '',
  `deleted` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
);

-- TABLES: types
CREATE TABLE IF NOT EXISTS `bx_donations_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL default '',
  `title` varchar(128) NOT NULL default '',
  `period` int(11) unsigned NOT NULL default '0',
  `period_unit` varchar(32) NOT NULL default '',
  `amount` float unsigned NOT NULL default '0',
  `custom` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_donations_type', @sName, '_bx_donations_form_type', '', '', 'do_submit', 'bx_donations_types', 'id', '', '', '', 0, 1, 'BxDonationsFormType', 'modules/boonex/donations/classes/BxDonationsFormType.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_donations_type_add', @sName, 'bx_donations_type', '_bx_donations_form_type_display_add', 0),
('bx_donations_type_edit', @sName, 'bx_donations_type', '_bx_donations_form_type_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_donations_type', @sName, 'name', '', '', 0, 'text', '_bx_donations_form_type_input_sys_name', '_bx_donations_form_type_input_name', '_bx_donations_form_type_input_inf_name', 1, 0, 0, '', '', '', 'Avail', '', '_bx_donations_form_type_input_err_name', 'Xss', '', 1, 0),
('bx_donations_type', @sName, 'title', '', '', 0, 'text_translatable', '_bx_donations_form_type_input_sys_title', '_bx_donations_form_type_input_title', '', 1, 0, 0, '', '', '', 'AvailTranslatable', 'a:1:{i:0;s:5:"title";}', '_bx_donations_form_type_input_err_title', 'Xss', '', 1, 0),
('bx_donations_type', @sName, 'period', '', '', 0, 'text', '_bx_donations_form_type_input_sys_period', '_bx_donations_form_type_input_period', '_bx_donations_form_type_input_inf_period', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_donations_type', @sName, 'period_unit', '', '#!bx_donations_period_units', 0, 'select', '_bx_donations_form_type_input_sys_period_unit', '_bx_donations_form_type_input_period_unit', '_bx_donations_form_type_input_inf_period_unit', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_donations_type', @sName, 'amount', '', '', 0, 'price', '_bx_donations_form_type_input_sys_amount', '_bx_donations_form_type_input_amount', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_donations_form_type_input_err_amount', 'Float', '', 1, 0),
('bx_donations_type', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_donations_type', @sName, 'do_submit', '_bx_donations_form_type_input_do_submit', '', 0, 'submit', '_bx_donations_form_type_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_donations_type', @sName, 'do_cancel', '_bx_donations_form_type_input_do_cancel', '', 0, 'button', '_bx_donations_form_type_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_donations_type_add', 'name', 2147483647, 1, 1),
('bx_donations_type_add', 'title', 2147483647, 1, 2),
('bx_donations_type_add', 'amount', 2147483647, 1, 3),
('bx_donations_type_add', 'period', 2147483647, 1, 4),
('bx_donations_type_add', 'period_unit', 2147483647, 1, 5),
('bx_donations_type_add', 'controls', 2147483647, 1, 6),
('bx_donations_type_add', 'do_submit', 2147483647, 1, 7),
('bx_donations_type_add', 'do_cancel', 2147483647, 1, 8),

('bx_donations_type_edit', 'name', 2147483647, 1, 1),
('bx_donations_type_edit', 'title', 2147483647, 1, 2),
('bx_donations_type_edit', 'amount', 2147483647, 1, 3),
('bx_donations_type_edit', 'period', 2147483647, 1, 4),
('bx_donations_type_edit', 'period_unit', 2147483647, 1, 5),
('bx_donations_type_edit', 'controls', 2147483647, 1, 6),
('bx_donations_type_edit', 'do_submit', 2147483647, 1, 7),
('bx_donations_type_edit', 'do_cancel', 2147483647, 1, 8);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_donations_period_units', '_bx_donations_pre_lists_period_units', @sName, '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_donations_period_units', '', 0, '_sys_please_select', ''),
('bx_donations_period_units', 'day', 1, '_bx_donations_pre_values_day', ''),
('bx_donations_period_units', 'week', 2, '_bx_donations_pre_values_week', ''),
('bx_donations_period_units', 'month', 3, '_bx_donations_pre_values_month', ''),
('bx_donations_period_units', 'year', 4, '_bx_donations_pre_values_year', '');


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_donations', '_bx_donations', 'bx_donations@modules/boonex/donations/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_donations', '', 'bx_donations@modules/boonex/donations/|std-icon.svg', '_bx_donations', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
