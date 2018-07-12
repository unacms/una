-- TABLES
CREATE TABLE IF NOT EXISTS `bx_posts_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);


-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

DELETE FROM `sys_objects_storage` WHERE `object`='bx_posts_photos';
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_posts_photos', @sStorageEngine, '', 360, 2592000, 3, 'bx_posts_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_posts_preview_photos', 'bx_posts_gallery_photos');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_posts_preview_photos', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_photos";}', 'no', '1', '2592000', '0'),
('bx_posts_gallery_photos', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_photos";}', 'no', '1', '2592000', '0');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_posts_preview_photos', 'bx_posts_gallery_photos') AND `filter`='Resize';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_posts_preview_photos', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_posts_gallery_photos', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name`='covers';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'covers', 'a:1:{i:0;s:14:"bx_posts_html5";}', 'a:2:{s:15:"bx_posts_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_posts_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_posts_form_entry_input_sys_covers', '_bx_posts_form_entry_input_covers', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_add' AND `input_name` IN ('pictures', 'covers');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'covers', 2147483647, 1, 5);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_edit' AND `input_name` IN ('pictures', 'covers');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_edit', 'covers', 2147483647, 1, 6);
