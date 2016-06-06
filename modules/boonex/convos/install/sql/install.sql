
-- TABLE: entries

CREATE TABLE IF NOT EXISTS `bx_convos_conversations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `text` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `last_reply_timestamp` int(11) NOT NULL,
  `last_reply_profile_id` int(10) unsigned NOT NULL,
  `last_reply_comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_reply_timestamp` (`last_reply_timestamp`)
);

CREATE TABLE IF NOT EXISTS `bx_convos_conv2folder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conv_id` int(10) unsigned NOT NULL,
  `folder_id` int(10) unsigned NOT NULL,
  `collaborator` int(10) unsigned NOT NULL,
  `read_comments` int(11) NOT NULL default '-1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `collaborator_folder_conv` (`collaborator`,`folder_id`, `conv_id`),
  KEY `conv_id` (`conv_id`)
);

CREATE TABLE IF NOT EXISTS `bx_convos_folders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `author` (`author`)
);

INSERT INTO `bx_convos_folders` (`id`, `author`, `name`) VALUES
(1, 0, '_bx_cnv_folder_inbox'),
(2, 0, '_bx_cnv_folder_drafts'),
(3, 0, '_bx_cnv_folder_spam'),
(4, 0, '_bx_cnv_folder_trash');

-- TABLE: storages & transcoders

CREATE TABLE IF NOT EXISTS `bx_convos_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
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

CREATE TABLE IF NOT EXISTS `bx_convos_photos_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
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

-- TABLE: comments

CREATE TABLE IF NOT EXISTS `bx_convos_cmts` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
);

-- TABLE: views

CREATE TABLE `bx_convos_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- STORAGES & TRANSCODERS

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_convos_files', 'Local', '', 360, 2592000, 3, 'bx_convos_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0),
('bx_convos_photos_resized', 'Local', '', 360, 2592000, 3, 'bx_convos_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_convos_preview', 'bx_convos_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_convos_files";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_convos_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0');

-- FORMS

INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_convos', 'bx_convos', '_bx_cnv_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_convos_conversations', 'id', '', '', 'do_submit', '', 0, 1, 'BxCnvFormEntry', 'modules/boonex/convos/classes/BxCnvFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_convos', 'bx_convos_entry_add', 'bx_convos', 0, '_bx_cnv_form_entry_display_add'),
('bx_convos', 'bx_convos_entry_delete', 'bx_convos', 0, '_bx_cnv_form_entry_display_delete'),
('bx_convos', 'bx_convos_entry_view', 'bx_convos', 1, '_bx_cnv_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_convos', 'bx_convos', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_cnv_form_entry_input_sys_delete_confirm', '_bx_cnv_form_entry_input_delete_confirm', '_bx_cnv_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_cnv_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_convos', 'bx_convos', 'do_submit', '_bx_cnv_form_entry_input_do_submit', '', 0, 'submit', '_bx_cnv_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_convos', 'bx_convos', 'submit_text', '', '', 0, 'custom', '_bx_cnv_form_entry_input_sys_submit_text', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_convos', 'bx_convos', 'submit_block', '', 'do_submit,submit_text', 0, 'input_set', '_bx_cnv_form_entry_input_sys_submit_block', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_convos', 'bx_convos', 'draft_id', '0', '', 0, 'hidden', '_bx_cnv_form_entry_input_sys_draft_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_convos', 'bx_convos', 'text', '', '', 0, 'textarea', '_bx_cnv_form_entry_input_sys_text', '_bx_cnv_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_cnv_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_convos', 'bx_convos', 'recipients', '', '', 0, 'custom', '_bx_cnv_form_entry_input_sys_recipients', '_bx_cnv_form_entry_input_recipients', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_cnv_form_entry_input_recipients_err', '', '', 1, 0),
('bx_convos', 'bx_convos', 'attachments', 'a:1:{i:0;s:9:"sys_html5";}', 'a:2:{s:10:"sys_simple";s:26:"_sys_uploader_simple_title";s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_cnv_form_entry_input_sys_attachments', '_bx_cnv_form_entry_input_attachments', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_convos_entry_add', 'delete_confirm', 2147483647, 0, 0),
('bx_convos_entry_add', 'recipients', 2147483647, 1, 1),
('bx_convos_entry_add', 'text', 2147483647, 1, 2),
('bx_convos_entry_add', 'attachments', 2147483647, 1, 3),
('bx_convos_entry_add', 'submit_block', 2147483647, 1, 4),
('bx_convos_entry_add', 'do_submit', 2147483647, 1, 5),
('bx_convos_entry_add', 'submit_text', 2147483647, 1, 6),
('bx_convos_entry_add', 'draft_id', 2147483647, 1, 7),

('bx_convos_entry_view', 'delete_confirm', 2147483647, 0, 0),
('bx_convos_entry_view', 'do_submit', 2147483647, 0, 0),
('bx_convos_entry_view', 'recipients', 2147483647, 0, 0),
('bx_convos_entry_view', 'text', 2147483647, 0, 0),
('bx_convos_entry_view', 'attachments', 2147483647, 0, 0),

('bx_convos_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_convos_entry_delete', 'do_submit', 2147483647, 1, 2),
('bx_convos_entry_delete', 'attachments', 2147483647, 0, 0),
('bx_convos_entry_delete', 'recipients', 2147483647, 0, 0),
('bx_convos_entry_delete', 'text', 2147483647, 0, 0);

-- STUDIO: page & widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_convos', '_bx_cnv', '_bx_cnv', 'bx_convos@modules/boonex/convos/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_convos', '{url_studio}module.php?name=bx_convos', '', 'bx_convos@modules/boonex/convos/|std-wi.png', '_bx_cnv', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

