-- TABLES
CREATE TABLE IF NOT EXISTS `bx_posts_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_posts_reports_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `text` text NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `report` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;


-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:9:"sys_html5";}', `values`='a:2:{s:10:"sys_simple";s:26:"_sys_uploader_simple_title";s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_posts' AND `name`='pictures';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name` IN ('added', 'changed');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'added', '', '', 0, 'datetime', '_bx_posts_form_entry_input_sys_date_added', '_bx_posts_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'changed', '', '', 0, 'datetime', '_bx_posts_form_entry_input_sys_date_changed', '_bx_posts_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_display_inputs` SET `active`='1', `order`='1' WHERE `display_name`='bx_posts_entry_view' AND `input_name`='cat';
UPDATE `sys_form_display_inputs` SET `active`='0', `order`='0' WHERE `display_name`='bx_posts_entry_view' AND `input_name`='text';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_view' AND `input_name` IN ('added', 'changed');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_view', 'added', 2147483647, 1, 2),
('bx_posts_entry_view', 'changed', 2147483647, 1, 3);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldAuthor`='author' WHERE `Name`='bx_posts';


-- VOTES
UPDATE `sys_objects_vote` SET `TriggerFieldAuthor`='author' WHERE `Name`='bx_posts';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_posts';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_posts', 'bx_posts_reports', 'bx_posts_reports_track', '1', 'page.php?i=view-post&id={object_id}', 'bx_posts_posts', 'id', 'author', 'reports', '', '');
