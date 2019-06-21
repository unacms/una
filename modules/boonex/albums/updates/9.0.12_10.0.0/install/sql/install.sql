
-- TABLES
ALTER TABLE `bx_albums_albums` CHANGE `allow_view_to` `allow_view_to` VARCHAR(16) NOT NULL DEFAULT '3';
ALTER TABLE `bx_albums_albums` CHANGE `status` `status` ENUM('active','awaiting','failed','hidden') NOT NULL DEFAULT 'active';

CREATE TABLE IF NOT EXISTS `bx_albums_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_albums_reactions_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_albums_video_webm', 'bx_albums_video_mp4_hd');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_albums_video_mp4_hd', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', '');

UPDATE `sys_objects_transcoder` SET `source_params`='a:4:{s:6:"object";s:15:"bx_albums_files";s:5:"image";s:17:"bx_albums_preview";s:12:"video_poster";s:30:"bx_albums_video_poster_preview";s:5:"video";a:4:{i:0;s:19:"bx_albums_video_mp4";i:1;s:22:"bx_albums_video_mp4_hd";i:2;s:29:"bx_albums_video_poster_browse";i:3;s:26:"bx_albums_video_poster_big";}}' WHERE `object`='bx_albums_proxy_preview';
UPDATE `sys_objects_transcoder` SET `source_params`='a:4:{s:6:"object";s:15:"bx_albums_files";s:5:"image";s:16:"bx_albums_browse";s:12:"video_poster";s:29:"bx_albums_video_poster_browse";s:5:"video";a:2:{i:0;s:19:"bx_albums_video_mp4";i:1;s:22:"bx_albums_video_mp4_hd";}}' WHERE `object`='bx_albums_proxy_browse';
UPDATE `sys_objects_transcoder` SET `source_params`='a:4:{s:6:"object";s:15:"bx_albums_files";s:5:"image";s:13:"bx_albums_big";s:12:"video_poster";s:26:"bx_albums_video_poster_big";s:5:"video";a:2:{i:0;s:19:"bx_albums_video_mp4";i:1;s:22:"bx_albums_video_mp4_hd";}}' WHERE `object`='bx_albums_proxy_cover';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_albums_video_webm', 'bx_albums_video_mp4_hd');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_albums_video_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_albums' AND `name`='labels';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_albums', 'bx_albums', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_display_inputs` SET `active`='1' WHERE `display_name`='bx_albums_entry_edit' AND `input_name`='pictures';

DELETE FROM `sys_objects_form` WHERE `object`='bx_albums_media';
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_media', 'bx_albums', '_bx_albums_form_media', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_albums_files2albums', 'id', '', '', 'do_submit', '', 0, 1, '', '');

DELETE FROM `sys_form_displays` WHERE `object`='bx_albums_media';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_albums_media', 'bx_albums_media_edit', 'bx_albums', 0, '_bx_albums_form_media_display_edit');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_albums_media';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_albums_media', 'bx_albums', 'title', '', '', 0, 'text', '_bx_albums_form_media_input_sys_title', '_bx_albums_form_media_input_title', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_albums_media', 'bx_albums', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_albums_media', 'bx_albums', 'do_submit', '_bx_albums_form_media_input_do_submit', '', 0, 'submit', '_bx_albums_form_media_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_albums_media', 'bx_albums', 'do_cancel', '_bx_albums_form_media_input_do_cancel', '', 0, 'button', '_bx_albums_form_media_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_albums_media_edit';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_albums_media_edit', 'title', 2147483647, 1, 1),
('bx_albums_media_edit', 'controls', 2147483647, 1, 2),
('bx_albums_media_edit', 'do_submit', 2147483647, 1, 3),
('bx_albums_media_edit', 'do_cancel', 2147483647, 1, 4);


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_albums_reactions';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_albums_reactions', 'bx_albums_reactions', 'bx_albums_reactions_track', '604800', '1', '1', '1', '1', 'bx_albums_albums', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');
