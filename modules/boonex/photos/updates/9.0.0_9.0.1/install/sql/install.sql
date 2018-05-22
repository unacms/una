-- TABLES
CREATE TABLE IF NOT EXISTS `bx_photos_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_photos_scores_track` (
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
DELETE FROM `sys_objects_form` WHERE `object`='bx_photos_upload';
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_photos_upload', 'bx_photos', '_bx_photos_form_upload', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_photos_entries', 'id', '', '', 'do_submit', '', 0, 1, 'BxPhotosFormUpload', 'modules/boonex/photos/classes/BxPhotosFormUpload.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_photos' AND `display_name`='bx_photos_entry_add';
DELETE FROM `sys_form_displays` WHERE `object`='bx_photos_upload';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_photos_upload', 'bx_photos_entry_upload', 'bx_photos', 0, '_bx_photos_form_entry_display_add');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_photos_upload';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_photos_upload', 'bx_photos', 'pictures', 'a:1:{i:0;s:15:"bx_photos_html5";}', 'a:2:{s:16:"bx_photos_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_photos_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_photos_form_entry_input_sys_attachment', '_bx_photos_form_entry_input_attachment', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_photos_upload', 'bx_photos', 'cat', '', '#!bx_photos_cats', 0, 'select', '_bx_photos_form_entry_input_sys_cat', '_bx_photos_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_photos_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_photos_upload', 'bx_photos', 'allow_view_to', '', '', 0, 'custom', '_bx_photos_form_entry_input_sys_allow_view_to', '_bx_photos_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_photos_upload', 'bx_photos', 'do_submit', '_bx_photos_form_entry_input_do_submit', '', 0, 'submit', '_bx_photos_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_photos_upload', 'bx_photos', 'profile_id', '0', '', 0, 'hidden', '_bx_photos_form_entry_input_sys_profile_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_photos_entry_add', 'bx_photos_entry_upload');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_photos_entry_upload', 'profile_id', 2147483647, 1, 1),
('bx_photos_entry_upload', 'pictures', 2147483647, 1, 2),
('bx_photos_entry_upload', 'cat', 2147483647, 1, 3),
('bx_photos_entry_upload', 'allow_view_to', 2147483647, 1, 4),
('bx_photos_entry_upload', 'do_submit', 2147483647, 1, 5);



-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='3' WHERE `Name`='bx_photos';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_photos';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_photos', 'bx_photos', 'bx_photos_scores', 'bx_photos_scores_track', '604800', '0', 'bx_photos_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');
