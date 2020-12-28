-- TABLES
CREATE TABLE IF NOT EXISTS `bx_groups_cmts_notes` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

CREATE TABLE IF NOT EXISTS `bx_groups_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_groups_prices` (
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
DELETE FROM `sys_objects_form` WHERE `object`='bx_groups_price';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_price', 'bx_groups', '_bx_groups_form_price', '', '', 'do_submit', 'bx_groups_prices', 'id', '', '', '', 0, 1, '', '');

DELETE FROM `sys_form_displays` WHERE `object`='bx_groups_price';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_groups_price_add', 'bx_groups', 'bx_groups_price', '_bx_groups_form_price_display_add', 0),
('bx_groups_price_edit', 'bx_groups', 'bx_groups_price', '_bx_groups_form_price_display_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_groups_price';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_groups_price', 'bx_groups', 'id', '', '', 0, 'hidden', '_bx_groups_form_price_input_sys_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_groups_price', 'bx_groups', 'role_id', '', '', 0, 'hidden', '_bx_groups_form_price_input_sys_role_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_groups_price', 'bx_groups', 'period', '', '', 0, 'text', '_bx_groups_form_price_input_sys_period', '_bx_groups_form_price_input_period', '_bx_groups_form_price_input_inf_period', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_groups_price', 'bx_groups', 'period_unit', '', '#!bx_groups_period_units', 0, 'select', '_bx_groups_form_price_input_sys_period_unit', '_bx_groups_form_price_input_period_unit', '_bx_groups_form_price_input_inf_period_unit', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_groups_price', 'bx_groups', 'price', '', '', 0, 'text', '_bx_groups_form_price_input_sys_price', '_bx_groups_form_price_input_price', '_bx_groups_form_price_input_inf_price', 1, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_groups_price', 'bx_groups', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_groups_price', 'bx_groups', 'do_submit', '_bx_groups_form_price_input_do_submit', '', 0, 'submit', '_bx_groups_form_price_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_groups_price', 'bx_groups', 'do_cancel', '_bx_groups_form_price_input_do_cancel', '', 0, 'button', '_bx_groups_form_price_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_groups_price_add', 'bx_groups_price_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_groups_price_add', 'id', 2147483647, 0, 1),
('bx_groups_price_add', 'role_id', 2147483647, 1, 2),
('bx_groups_price_add', 'price', 2147483647, 1, 3),
('bx_groups_price_add', 'period', 2147483647, 1, 4),
('bx_groups_price_add', 'period_unit', 2147483647, 1, 5),
('bx_groups_price_add', 'controls', 2147483647, 1, 6),
('bx_groups_price_add', 'do_submit', 2147483647, 1, 7),
('bx_groups_price_add', 'do_cancel', 2147483647, 1, 8),

('bx_groups_price_edit', 'id', 2147483647, 1, 1),
('bx_groups_price_edit', 'role_id', 2147483647, 1, 2),
('bx_groups_price_edit', 'price', 2147483647, 1, 3),
('bx_groups_price_edit', 'period', 2147483647, 1, 4),
('bx_groups_price_edit', 'period_unit', 2147483647, 1, 5),
('bx_groups_price_edit', 'controls', 2147483647, 1, 6),
('bx_groups_price_edit', 'do_submit', 2147483647, 1, 7),
('bx_groups_price_edit', 'do_cancel', 2147483647, 1, 8);


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_groups_roles';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_groups_roles', '_bx_groups_pre_lists_roles', 'bx_groups', '1');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_groups_roles';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('bx_groups_roles', '0', 1, '_bx_groups_role_regular', '', ''),
('bx_groups_roles', '1', 2, '_bx_groups_role_administrator', '', ''),
('bx_groups_roles', '2', 3, '_bx_groups_role_moderator', '', '');

DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_groups_period_units';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_groups_period_units', '_bx_groups_pre_lists_period_units', 'bx_groups', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_groups_period_units';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('bx_groups_period_units', '', 0, '_sys_please_select', '', ''),
('bx_groups_period_units', 'day', 1, '_bx_groups_period_unit_day', '', ''),
('bx_groups_period_units', 'week', 2, '_bx_groups_period_unit_week', '', ''),
('bx_groups_period_units', 'month', 3, '_bx_groups_period_unit_month', '', ''),
('bx_groups_period_units', 'year', 4, '_bx_groups_period_unit_year', '', '');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_groups_notes';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_groups_notes', 'bx_groups', 'bx_groups_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', '', '', 'bx_groups_data', 'id', 'author', 'group_name', 'comments', '', '');


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_groups', `object_comment`='bx_groups_notes' WHERE `name`='bx_groups';


-- FAVORITES
UPDATE `sys_objects_favorite` SET `table_lists`='bx_groups_favorites_lists' WHERE `name`='bx_groups';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_groups' WHERE `name`='bx_groups';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_groups' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
