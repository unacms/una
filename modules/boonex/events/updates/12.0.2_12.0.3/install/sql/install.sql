-- TABLES
CREATE TABLE IF NOT EXISTS `bx_events_prices` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `role_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `period` int(11) unsigned NOT NULL default '1',
  `period_unit` varchar(32) NOT NULL default '',
  `price` float unsigned NOT NULL default '1',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `type` (`profile_id`, `role_id`,`period`, `period_unit`)
);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_event' AND `name` IN ('added', 'changed', 'published');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_event', 'bx_events', 'added', '', '', 0, 'datetime', '_bx_events_form_profile_input_sys_date_added', '_bx_events_form_profile_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_event', 'bx_events', 'changed', '', '', 0, 'datetime', '_bx_events_form_profile_input_sys_date_changed', '_bx_events_form_profile_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_event', 'bx_events', 'published', '', '', 0, 'datetime', '_bx_events_form_profile_input_sys_date_published', '_bx_events_form_profile_input_date_published', '_bx_events_form_profile_input_date_published_info', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_event_add', 'bx_event_edit') AND `input_name`='published';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_event_add', 'published', 192, 1, 17),
('bx_event_edit', 'published', 192, 1, 17);

DELETE FROM  `sys_form_display_inputs` WHERE `display_name` IN ('bx_event_view', 'bx_event_view_full') AND `input_name` IN ('added', 'changed', 'published');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_event_view', 'added', 192, 1, 16),
('bx_event_view', 'changed', 192, 1, 17),
('bx_event_view', 'published', 192, 1, 18),
('bx_event_view_full', 'added', 192, 1, 16),
('bx_event_view_full', 'changed', 192, 1, 17),
('bx_event_view_full', 'published', 192, 1, 18);

DELETE FROM `sys_objects_form` WHERE `object`='bx_events_price';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_events_price', 'bx_events', '_bx_events_form_price', '', '', 'do_submit', 'bx_events_prices', 'id', '', '', '', 0, 1, 'BxEventsFormPrice', 'modules/boonex/events/classes/BxEventsFormPrice.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_events_price';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_events_price_add', 'bx_events', 'bx_events_price', '_bx_events_form_price_display_add', 0),
('bx_events_price_edit', 'bx_events', 'bx_events_price', '_bx_events_form_price_display_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_events_price';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_events_price', 'bx_events', 'id', '', '', 0, 'hidden', '_bx_events_form_price_input_sys_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_events_price', 'bx_events', 'role_id', '', '', 0, 'hidden', '_bx_events_form_price_input_sys_role_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_events_price', 'bx_events', 'name', '', '', 0, 'text', '_bx_events_form_price_input_sys_name', '_bx_events_form_price_input_name', '_bx_events_form_price_input_inf_name', 1, 0, 0, '', '', '', 'Avail', '', '_bx_events_form_price_input_err_name', 'Xss', '', 1, 0),
('bx_events_price', 'bx_events', 'period', '', '', 0, 'text', '_bx_events_form_price_input_sys_period', '_bx_events_form_price_input_period', '_bx_events_form_price_input_inf_period', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_events_price', 'bx_events', 'period_unit', '', '#!bx_events_period_units', 0, 'select', '_bx_events_form_price_input_sys_period_unit', '_bx_events_form_price_input_period_unit', '_bx_events_form_price_input_inf_period_unit', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_events_price', 'bx_events', 'price', '', '', 0, 'text', '_bx_events_form_price_input_sys_price', '_bx_events_form_price_input_price', '_bx_events_form_price_input_inf_price', 1, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_events_price', 'bx_events', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_events_price', 'bx_events', 'do_submit', '_bx_events_form_price_input_do_submit', '', 0, 'submit', '_bx_events_form_price_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_events_price', 'bx_events', 'do_cancel', '_bx_events_form_price_input_do_cancel', '', 0, 'button', '_bx_events_form_price_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_events_price_add', 'bx_events_price_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_events_price_add', 'id', 2147483647, 0, 1),
('bx_events_price_add', 'role_id', 2147483647, 1, 2),
('bx_events_price_add', 'name', 2147483647, 1, 3),
('bx_events_price_add', 'price', 2147483647, 1, 4),
('bx_events_price_add', 'period', 2147483647, 1, 5),
('bx_events_price_add', 'period_unit', 2147483647, 1, 6),
('bx_events_price_add', 'controls', 2147483647, 1, 7),
('bx_events_price_add', 'do_submit', 2147483647, 1, 8),
('bx_events_price_add', 'do_cancel', 2147483647, 1, 9),

('bx_events_price_edit', 'id', 2147483647, 1, 1),
('bx_events_price_edit', 'role_id', 2147483647, 1, 2),
('bx_events_price_edit', 'name', 2147483647, 1, 3),
('bx_events_price_edit', 'price', 2147483647, 1, 4),
('bx_events_price_edit', 'period', 2147483647, 1, 5),
('bx_events_price_edit', 'period_unit', 2147483647, 1, 6),
('bx_events_price_edit', 'controls', 2147483647, 1, 7),
('bx_events_price_edit', 'do_submit', 2147483647, 1, 8),
('bx_events_price_edit', 'do_cancel', 2147483647, 1, 9);


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_events_period_units';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_period_units', '_bx_events_pre_lists_period_units', 'bx_events', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_events_period_units';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('bx_events_period_units', '', 0, '_sys_please_select', '', ''),
('bx_events_period_units', 'day', 1, '_bx_events_period_unit_day', '', ''),
('bx_events_period_units', 'week', 2, '_bx_events_period_unit_week', '', ''),
('bx_events_period_units', 'month', 3, '_bx_events_period_unit_month', '', ''),
('bx_events_period_units', 'year', 4, '_bx_events_period_unit_year', '', '');
