-- TABLES
ALTER TABLE `bx_posts_posts` CHANGE `status` `status` enum('active','awaiting','hidden') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_posts_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_posts_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name`='published';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'published', '', '', 0, 'datetime', '_bx_posts_form_entry_input_sys_date_published', '_bx_posts_form_entry_input_date_published', '_bx_posts_form_entry_input_date_published_info', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_posts_entry_add', 'bx_posts_entry_edit', 'bx_posts_entry_view') AND `input_name`='published';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'published', 192, 1, 8),
('bx_posts_entry_edit', 'published', 192, 1, 8),
('bx_posts_entry_view', 'published', 192, 1, 4);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='3' WHERE `Name`='bx_posts';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_posts';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_posts', 'bx_posts', 'bx_posts_scores', 'bx_posts_scores_track', '604800', '0', 'bx_posts_posts', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');
