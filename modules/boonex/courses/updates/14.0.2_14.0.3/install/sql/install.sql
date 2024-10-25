-- TABLES
CREATE TABLE IF NOT EXISTS `bx_courses_content_structure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `node_id` int(11) NOT NULL DEFAULT '0',
  `level` tinyint(4) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `cn_l2` int(11) NOT NULL DEFAULT '0',
  `cn_l3` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `node_id` (`node_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_nodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `passing` tinyint(4) NOT NULL DEFAULT '0',
  `counters` text NOT NULL,
  `added` int(11) NOT NULL,
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_nodes2users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pass` (`node_id`, `profile_id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `node_id` int(11) NOT NULL DEFAULT '0',
  `content_type` varchar(32) NOT NULL DEFAULT '',
  `content_id` int(11) NOT NULL DEFAULT '0',
  `usage` tinyint(4) NOT NULL DEFAULT '0',
  `added` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `bx_courses_content_data2users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pass` (`data_id`, `profile_id`)
);


-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_courses_content_node';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_courses_content_node', 'bx_courses', '_bx_courses_form_content_node', '', '', 'do_submit', 'bx_courses_content_nodes', 'id', '', '', '', 0, 1, 'BxCoursesFormContentNode', 'modules/boonex/courses/classes/BxCoursesFormContentNode.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_courses_content_node';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_courses_content_node_add', 'bx_courses', 'bx_courses_content_node', '_bx_courses_form_content_node_display_add', 0),
('bx_courses_content_node_edit', 'bx_courses', 'bx_courses_content_node', '_bx_courses_form_content_node_display_edit', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_courses_content_node';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_courses_content_node', 'bx_courses', 'title', '', '', 0, 'text', '_bx_courses_form_content_node_input_sys_title', '_bx_courses_form_content_node_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_courses_form_content_node_input_title_err', 'Xss', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'text', '', '', 0, 'textarea', '_bx_courses_form_content_node_input_sys_text', '_bx_courses_form_content_node_input_text', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'passing', '', '', 0, 'select', '_bx_courses_form_content_node_input_sys_passing', '_bx_courses_form_content_node_input_passing', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'do_submit', '_bx_courses_form_content_node_input_do_submit', '', 0, 'submit', '_bx_courses_form_content_node_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_courses_content_node', 'bx_courses', 'do_cancel', '_bx_courses_form_content_node_input_do_cancel', '', 0, 'button', '_bx_courses_form_content_node_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_courses_content_node_add', 'bx_courses_content_node_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_courses_content_node_add', 'id', 2147483647, 0, 1),
('bx_courses_content_node_add', 'title', 2147483647, 1, 2),
('bx_courses_content_node_add', 'text', 2147483647, 1, 3),
('bx_courses_content_node_add', 'passing', 2147483647, 1, 4),
('bx_courses_content_node_add', 'controls', 2147483647, 1, 5),
('bx_courses_content_node_add', 'do_submit', 2147483647, 1, 6),
('bx_courses_content_node_add', 'do_cancel', 2147483647, 1, 7),

('bx_courses_content_node_edit', 'id', 2147483647, 1, 1),
('bx_courses_content_node_edit', 'title', 2147483647, 1, 2),
('bx_courses_content_node_edit', 'text', 2147483647, 1, 3),
('bx_courses_content_node_edit', 'passing', 2147483647, 1, 4),
('bx_courses_content_node_edit', 'controls', 2147483647, 1, 5),
('bx_courses_content_node_edit', 'do_submit', 2147483647, 1, 6),
('bx_courses_content_node_edit', 'do_cancel', 2147483647, 1, 7);
