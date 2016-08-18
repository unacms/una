SET @sName = 'bx_forum';


-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_forum_discussions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `text` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  `status` enum('active','draft','hidden') NOT NULL DEFAULT 'active',
  `last_reply_timestamp` int(11) NOT NULL,
  `last_reply_profile_id` int(10) unsigned NOT NULL,
  `last_reply_comment_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_reply_timestamp` (`last_reply_timestamp`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_forum_files` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_photos_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_forum_cmts` (
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
CREATE TABLE `bx_forum_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_forum_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_forum_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_forum_reports_track` (
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
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_forum_files', 'Local', '', 360, 2592000, 3, 'bx_forum_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0),
('bx_forum_photos_resized', 'Local', '', 360, 2592000, 3, 'bx_forum_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_forum_preview', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_forum_files";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_forum_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0');


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
(@sName, @sName, '_bx_forum_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_forum_discussions', 'id', '', '', 'do_submit', '', 0, 1, 'BxForumFormEntry', 'modules/boonex/forum/classes/BxForumFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
(@sName, 'bx_forum_entry_add', @sName, 0, '_bx_forum_form_entry_display_add'),
(@sName, 'bx_forum_entry_delete', @sName, 0, '_bx_forum_form_entry_display_delete'),
(@sName, 'bx_forum_entry_edit', @sName, 0, '_bx_forum_form_entry_display_edit'),
(@sName, 'bx_forum_entry_view', @sName, 1, '_bx_forum_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'allow_view_to', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_allow_view_to', '_bx_forum_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
(@sName, @sName, 'delete_confirm', 1, '', 0, 'checkbox', '_bx_forum_form_entry_input_sys_delete_confirm', '_bx_forum_form_entry_input_delete_confirm', '_bx_forum_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_delete_confirm_error', '', '', 1, 0),
(@sName, @sName, 'do_submit', '_bx_forum_form_entry_input_do_submit', '', 0, 'submit', '_bx_forum_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'submit_text', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_submit_text', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
(@sName, @sName, 'submit_block', '', 'do_submit,submit_text', 0, 'input_set', '_bx_forum_form_entry_input_sys_submit_block', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
(@sName, @sName, 'draft_id', '0', '', 0, 'hidden', '_bx_forum_form_entry_input_sys_draft_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
(@sName, @sName, 'text', '', '', 0, 'textarea', '_bx_forum_form_entry_input_sys_text', '_bx_forum_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_text_err', 'XssHtml', '', 1, 0),
(@sName, @sName, 'title', '', '', 0, 'text', '_bx_forum_form_entry_input_sys_title', '_bx_forum_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_title_err', 'Xss', '', 1, 0),
(@sName, @sName, 'cat', '', '#!bx_forum_cats', 0, 'select', '_bx_forum_form_entry_input_sys_cat', '_bx_forum_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_forum_form_entry_input_cat_err', 'Xss', '', 1, 0),
(@sName, @sName, 'attachments', 'a:1:{i:0;s:9:"sys_html5";}', 'a:2:{s:10:"sys_simple";s:26:"_sys_uploader_simple_title";s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_forum_form_entry_input_sys_attachments', '_bx_forum_form_entry_input_attachments', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_entry_add', 'title', 2147483647, 1, 1),
('bx_forum_entry_add', 'cat', 2147483647, 1, 2),
('bx_forum_entry_add', 'text', 2147483647, 1, 3),
('bx_forum_entry_add', 'attachments', 2147483647, 1, 4),
('bx_forum_entry_add', 'allow_view_to', 2147483647, 1, 5),
('bx_forum_entry_add', 'submit_block', 2147483647, 1, 6),
('bx_forum_entry_add', 'do_submit', 2147483647, 1, 7),
('bx_forum_entry_add', 'submit_text', 2147483647, 1, 8),
('bx_forum_entry_add', 'draft_id', 2147483647, 1, 9),

('bx_forum_entry_edit', 'title', 2147483647, 1, 1),
('bx_forum_entry_edit', 'cat', 2147483647, 1, 2),
('bx_forum_entry_edit', 'text', 2147483647, 1, 3),
('bx_forum_entry_edit', 'attachments', 2147483647, 1, 4),
('bx_forum_entry_edit', 'allow_view_to', 2147483647, 1, 5),
('bx_forum_entry_edit', 'submit_block', 2147483647, 1, 6),
('bx_forum_entry_edit', 'do_submit', 2147483647, 1, 7),
('bx_forum_entry_edit', 'submit_text', 2147483647, 1, 8),
('bx_forum_entry_edit', 'draft_id', 2147483647, 1, 9),

('bx_forum_entry_view', 'title', 2147483647, 1, 1),
('bx_forum_entry_view', 'cat', 2147483647, 1, 2),
('bx_forum_entry_view', 'text', 2147483647, 1, 3),

('bx_forum_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_forum_entry_delete', 'do_submit', 2147483647, 1, 2);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_forum_cats', '_bx_forum_pre_lists_cats', 'bx_forum', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_forum_cats', '', 0, '_sys_please_select', ''),
('bx_forum_cats', '1', 1, '_bx_forum_cat_Animals_Pets', ''),
('bx_forum_cats', '2', 2, '_bx_forum_cat_Architecture', ''),
('bx_forum_cats', '3', 3, '_bx_forum_cat_Art', ''),
('bx_forum_cats', '4', 4, '_bx_forum_cat_Cars_Motorcycles', ''),
('bx_forum_cats', '5', 5, '_bx_forum_cat_Celebrities', ''),
('bx_forum_cats', '6', 6, '_bx_forum_cat_Design', ''),
('bx_forum_cats', '7', 7, '_bx_forum_cat_DIY_Crafts', ''),
('bx_forum_cats', '8', 8, '_bx_forum_cat_Education', ''),
('bx_forum_cats', '9', 9, '_bx_forum_cat_Film_Music_Books', ''),
('bx_forum_cats', '10', 10, '_bx_forum_cat_Food_Drink', ''),
('bx_forum_cats', '11', 11, '_bx_forum_cat_Gardening', ''),
('bx_forum_cats', '12', 12, '_bx_forum_cat_Geek', ''),
('bx_forum_cats', '13', 13, '_bx_forum_cat_Hair_Beauty', ''),
('bx_forum_cats', '14', 14, '_bx_forum_cat_Health_Fitness', ''),
('bx_forum_cats', '15', 15, '_bx_forum_cat_History', ''),
('bx_forum_cats', '16', 16, '_bx_forum_cat_Holidays_Events', ''),
('bx_forum_cats', '17', 17, '_bx_forum_cat_Home_Decor', ''),
('bx_forum_cats', '18', 18, '_bx_forum_cat_Humor', ''),
('bx_forum_cats', '19', 19, '_bx_forum_cat_Illustrations_Posters', ''),
('bx_forum_cats', '20', 20, '_bx_forum_cat_Kids_Parenting', ''),
('bx_forum_cats', '21', 21, '_bx_forum_cat_Mens_Fashion', ''),
('bx_forum_cats', '22', 22, '_bx_forum_cat_Outdoors', ''),
('bx_forum_cats', '23', 23, '_bx_forum_cat_Photography', ''),
('bx_forum_cats', '24', 24, '_bx_forum_cat_Products', ''),
('bx_forum_cats', '25', 25, '_bx_forum_cat_Quotes', ''),
('bx_forum_cats', '26', 26, '_bx_forum_cat_Science_Nature', ''),
('bx_forum_cats', '27', 27, '_bx_forum_cat_Sports', ''),
('bx_forum_cats', '28', 28, '_bx_forum_cat_Tattoos', ''),
('bx_forum_cats', '29', 29, '_bx_forum_cat_Technology', ''),
('bx_forum_cats', '30', 30, '_bx_forum_cat_Travel', ''),
('bx_forum_cats', '31', 31, '_bx_forum_cat_Weddings', ''),
('bx_forum_cats', '32', 32, '_bx_forum_cat_Womens_Fashion', '');

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_forum', '_bx_forum', 'bx_forum@modules/boonex/forum/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_forum', '', 'bx_forum@modules/boonex/forum/|std-wi.png', '_bx_forum', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
