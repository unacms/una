SET @sName = 'bx_forum';
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_forum_discussions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `text` text NOT NULL,
  `text_comments` text NOT NULL,
  `lr_timestamp` int(11) NOT NULL,
  `lr_profile_id` int(10) unsigned NOT NULL,
  `lr_comment_id` int(11) NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `stick` tinyint(4) NOT NULL DEFAULT '0',
  `lock` tinyint(4) NOT NULL DEFAULT '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  `status` enum('active','draft','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`,`text_comments`),
  KEY `lr_timestamp` (`lr_timestamp`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_categories` (
  `category` int(11) NOT NULL default '0',
  `visible_for_levels` int(11) NOT NULL default '2147483647',
  PRIMARY KEY (`category`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_forum_files` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_photos_resized` (
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

-- TABLE: subscribers
CREATE TABLE IF NOT EXISTS `bx_forum_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

-- TABLE: views
CREATE TABLE `bx_forum_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: votes
CREATE TABLE IF NOT EXISTS `bx_forum_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_forum_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

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

-- TABLE: favorites
CREATE TABLE `bx_forum_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- STORAGES & TRANSCODERS
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_forum_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0),
('bx_forum_files_cmts', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0),
('bx_forum_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_preview', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_forum_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_forum_preview_cmts', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_forum_files_cmts";}', 'no', '1', '2592000', '0', '', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_forum_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_forum_preview_cmts', 'Resize', 'a:4:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
(@sName, @sName, '_bx_forum_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_forum_discussions', 'id', '', '', 'do_submit', '', 0, 1, 'BxForumFormEntry', 'modules/boonex/forum/classes/BxForumFormEntry.php'),
('bx_forum_search', @sName, '_bx_forum_form_search', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_forum_discussions', 'id', '', '', 'do_submit', '', 0, 1, 'BxForumFormSearch', 'modules/boonex/forum/classes/BxForumFormSearch.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
(@sName, 'bx_forum_entry_add', @sName, 0, '_bx_forum_form_entry_display_add'),
(@sName, 'bx_forum_entry_delete', @sName, 0, '_bx_forum_form_entry_display_delete'),
(@sName, 'bx_forum_entry_edit', @sName, 0, '_bx_forum_form_entry_display_edit'),
(@sName, 'bx_forum_entry_view', @sName, 1, '_bx_forum_form_entry_display_view'),

('bx_forum_search', 'bx_forum_search_full', @sName, 0, '_bx_forum_form_search_display_full');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'allow_view_to', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_allow_view_to', '_bx_forum_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'delete_confirm', 1, '', 0, 'checkbox', '_bx_forum_form_entry_input_sys_delete_confirm', '_bx_forum_form_entry_input_delete_confirm', '_bx_forum_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_delete_confirm_error', '', '', 1, 0),
(@sName, @sName, 'do_submit', '_bx_forum_form_entry_input_do_submit', '', 0, 'submit', '_bx_forum_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'text', '', '', 0, 'textarea', '_bx_forum_form_entry_input_sys_text', '_bx_forum_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_text_err', 'XssHtml', '', 1, 0),
(@sName, @sName, 'title', '', '', 0, 'text', '_bx_forum_form_entry_input_sys_title', '_bx_forum_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_title_err', 'Xss', '', 1, 0),
(@sName, @sName, 'cat', '', '#!bx_forum_cats', 0, 'select', '_bx_forum_form_entry_input_sys_cat', '_bx_forum_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_forum_form_entry_input_cat_err', 'Xss', '', 1, 0),
(@sName, @sName, 'attachments', 'a:1:{i:0;s:14:"bx_forum_html5";}', 'a:2:{s:15:"bx_forum_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_forum_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_forum_form_entry_input_sys_attachments', '_bx_forum_form_entry_input_attachments', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),

('bx_forum_search', @sName, 'author', '', '', 0, 'custom', '_bx_forum_form_search_input_sys_author', '_bx_forum_form_search_input_author', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_forum_search', @sName, 'category', '', '#!bx_forum_cats', 0, 'select', '_bx_forum_form_search_input_sys_category', '_bx_forum_form_search_input_category', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_forum_search', @sName, 'keyword', '', '', 0, 'text', '_bx_forum_form_search_input_sys_keyword', '_bx_forum_form_search_input_keyword', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_forum_search', @sName, 'do_submit', '_bx_forum_form_search_input_do_submit', '', 0, 'submit', '_bx_forum_form_search_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_entry_add', 'title', 2147483647, 1, 1),
('bx_forum_entry_add', 'cat', 2147483647, 1, 2),
('bx_forum_entry_add', 'text', 2147483647, 1, 3),
('bx_forum_entry_add', 'attachments', 2147483647, 1, 4),
('bx_forum_entry_add', 'allow_view_to', 2147483647, 1, 5),
('bx_forum_entry_add', 'do_submit', 2147483647, 1, 6),

('bx_forum_entry_edit', 'title', 2147483647, 1, 1),
('bx_forum_entry_edit', 'cat', 2147483647, 1, 2),
('bx_forum_entry_edit', 'text', 2147483647, 1, 3),
('bx_forum_entry_edit', 'attachments', 2147483647, 1, 4),
('bx_forum_entry_edit', 'allow_view_to', 2147483647, 1, 5),
('bx_forum_entry_edit', 'do_submit', 2147483647, 1, 6),

('bx_forum_entry_view', 'title', 2147483647, 1, 1),
('bx_forum_entry_view', 'cat', 2147483647, 1, 2),
('bx_forum_entry_view', 'text', 2147483647, 1, 3),

('bx_forum_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_forum_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_forum_search_full', 'author', 2147483647, 1, 1),
('bx_forum_search_full', 'category', 2147483647, 1, 2),
('bx_forum_search_full', 'keyword', 2147483647, 1, 3),
('bx_forum_search_full', 'do_submit', 2147483647, 1, 4);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_forum_cats', '_bx_forum_pre_lists_cats', 'bx_forum', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_forum_cats', '', 0, '_sys_please_select', ''),
('bx_forum_cats', '1', 1, '_bx_forum_cat_General', '');


-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
(@sName, '_bx_forum', @sName, 'added', 'edited', 'deleted', '', ''),
('bx_forum_cmts', '_bx_forum_cmts', @sName, 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
(@sName, @sName, 'id', '', 'a:1:{s:4:"sort";a:2:{s:5:"stick";s:4:"desc";s:12:"lr_timestamp";s:4:"desc";}}'),
(@sName, 'bx_forum_administration', 'id', '', ''),
(@sName, 'bx_forum_common', 'id', '', '');


-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_forum', 'bx_forum', 'bx_forum', '_bx_forum_search_extended', 1, '', ''),
('bx_forum_cmts', 'bx_forum_cmts', 'bx_forum', '_bx_forum_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_forum', '_bx_forum', 'bx_forum@modules/boonex/forum/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_forum', '', 'bx_forum@modules/boonex/forum/|std-icon.svg', '_bx_forum', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
