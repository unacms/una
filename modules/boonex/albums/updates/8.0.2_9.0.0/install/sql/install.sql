-- TABLES
CREATE TABLE IF NOT EXISTS `bx_albums_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_albums_reports_track` (
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


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_albums_proxy_cover';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_albums_proxy_cover', 'bx_albums_photos_resized', 'Proxy', 'a:4:{s:6:"object";s:15:"bx_albums_files";s:5:"image";s:13:"bx_albums_big";s:12:"video_poster";s:26:"bx_albums_video_poster_big";s:5:"video";a:2:{i:0;s:19:"bx_albums_video_mp4";i:1;s:20:"bx_albums_video_webm";}}', 'no', 0, 0, 0, 'BxDolTranscoderProxy', '');


-- FORMS
DELETE FROM `sys_form_displays` WHERE `object`='bx_albums' AND `display_name`='bx_albums_entry_add_images';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_albums', 'bx_albums_entry_add_images', 'bx_albums', 0, '_bx_albums_form_entry_display_add_images');

UPDATE `sys_form_inputs` SET `value`='a:2:{i:0;s:15:"bx_albums_html5";i:1;s:14:"bx_albums_crop";}', `values`='a:3:{s:16:"bx_albums_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_albums_html5";s:25:"_sys_uploader_html5_title";s:14:"bx_albums_crop";s:24:"_sys_uploader_crop_title";}' WHERE `object`='bx_albums' AND `name`='pictures';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_albums' AND `name` IN ('added', 'changed');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_albums', 'bx_albums', 'added', '', '', 0, 'datetime', '_bx_albums_form_entry_input_sys_date_added', '_bx_albums_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_albums', 'bx_albums', 'changed', '', '', 0, 'datetime', '_bx_albums_form_entry_input_sys_date_changed', '_bx_albums_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_display_inputs` SET `active`='0' WHERE `display_name`='bx_albums_entry_edit' AND `input_name` IN ('pictures');

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_albums_entry_add_images' AND `input_name` IN ('delete_confirm', 'title', 'text', 'allow_view_to', 'location', 'pictures', 'do_submit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_albums_entry_add_images', 'delete_confirm', 2147483647, 0, 1),
('bx_albums_entry_add_images', 'title', 2147483647, 0, 2),
('bx_albums_entry_add_images', 'text', 2147483647, 0, 3),
('bx_albums_entry_add_images', 'allow_view_to', 2147483647, 0, 4),
('bx_albums_entry_add_images', 'location', 2147483647, 0, 5),
('bx_albums_entry_add_images', 'pictures', 2147483647, 1, 6),
('bx_albums_entry_add_images', 'do_submit', 2147483647, 1, 7);

UPDATE `sys_form_display_inputs` SET `active`='0' WHERE `display_name`='bx_albums_entry_view' AND `input_name` IN ('text', 'title');

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_albums_entry_view' AND `input_name` IN ('added', 'changed');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_albums_entry_view', 'added', 2147483647, 1, 1),
('bx_albums_entry_view', 'changed', 2147483647, 1, 2);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldAuthor`='author' WHERE `Name`='bx_albums';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_albums';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_albums', 'bx_albums_reports', 'bx_albums_reports_track', '1', 'page.php?i=view-album&id={object_id}', 'bx_albums_albums', 'id', 'author', 'reports', '', '');