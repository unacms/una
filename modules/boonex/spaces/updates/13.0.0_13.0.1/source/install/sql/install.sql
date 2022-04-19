SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- TABLE: PROFILES
CREATE TABLE IF NOT EXISTS `bx_spaces_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `parent_space` int(10) unsigned NOT NULL  default '0',
  `level` int(10) unsigned NOT NULL  default '0',
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  `cover` int(11) NOT NULL,
  `space_name` varchar(255) NOT NULL,
  `space_cat` int(11) NOT NULL,
  `space_desc` text NOT NULL,
  `labels` text NOT NULL,
  `location` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `sc_up` int(11) NOT NULL default '0',
  `sc_down` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `cf` int(11) NOT NULL default '1',
  `join_confirmation` tinyint(4) NOT NULL DEFAULT '0',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `allow_post_to` varchar(16) NOT NULL DEFAULT '3',
  `status` enum('active','awaiting','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`space_name`, `space_desc`)
);

-- TABLE: STORAGES & TRANSCODERS
CREATE TABLE `bx_spaces_pics` (
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

CREATE TABLE `bx_spaces_pics_resized` (
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

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_spaces_cmts` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  `cmt_cf` int(11) NOT NULL default '1',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

CREATE TABLE IF NOT EXISTS `bx_spaces_cmts_notes` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

-- TABLE: VIEWS
CREATE TABLE `bx_spaces_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: VOTES
CREATE TABLE IF NOT EXISTS `bx_spaces_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_spaces_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- TABLE: REPORTS
CREATE TABLE IF NOT EXISTS `bx_spaces_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_spaces_reports_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `text` text NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `checked_by` int(11) NOT NULL default '0',
  `status` tinyint(11) NOT NULL default '0',	
  PRIMARY KEY (`id`),
  KEY `report` (`object_id`, `author_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_spaces_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE IF NOT EXISTS `bx_spaces_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `street_number` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
);

CREATE TABLE `bx_spaces_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: fans
CREATE TABLE IF NOT EXISTS `bx_spaces_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `mutual` tinyint(4) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);

-- TABLE: admins
CREATE TABLE IF NOT EXISTS `bx_spaces_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_profile_id` int(10) unsigned NOT NULL,
  `fan_id` int(10) unsigned NOT NULL,
  `role` int(10) unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `expired` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin` (`group_profile_id`,`fan_id`)
);

-- TABLE: favorites
CREATE TABLE `bx_spaces_favorites_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`author_id`)
);

CREATE TABLE `bx_spaces_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_spaces_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_spaces_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_spaces_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(128) NOT NULL default '0',
  `group_profile_id` int(11) NOT NULL default '0',
  `author_profile_id` int(11) NOT NULL default '0',
  `invited_profile_id` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`)
);

-- TABLE: Pricing
CREATE TABLE IF NOT EXISTS `bx_spaces_prices` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `role_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(128) NOT NULL default '',
  `period` int(11) unsigned NOT NULL default '1',
  `period_unit` varchar(32) NOT NULL default '',
  `price` float unsigned NOT NULL default '1',
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `type` (`profile_id`, `role_id`,`period`, `period_unit`)
);

