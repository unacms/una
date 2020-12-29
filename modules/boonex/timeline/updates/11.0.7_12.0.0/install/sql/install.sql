SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_mute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `mutual` tinyint(4) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);


-- STORAGES, TRANSCODERS, UPLOADERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

DELETE FROM `sys_objects_uploader` WHERE `object`='bx_timeline_record_video';
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_record_video', 1, 'BxTimelineUploaderRecordVideoAttach', 'modules/boonex/timeline/classes/BxTimelineUploaderRecordVideoAttach.php');


-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:2:{i:0;s:23:"bx_timeline_html5_video";i:1;s:24:"bx_timeline_record_video";}', `values`='a:3:{s:24:"bx_timeline_simple_video";s:26:"_sys_uploader_simple_title";s:23:"bx_timeline_html5_video";s:25:"_sys_uploader_html5_title";s:24:"bx_timeline_record_video";s:32:"_sys_uploader_record_video_title";}' WHERE `object`='bx_timeline_post' AND `name`='video';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_timeline_post_edit' AND `input_name` IN ('attachments', 'link', 'photo', 'video');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_edit', 'attachments', 2147483647, 1, 5),
('bx_timeline_post_edit', 'link', 2147483647, 1, 8),
('bx_timeline_post_edit', 'photo', 2147483647, 1, 9),
('bx_timeline_post_edit', 'video', 2147483647, 1, 10);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_attach_link' AND `name`='event_id';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_attach_link', @sName, 'event_id', '0', '', 0, 'hidden', '_bx_timeline_form_attach_link_input_sys_event_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_timeline_attach_link_add' AND `input_name`='event_id';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_attach_link_add', 'event_id', 2147483647, 1, 1);


-- REPORTS
UPDATE `sys_objects_report` SET `module`=@sName WHERE `name`=@sName;


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`=@sName LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='extensions' WHERE `page_id`=@iPageId;
