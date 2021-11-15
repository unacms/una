-- TABLES
ALTER TABLE `bx_posts_posts` MODIFY `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active';


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_posts_view_photos';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_view_photos', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_photos";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_posts_view_photos';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_posts_view_photos', 'Resize',  'a:2:{s:1:"w";s:4:"2000";s:1:"h";s:4:"2000";}', '0');


-- FORMS
UPDATE `sys_form_inputs` SET `name`='allow_comments', `caption_system`='_bx_posts_form_entry_input_sys_allow_comments', `caption`='_bx_posts_form_entry_input_allow_comments' WHERE `object`='bx_posts' AND `name`='disable_comments';

UPDATE `sys_form_display_inputs` SET `input_name`='allow_comments' WHERE `display_name` IN ('bx_posts_entry_add', 'bx_posts_entry_edit') AND `input_name`='disable_comments';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldComments`='' WHERE `Name`='bx_posts_notes';