-- STORAGES & TRANSCODERS
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_spaces_pics', @sStorageEngine, '', 360, 2592000, 3, 'bx_spaces_pics', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_spaces_pics_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_spaces_pics_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_spaces_icon', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0'),
('bx_spaces_thumb', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0'),
('bx_spaces_avatar', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0'),
('bx_spaces_avatar_big', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0'),
('bx_spaces_picture', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0'),
('bx_spaces_cover', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0'),
('bx_spaces_cover_thumb', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0'),
('bx_spaces_gallery', 'bx_spaces_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_spaces_pics";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_spaces_icon', 'Resize', 'a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}', '0'),
('bx_spaces_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}', '0'),
('bx_spaces_avatar', 'Resize', 'a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}', '0'),
('bx_spaces_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0'),
('bx_spaces_picture', 'Resize', 'a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}', '0'),
('bx_spaces_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),
('bx_spaces_cover_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_spaces_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');

-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_space', 'bx_spaces', '_bx_spaces_form_profile', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_spaces_data', 'id', '', '', 'do_submit', '', 0, 1, 'BxSpacesFormEntry', 'modules/boonex/spaces/classes/BxSpacesFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_space', 'bx_space_add', 'bx_spaces', 0, '_bx_spaces_form_profile_display_add'),
('bx_space', 'bx_space_delete', 'bx_spaces', 0, '_bx_spaces_form_profile_display_delete'),
('bx_space', 'bx_space_edit', 'bx_spaces', 0, '_bx_spaces_form_profile_display_edit'),
('bx_space', 'bx_space_edit_cover', 'bx_spaces', 0, '_bx_spaces_form_profile_display_edit_cover'),
('bx_space', 'bx_space_view', 'bx_spaces', 1, '_bx_spaces_form_profile_display_view'),
('bx_space', 'bx_space_view_full', 'bx_spaces', 1, '_bx_spaces_form_profile_display_view_full'),
('bx_space', 'bx_space_invite', 'bx_spaces', 0, '_bx_spaces_form_profile_display_invite');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_space', 'bx_spaces', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_space', 'bx_spaces', 'allow_view_to', 3, '', 0, 'custom', '_bx_spaces_form_profile_input_sys_allow_view_to', '_bx_spaces_form_profile_input_allow_view_to', '_bx_spaces_form_profile_input_allow_view_to_desc', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_space', 'bx_spaces', 'allow_post_to', 3, '', 0, 'custom', '_bx_spaces_form_profile_input_sys_allow_post_to', '_bx_spaces_form_profile_input_allow_post_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_space', 'bx_spaces', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_spaces_form_profile_input_sys_delete_confirm', '_bx_spaces_form_profile_input_delete_confirm', '_bx_spaces_form_profile_input_delete_confirm_info', 1, 0, 0, '', '', '', 'avail', '', '_bx_spaces_form_profile_input_delete_confirm_error', '', '', 1, 0),
('bx_space', 'bx_spaces', 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_bx_spaces_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_space', 'bx_spaces', 'space_desc', '', '', 0, 'textarea', '_bx_spaces_form_profile_input_sys_space_desc', '_bx_spaces_form_profile_input_space_desc', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 1),
('bx_space', 'bx_spaces', 'space_cat', '', '#!bx_spaces_cats', 0, 'select', '_bx_spaces_form_profile_input_sys_space_cat', '_bx_spaces_form_profile_input_space_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_spaces_form_profile_input_space_cat_err', 'Xss', '', 1, 1),
('bx_space', 'bx_spaces', 'space_name', '', '', 0, 'text', '_bx_spaces_form_profile_input_sys_space_name', '_bx_spaces_form_profile_input_space_name', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_spaces_form_profile_input_space_name_err', 'Xss', '', 1, 0),
('bx_space', 'bx_spaces', 'parent_space', '', '', 0, 'custom', '_bx_spaces_form_profile_input_sys_parent_space', '_bx_spaces_form_profile_input_parent_space', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_space', 'bx_spaces', 'initial_members', '', '', 0, 'custom', '_bx_spaces_form_profile_input_sys_initial_members', '_bx_spaces_form_profile_input_initial_members', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_space', 'bx_spaces', 'join_confirmation', 1, '', 0, 'switcher', '_bx_spaces_form_profile_input_sys_join_confirm', '_bx_spaces_form_profile_input_join_confirm', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_space', 'bx_spaces', 'cover', 'a:1:{i:0;s:20:"bx_spaces_cover_crop";}', 'a:1:{s:20:"bx_spaces_cover_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_spaces_form_profile_input_sys_cover', '_bx_spaces_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_space', 'bx_spaces', 'picture', 'a:1:{i:0;s:22:"bx_spaces_picture_crop";}', 'a:1:{s:22:"bx_spaces_picture_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_spaces_form_profile_input_sys_picture', '_bx_spaces_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_spaces_form_profile_input_picture_err', '', '', 1, 0),
('bx_space', 'bx_spaces', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_space', 'bx_spaces', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_space_add', 'initial_members', 2147483647, 1, 1),
('bx_space_add', 'space_name', 2147483647, 1, 2),
('bx_space_add', 'parent_space', 2147483647, 1, 3),
('bx_space_add', 'space_cat', 2147483647, 1, 4),
('bx_space_add', 'space_desc', 2147483647, 1, 5),
('bx_space_add', 'location', 2147483647, 1, 6),
('bx_space_add', 'join_confirmation', 2147483647, 1, 7),
('bx_space_add', 'allow_view_to', 2147483647, 1, 8),
('bx_space_add', 'allow_post_to', 2147483647, 1, 9),
('bx_space_add', 'cf', 2147483647, 1, 10),
('bx_space_add', 'do_submit', 2147483647, 1, 11),

('bx_space_invite', 'initial_members', 2147483647, 1, 1),
('bx_space_invite', 'do_submit', 2147483647, 1, 2),

('bx_space_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_space_delete', 'do_submit', 2147483647, 1, 2),

('bx_space_edit', 'space_name', 2147483647, 1, 1),
('bx_space_edit', 'parent_space', 2147483647, 1, 2),
('bx_space_edit', 'space_cat', 2147483647, 1, 3),
('bx_space_edit', 'space_desc', 2147483647, 1, 4),
('bx_space_edit', 'location', 2147483647, 1, 5),
('bx_space_edit', 'join_confirmation', 2147483647, 1, 6),
('bx_space_edit', 'allow_view_to', 2147483647, 1, 7),
('bx_space_edit', 'allow_post_to', 2147483647, 1, 8),
('bx_space_edit', 'cf', 2147483647, 1, 9),
('bx_space_edit', 'do_submit', 2147483647, 1, 10),

('bx_space_edit_cover', 'cover', 2147483647, 1, 1),
('bx_space_edit_cover', 'do_submit', 2147483647, 1, 2),

('bx_space_view', 'space_name', 2147483647, 1, 1),
('bx_space_view', 'space_cat', 2147483647, 1, 2),

('bx_space_view_full', 'space_name', 2147483647, 1, 1),
('bx_space_view_full', 'space_cat', 2147483647, 1, 2),
('bx_space_view_full', 'space_desc', 2147483647, 1, 3);

-- FORMS: Price
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_spaces_price', 'bx_spaces', '_bx_spaces_form_price', '', '', 'do_submit', 'bx_spaces_prices', 'id', '', '', '', 0, 1, 'BxSpacesFormPrice', 'modules/boonex/spaces/classes/BxSpacesFormPrice.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_spaces_price_add', 'bx_spaces', 'bx_spaces_price', '_bx_spaces_form_price_display_add', 0),
('bx_spaces_price_edit', 'bx_spaces', 'bx_spaces_price', '_bx_spaces_form_price_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_spaces_price', 'bx_spaces', 'id', '', '', 0, 'hidden', '_bx_spaces_form_price_input_sys_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'role_id', '', '', 0, 'hidden', '_bx_spaces_form_price_input_sys_role_id', '', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'name', '', '', 0, 'text', '_bx_spaces_form_price_input_sys_name', '_bx_spaces_form_price_input_name', '_bx_spaces_form_price_input_inf_name', 1, 0, 0, '', '', '', 'Avail', '', '_bx_spaces_form_price_input_err_name', 'Xss', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'period', '', '', 0, 'text', '_bx_spaces_form_price_input_sys_period', '_bx_spaces_form_price_input_period', '_bx_spaces_form_price_input_inf_period', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'period_unit', '', '#!bx_spaces_period_units', 0, 'select', '_bx_spaces_form_price_input_sys_period_unit', '_bx_spaces_form_price_input_period_unit', '_bx_spaces_form_price_input_inf_period_unit', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'price', '', '', 0, 'price', '_bx_spaces_form_price_input_sys_price', '_bx_spaces_form_price_input_price', '_bx_spaces_form_price_input_inf_price', 1, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'do_submit', '_bx_spaces_form_price_input_do_submit', '', 0, 'submit', '_bx_spaces_form_price_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_spaces_price', 'bx_spaces', 'do_cancel', '_bx_spaces_form_price_input_do_cancel', '', 0, 'button', '_bx_spaces_form_price_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_spaces_price_add', 'id', 2147483647, 0, 1),
('bx_spaces_price_add', 'role_id', 2147483647, 1, 2),
('bx_spaces_price_add', 'name', 2147483647, 1, 3),
('bx_spaces_price_add', 'price', 2147483647, 1, 4),
('bx_spaces_price_add', 'period', 2147483647, 1, 5),
('bx_spaces_price_add', 'period_unit', 2147483647, 1, 6),
('bx_spaces_price_add', 'controls', 2147483647, 1, 7),
('bx_spaces_price_add', 'do_submit', 2147483647, 1, 8),
('bx_spaces_price_add', 'do_cancel', 2147483647, 1, 9),

('bx_spaces_price_edit', 'id', 2147483647, 1, 1),
('bx_spaces_price_edit', 'role_id', 2147483647, 1, 2),
('bx_spaces_price_edit', 'name', 2147483647, 1, 3),
('bx_spaces_price_edit', 'price', 2147483647, 1, 4),
('bx_spaces_price_edit', 'period', 2147483647, 1, 5),
('bx_spaces_price_edit', 'period_unit', 2147483647, 1, 6),
('bx_spaces_price_edit', 'controls', 2147483647, 1, 7),
('bx_spaces_price_edit', 'do_submit', 2147483647, 1, 8),
('bx_spaces_price_edit', 'do_cancel', 2147483647, 1, 9);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_spaces_cats', '_bx_spaces_pre_lists_cats', 'bx_spaces', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_spaces_cats', '', 0, '_sys_please_select', ''),
('bx_spaces_cats', '1', 1, '_bx_spaces_cat_General', ''),
('bx_spaces_cats', '2', 2, '_bx_spaces_cat_Business', ''),
('bx_spaces_cats', '3', 3, '_bx_spaces_cat_Interests', ''),
('bx_spaces_cats', '4', 4, '_bx_spaces_cat_Causes', ''),
('bx_spaces_cats', '5', 5, '_bx_spaces_cat_Fun', ''),
('bx_spaces_cats', '6', 6, '_bx_spaces_cat_Uncategorised', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_spaces_roles', '_bx_spaces_pre_lists_roles', 'bx_spaces', '1');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_spaces_roles', '0', 1, '_bx_spaces_role_regular', ''),
('bx_spaces_roles', '1', 2, '_bx_spaces_role_administrator', ''),
('bx_spaces_roles', '2', 3, '_bx_spaces_role_moderator', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_spaces_period_units', '_bx_spaces_pre_lists_period_units', 'bx_spaces', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_spaces_period_units', '', 0, '_sys_please_select', ''),
('bx_spaces_period_units', 'day', 1, '_bx_spaces_period_unit_day', ''),
('bx_spaces_period_units', 'week', 2, '_bx_spaces_period_unit_week', ''),
('bx_spaces_period_units', 'month', 3, '_bx_spaces_period_unit_month', ''),
('bx_spaces_period_units', 'year', 4, '_bx_spaces_period_unit_year', '');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_spaces', 'bx_spaces', 'bx_spaces_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-space-profile&id={object_id}', '', 'bx_spaces_data', 'id', 'author', 'space_name', 'comments', 'BxSpacesCmts', 'modules/boonex/spaces/classes/BxSpacesCmts.php'),
('bx_spaces_notes', 'bx_spaces', 'bx_spaces_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', 'bx_spaces_data', 'id', 'author', 'space_name', '', 'BxTemplCmtsNotes', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_spaces', 'bx_spaces_views_track', '86400', '1', 'bx_spaces_data', 'id', 'author', 'views', '', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_spaces', 'bx_spaces_votes', 'bx_spaces_votes_track', '604800', '1', '1', '0', '1', 'bx_spaces_data', 'id', 'author', 'rate', 'votes', '', '');

-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_spaces', 'bx_spaces', 'bx_spaces_scores', 'bx_spaces_scores_track', '604800', '0', 'bx_spaces_data', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_spaces', 'bx_spaces', 'bx_spaces_reports', 'bx_spaces_reports_track', '1', 'page.php?i=view-space-profile&id={object_id}', 'bx_spaces_notes', 'bx_spaces_data', 'id', 'author', 'reports', '', '');

-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `table_lists`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_spaces', 'bx_spaces_favorites_track', 'bx_spaces_favorites_lists', '1', '1', '1', 'page.php?i=view-space-profile&id={object_id}', 'bx_spaces_data', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_spaces', 'bx_spaces', '1', '1', 'page.php?i=view-space-profile&id={object_id}', 'bx_spaces_data', 'id', 'author', 'featured', '', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_spaces', '_bx_spaces', 'bx_spaces', 'added', 'edited', 'deleted', '', ''),
('bx_spaces_cmts', '_bx_spaces_cmts', 'bx_spaces', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_spaces', 'bx_spaces_administration', 'td`.`id', '', ''),
('bx_spaces', 'bx_spaces_common', 'td`.`id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_spaces', 'bx_spaces', 'bx_spaces', '_bx_spaces_search_extended', 1, '', ''),
('bx_spaces_cmts', 'bx_spaces_cmts', 'bx_spaces', '_bx_spaces_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_spaces', '_bx_spaces', '_bx_spaces', 'bx_spaces@modules/boonex/spaces/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_spaces', 'content', '{url_studio}module.php?name=bx_spaces', '', 'bx_spaces@modules/boonex/spaces/|std-icon.svg', '_bx_spaces', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

