SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_files2events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL DEFAULT '0',
  `media_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `media` (`event_id`, `media_id`)
);


-- STORAGES, TRANSCODERS, UPLOADERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_timeline_simple_file', 'bx_timeline_html5_file');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_simple_file', 1, 'BxTimelineUploaderSimpleAttach', 'modules/boonex/timeline/classes/BxTimelineUploaderSimpleAttach.php'),
('bx_timeline_html5_file', 1, 'BxTimelineUploaderHTML5Attach', 'modules/boonex/timeline/classes/BxTimelineUploaderHTML5Attach.php');

DELETE FROM `sys_objects_storage` WHERE `object`='bx_timeline_files';
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_timeline_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_timeline_files', 'deny-allow', '', 'jpg,jpeg,jpe,gif,png,action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0);


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_post' AND `name`='file';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `help`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_post', @sName, 'file', 'a:1:{i:0;s:22:"bx_timeline_html5_file";}', 'a:2:{s:23:"bx_timeline_simple_file";s:26:"_sys_uploader_simple_title";s:22:"bx_timeline_html5_file";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_timeline_form_post_input_sys_files', '_bx_timeline_form_post_input_files', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_post_add', 'bx_timeline_post_add_public', 'bx_timeline_post_add_profile', 'bx_timeline_post_edit') AND `input_name`='file';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_add', 'file', 2147483647, 1, 11),
('bx_timeline_post_add_public', 'file', 2147483647, 1, 11),
('bx_timeline_post_add_profile', 'file', 2147483647, 1, 11),
('bx_timeline_post_edit', 'file', 2147483647, 1, 11);

DELETE FROM `sys_objects_form` WHERE `object`='bx_timeline_repost_to';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_repost_to', @sName, '_bx_timeline_form_repost_to', '', '', 'do_submit', '', '', '', '', '', 0, 1, 'BxTimelineFormRepostTo', 'modules/boonex/timeline/classes/BxTimelineFormRepostTo.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_timeline_repost_to';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_timeline_repost_to_browse', @sName, 'bx_timeline_repost_to', '_bx_timeline_form_repost_to_display_browse', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_repost_to';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `help`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_repost_to', @sName, 'reposter_id', '', '', 0, 'hidden', '_bx_timeline_form_repost_to_input_sys_reposter_id', '', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_timeline_repost_to', @sName, 'type', '', '', 0, 'hidden', '_bx_timeline_form_repost_to_input_sys_type', '', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_timeline_repost_to', @sName, 'action', '', '', 0, 'hidden', '_bx_timeline_form_repost_to_input_sys_action', '', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_timeline_repost_to', @sName, 'object_id', '', '', 0, 'hidden', '_bx_timeline_form_repost_to_input_sys_object_id', '', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_timeline_repost_to', @sName, 'search', '', '', 0, 'custom', '_bx_timeline_form_repost_to_input_sys_search', '_bx_timeline_form_repost_to_input_search', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost_to', @sName, 'list', '', '', 0, 'custom', '_bx_timeline_form_repost_to_input_sys_list', '_bx_timeline_form_repost_to_input_list', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost_to', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost_to', @sName, 'do_submit', '_bx_timeline_form_repost_to_input_do_submit', '', 0, 'submit', '_bx_timeline_form_repost_to_input_sys_do_submit', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_repost_to', @sName, 'do_cancel', '_bx_timeline_form_repost_to_input_do_cancel', '', 0, 'button', '_bx_timeline_form_repost_to_input_sys_do_cancel', '', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_timeline_repost_to_browse';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_repost_to_browse', 'reposter_id', 2147483647, 1, 1),
('bx_timeline_repost_to_browse', 'type', 2147483647, 1, 2),
('bx_timeline_repost_to_browse', 'action', 2147483647, 1, 3),
('bx_timeline_repost_to_browse', 'object_id', 2147483647, 1, 4),
('bx_timeline_repost_to_browse', 'search', 2147483647, 1, 5),
('bx_timeline_repost_to_browse', 'list', 2147483647, 1, 6),
('bx_timeline_repost_to_browse', 'controls', 2147483647, 1, 7),
('bx_timeline_repost_to_browse', 'do_submit', 2147483647, 1, 8),
('bx_timeline_repost_to_browse', 'do_cancel', 2147483647, 1, 9);