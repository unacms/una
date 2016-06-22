
-- TABLE: PROFILES
CREATE TABLE IF NOT EXISTS `bx_groups_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  `cover` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `group_cat` int(11) NOT NULL,
  `group_desc` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `join_confirmation` tinyint(4) NOT NULL DEFAULT '1',
  `allow_view_to` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `group_name` (`group_name`)
);

-- TABLE: STORAGES & TRANSCODERS
CREATE TABLE `bx_groups_pics` (
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

CREATE TABLE `bx_groups_pics_resized` (
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

-- TABLE: VIEWS
CREATE TABLE `bx_groups_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: VOTES
CREATE TABLE IF NOT EXISTS `bx_groups_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- TABLE: REPORTS
CREATE TABLE IF NOT EXISTS `bx_groups_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_groups_reports_track` (
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

-- TABLE: metas
CREATE TABLE `bx_groups_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: fans
CREATE TABLE IF NOT EXISTS `bx_groups_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `mutual` tinyint(4) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: admins
CREATE TABLE IF NOT EXISTS `bx_groups_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_profile_id` int(10) unsigned NOT NULL,
  `fan_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin` (`group_profile_id`,`fan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- STORAGES & TRANSCODERS

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_groups_pics', 'Local', '', 360, 2592000, 3, 'bx_groups_pics', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_groups_pics_resized', 'Local', '', 360, 2592000, 3, 'bx_groups_pics_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_groups_icon', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0'),
('bx_groups_thumb', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0'),
('bx_groups_avatar', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0'),
('bx_groups_picture', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0'),
('bx_groups_cover', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0'),
('bx_groups_cover_thumb', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_groups_icon', 'Resize', 'a:3:{s:1:"w";s:2:"32";s:1:"h";s:2:"32";s:13:"square_resize";s:1:"1";}', '0'),
('bx_groups_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_groups_avatar', 'Resize', 'a:3:{s:1:"w";s:2:"96";s:1:"h";s:2:"96";s:13:"square_resize";s:1:"1";}', '0'),
('bx_groups_picture', 'Resize', 'a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}', '0'),
('bx_groups_cover', 'Resize', 'a:1:{s:1:"w";s:4:"1024";}', '0'),
('bx_groups_cover_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0');

-- FORMS

INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_group', 'bx_groups', '_bx_groups_form_profile', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_groups_data', 'id', '', '', 'do_submit', '', 0, 1, 'BxGroupsFormEntry', 'modules/boonex/groups/classes/BxGroupsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_group', 'bx_group_add', 'bx_groups', 0, '_bx_groups_form_profile_display_add'),
('bx_group', 'bx_group_delete', 'bx_groups', 0, '_bx_groups_form_profile_display_delete'),
('bx_group', 'bx_group_edit', 'bx_groups', 0, '_bx_groups_form_profile_display_edit'),
('bx_group', 'bx_group_edit_cover', 'bx_groups', 0, '_bx_groups_form_profile_display_edit_cover'),
('bx_group', 'bx_group_view', 'bx_groups', 1, '_bx_groups_form_profile_display_view'),
('bx_group', 'bx_group_view_full', 'bx_groups', 1, '_bx_groups_form_profile_display_view_full');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_group', 'bx_groups', 'allow_view_to', 3, '', 0, 'custom', '_bx_groups_form_profile_input_sys_allow_view_to', '_bx_groups_form_profile_input_allow_view_to', '_bx_groups_form_profile_input_allow_view_to_desc', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_group', 'bx_groups', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_groups_form_profile_input_sys_delete_confirm', '_bx_groups_form_profile_input_delete_confirm', '_bx_groups_form_profile_input_delete_confirm_info', 1, 0, 0, '', '', '', 'avail', '', '_bx_groups_form_profile_input_delete_confirm_error', '', '', 1, 0),
('bx_group', 'bx_groups', 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_bx_groups_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_group', 'bx_groups', 'group_desc', '', '', 0, 'textarea', '_bx_groups_form_profile_input_sys_group_desc', '_bx_groups_form_profile_input_group_desc', '', 0, 0, 0, '', '', '', '', '', '', 'XssMultiline', '', 1, 1),
('bx_group', 'bx_groups', 'group_cat', '', '#!bx_groups_cats', 0, 'select', '_bx_groups_form_profile_input_sys_group_cat', '_bx_groups_form_profile_input_group_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_groups_form_profile_input_group_cat_err', 'Xss', '', 1, 1),
('bx_group', 'bx_groups', 'group_name', '', '', 0, 'text', '_bx_groups_form_profile_input_sys_group_name', '_bx_groups_form_profile_input_group_name', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_groups_form_profile_input_group_name_err', 'Xss', '', 1, 0),
('bx_group', 'bx_groups', 'initial_members', '', '', 0, 'custom', '_bx_groups_form_profile_input_sys_initial_members', '_bx_groups_form_profile_input_initial_members', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_group', 'bx_groups', 'join_confirmation', 1, '', 1, 'switcher', '_bx_groups_form_profile_input_sys_join_confirm', '_bx_groups_form_profile_input_join_confirm', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_group', 'bx_groups', 'cover', 'a:1:{i:0;s:20:"bx_groups_cover_crop";}', 'a:1:{s:20:"bx_groups_cover_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_groups_form_profile_input_sys_cover', '_bx_groups_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_group', 'bx_groups', 'picture', 'a:1:{i:0;s:22:"bx_groups_picture_crop";}', 'a:1:{s:22:"bx_groups_picture_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_groups_form_profile_input_sys_picture', '_bx_groups_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_groups_form_profile_input_picture_err', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_group_add', 'delete_confirm', 2147483647, 0, 3),
('bx_group_add', 'cover', 2147483647, 0, 4),
('bx_group_add', 'initial_members', 2147483647, 1, 5),
('bx_group_add', 'picture', 2147483647, 1, 6),
('bx_group_add', 'group_name', 2147483647, 1, 7),
('bx_group_add', 'group_cat', 2147483647, 1, 8),
('bx_group_add', 'group_desc', 2147483647, 1, 9),
('bx_group_add', 'join_confirmation', 2147483647, 1, 10),
('bx_group_add', 'allow_view_to', 2147483647, 1, 11),
('bx_group_add', 'do_submit', 2147483647, 1, 12),

('bx_group_delete', 'delete_confirm', 2147483647, 1, 0),
('bx_group_delete', 'cover', 2147483647, 0, 0),
('bx_group_delete', 'picture', 2147483647, 0, 0),
('bx_group_delete', 'do_submit', 2147483647, 1, 1),
('bx_group_delete', 'group_name', 2147483647, 0, 2),
('bx_group_delete', 'group_cat', 2147483647, 0, 3),

('bx_group_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_group_edit', 'cover', 2147483647, 0, 3),
('bx_group_edit', 'picture', 2147483647, 1, 5),
('bx_group_edit', 'group_name', 2147483647, 1, 6),
('bx_group_edit', 'group_cat', 2147483647, 1, 7),
('bx_group_edit', 'group_desc', 2147483647, 1, 8),
('bx_group_edit', 'join_confirmation', 2147483647, 1, 9),
('bx_group_edit', 'allow_view_to', 2147483647, 1, 10),
('bx_group_edit', 'do_submit', 2147483647, 1, 11),

('bx_group_edit_cover', 'join_confirmation', 2147483647, 0, 0),
('bx_group_edit_cover', 'group_desc', 2147483647, 0, 0),
('bx_group_edit_cover', 'delete_confirm', 2147483647, 0, 1),
('bx_group_edit_cover', 'group_name', 2147483647, 0, 2),
('bx_group_edit_cover', 'picture', 2147483647, 0, 3),
('bx_group_edit_cover', 'group_cat', 2147483647, 0, 5),
('bx_group_edit_cover', 'cover', 2147483647, 1, 7),
('bx_group_edit_cover', 'do_submit', 2147483647, 1, 8),

('bx_group_view', 'delete_confirm', 2147483647, 0, 3),
('bx_group_view', 'picture', 2147483647, 0, 4),
('bx_group_view', 'cover', 2147483647, 0, 5),
('bx_group_view', 'do_submit', 2147483647, 0, 6),
('bx_group_view', 'group_name', 2147483647, 1, 7),
('bx_group_view', 'group_cat', 2147483647, 1, 8),
('bx_group_view', 'group_desc', 2147483647, 0, 9),

('bx_group_view_full', 'group_name', 2147483647, 1, 1),
('bx_group_view_full', 'group_cat', 2147483647, 1, 2),
('bx_group_view_full', 'group_desc', 2147483647, 1, 3);

-- PRE-VALUES

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_groups_cats', '_bx_groups_pre_lists_cats', 'bx_groups', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_groups_cats', '', 0, '_sys_please_select', ''),
('bx_groups_cats', '1', 1, '_bx_groups_cat_General', ''),
('bx_groups_cats', '2', 2, '_bx_groups_cat_Business', ''),
('bx_groups_cats', '3', 3, '_bx_groups_cat_Interests', ''),
('bx_groups_cats', '4', 4, '_bx_groups_cat_Causes', ''),
('bx_groups_cats', '5', 5, '_bx_groups_cat_Fun', ''),
('bx_groups_cats', '6', 6, '_bx_groups_cat_Uncategorised', '');

-- STUDIO PAGE & WIDGET

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_groups', '_bx_groups', '_bx_groups', 'bx_groups@modules/boonex/groups/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_groups', '{url_studio}module.php?name=bx_groups', '', 'bx_groups@modules/boonex/groups/|std-wi.png', '_bx_groups', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

