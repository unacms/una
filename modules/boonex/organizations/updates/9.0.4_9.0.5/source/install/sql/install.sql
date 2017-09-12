
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- TABLE: PROFILES
CREATE TABLE IF NOT EXISTS `bx_organizations_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  `cover` int(11) NOT NULL,
  `org_name` varchar(255) NOT NULL,
  `org_cat` int(11) NOT NULL,
  `org_desc` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`org_name`,`org_desc`)
);

-- TABLE: STORAGES & TRANSCODERS
CREATE TABLE IF NOT EXISTS `bx_organizations_pics` (
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

CREATE TABLE IF NOT EXISTS `bx_organizations_pics_resized` (
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

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_organizations_cmts` (
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

-- TABLE: VIEWS
CREATE TABLE IF NOT EXISTS `bx_organizations_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: favorites
CREATE TABLE IF NOT EXISTS `bx_organizations_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_organizations_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_organizations_reports_track` (
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
CREATE TABLE IF NOT EXISTS `bx_organizations_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_organizations_meta_locations` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- STORAGES & TRANSCODERS
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_organizations_pics', @sStorageEngine, '', 360, 2592000, 3, 'bx_organizations_pics', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_organizations_pics_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_organizations_pics_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_organizations_icon', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_thumb', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_avatar', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_picture', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_cover', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_cover_thumb', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_gallery', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_organizations_icon', 'Resize', 'a:3:{s:1:"w";s:2:"32";s:1:"h";s:2:"32";s:13:"square_resize";s:1:"1";}', '0'),
('bx_organizations_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_organizations_avatar', 'Resize', 'a:3:{s:1:"w";s:2:"96";s:1:"h";s:2:"96";s:13:"square_resize";s:1:"1";}', '0'),
('bx_organizations_picture', 'Resize', 'a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}', '0'),
('bx_organizations_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),
('bx_organizations_cover_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_organizations_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');

-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organization', 'bx_organizations', '_bx_orgs_form_profile', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_organizations_data', 'id', '', '', 'do_submit', '', 0, 1, 'BxOrgsFormEntry', 'modules/boonex/organizations/classes/BxOrgsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_organization', 'bx_organization_add', 'bx_organizations', 0, '_bx_orgs_form_profile_display_add'),
('bx_organization', 'bx_organization_delete', 'bx_organizations', 0, '_bx_orgs_form_profile_display_delete'),
('bx_organization', 'bx_organization_edit', 'bx_organizations', 0, '_bx_orgs_form_profile_display_edit'),
('bx_organization', 'bx_organization_edit_cover', 'bx_organizations', 0, '_bx_orgs_form_profile_display_edit_cover'),
('bx_organization', 'bx_organization_view', 'bx_organizations', 1, '_bx_orgs_form_profile_display_view'),
('bx_organization', 'bx_organization_view_full', 'bx_organizations', 1, '_bx_orgs_form_profile_display_view_full');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_organization', 'bx_organizations', 'allow_view_to', 3, '', 0, 'custom', '_bx_orgs_form_profile_input_sys_allow_view_to', '_bx_orgs_form_profile_input_allow_view_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_orgs_form_profile_input_sys_delete_confirm', '_bx_orgs_form_profile_input_delete_confirm', '_bx_orgs_form_profile_input_delete_confirm_info', 1, 0, 0, '', '', '', 'avail', '', '_bx_orgs_form_profile_input_delete_confirm_error', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_bx_orgs_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'org_desc', '', '', 0, 'textarea', '_bx_orgs_form_profile_input_sys_org_desc', '_bx_orgs_form_profile_input_org_desc', '', 0, 0, 0, '', '', '', '', '', '', 'XssMultiline', '', 1, 1),
('bx_organization', 'bx_organizations', 'org_cat', '', '#!bx_organizations_cats', 0, 'select', '_bx_orgs_form_profile_input_sys_org_cat', '_bx_orgs_form_profile_input_org_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_orgs_form_profile_input_org_cat_err', 'Xss', '', 1, 1),
('bx_organization', 'bx_organizations', 'org_name', '', '', 0, 'text', '_bx_orgs_form_profile_input_sys_org_name', '_bx_orgs_form_profile_input_org_name', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_orgs_form_profile_input_org_name_err', 'Xss', '', 1, 0),
('bx_organization', 'bx_organizations', 'cover', 'a:1:{i:0;s:27:"bx_organizations_cover_crop";}', 'a:1:{s:27:"bx_organizations_cover_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_orgs_form_profile_input_sys_cover', '_bx_orgs_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'picture', 'a:1:{i:0;s:29:"bx_organizations_picture_crop";}', 'a:1:{s:29:"bx_organizations_picture_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_orgs_form_profile_input_sys_picture', '_bx_orgs_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_orgs_form_profile_input_picture_err', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'profile_email', '', '', 0, 'text', '_bx_orgs_form_profile_input_sys_profile_email', '_bx_orgs_form_profile_input_profile_email', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_organization', 'bx_organizations', 'profile_status', '', '', 0, 'custom', '_bx_orgs_form_profile_input_sys_profile_status', '_bx_orgs_form_profile_input_profile_status', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);


INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_organization_add', 'picture', 2147483647, 1, 1),
('bx_organization_add', 'org_name', 2147483647, 1, 2),
('bx_organization_add', 'org_cat', 2147483647, 1, 3),
('bx_organization_add', 'org_desc', 2147483647, 1, 4),
('bx_organization_add', 'location', 2147483647, 1, 5),
('bx_organization_add', 'allow_view_to', 2147483647, 1, 6),
('bx_organization_add', 'do_submit', 2147483647, 1, 7),

('bx_organization_delete', 'picture', 2147483647, 0, 0),
('bx_organization_delete', 'delete_confirm', 2147483647, 1, 0),
('bx_organization_delete', 'cover', 2147483647, 0, 0),
('bx_organization_delete', 'do_submit', 2147483647, 1, 1),
('bx_organization_delete', 'org_name', 2147483647, 0, 2),
('bx_organization_delete', 'org_cat', 2147483647, 0, 3),

('bx_organization_edit', 'picture', 2147483647, 1, 1),
('bx_organization_edit', 'org_name', 2147483647, 1, 2),
('bx_organization_edit', 'org_cat', 2147483647, 1, 3),
('bx_organization_edit', 'org_desc', 2147483647, 1, 4),
('bx_organization_edit', 'location', 2147483647, 1, 5),
('bx_organization_edit', 'allow_view_to', 2147483647, 1, 6),
('bx_organization_edit', 'do_submit', 2147483647, 1, 7),

('bx_organization_edit_cover', 'delete_confirm', 2147483647, 0, 1),
('bx_organization_edit_cover', 'org_name', 2147483647, 0, 2),
('bx_organization_edit_cover', 'picture', 2147483647, 0, 3),
('bx_organization_edit_cover', 'org_cat', 2147483647, 0, 5),
('bx_organization_edit_cover', 'cover', 2147483647, 1, 7),
('bx_organization_edit_cover', 'do_submit', 2147483647, 1, 8),

('bx_organization_view', 'org_name', 2147483647, 1, 1),
('bx_organization_view', 'org_cat', 2147483647, 1, 2),
('bx_organization_view', 'profile_email', 192, 1, 3),
('bx_organization_view', 'profile_status', 192, 1, 4),

('bx_organization_view_full', 'org_name', 2147483647, 1, 1),
('bx_organization_view_full', 'org_cat', 2147483647, 1, 2),
('bx_organization_view_full', 'org_desc', 2147483647, 1, 3),
('bx_organization_view_full', 'profile_email', 192, 1, 4),
('bx_organization_view_full', 'profile_status', 192, 1, 5);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_organizations_cats', '_bx_orgs_pre_lists_cats', 'bx_organizations', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_organizations_cats', '', 0, '_sys_please_select', ''),
('bx_organizations_cats', '1', 1, '_bx_orgs_cat_Agriculture', ''),
('bx_organizations_cats', '2', 2, '_bx_orgs_cat_Financial', ''),
('bx_organizations_cats', '3', 3, '_bx_orgs_cat_Biotech', ''),
('bx_organizations_cats', '4', 4, '_bx_orgs_cat_Cause', ''),
('bx_organizations_cats', '5', 5, '_bx_orgs_cat_Chemical', ''),
('bx_organizations_cats', '6', 6, '_bx_orgs_cat_Religious', ''),
('bx_organizations_cats', '7', 7, '_bx_orgs_cat_Community', ''),
('bx_organizations_cats', '8', 8, '_bx_orgs_cat_Company', ''),
('bx_organizations_cats', '9', 9, '_bx_orgs_cat_Entertainment', ''),
('bx_organizations_cats', '10', 10, '_bx_orgs_cat_Technology', ''),
('bx_organizations_cats', '11', 11, '_bx_orgs_cat_Consulting', ''),
('bx_organizations_cats', '12', 12, '_bx_orgs_cat_Education', ''),
('bx_organizations_cats', '13', 13, '_bx_orgs_cat_Energy', ''),
('bx_organizations_cats', '14', 14, '_bx_orgs_cat_Engineering', ''),
('bx_organizations_cats', '15', 15, '_bx_orgs_cat_Food', ''),
('bx_organizations_cats', '16', 16, '_bx_orgs_cat_Government', ''),
('bx_organizations_cats', '17', 17, '_bx_orgs_cat_Health', ''),
('bx_organizations_cats', '18', 18, '_bx_orgs_cat_Medical', ''),
('bx_organizations_cats', '19', 19, '_bx_orgs_cat_Industrial', ''),
('bx_organizations_cats', '20', 20, '_bx_orgs_cat_Insurance', ''),
('bx_organizations_cats', '21', 21, '_bx_orgs_cat_Internet', ''),
('bx_organizations_cats', '22', 22, '_bx_orgs_cat_Software', ''),
('bx_organizations_cats', '23', 23, '_bx_orgs_cat_Legal', ''),
('bx_organizations_cats', '24', 24, '_bx_orgs_cat_Media', ''),
('bx_organizations_cats', '25', 25, '_bx_orgs_cat_School', ''),
('bx_organizations_cats', '26', 26, '_bx_orgs_cat_NGO', ''),
('bx_organizations_cats', '27', 27, '_bx_orgs_cat_NPO', ''),
('bx_organizations_cats', '28', 28, '_bx_orgs_cat_Political', ''),
('bx_organizations_cats', '29', 29, '_bx_orgs_cat_Retail', ''),
('bx_organizations_cats', '30', 30, '_bx_orgs_cat_Small_Business', ''),
('bx_organizations_cats', '31', 31, '_bx_orgs_cat_Telecommunication', ''),
('bx_organizations_cats', '32', 32, '_bx_orgs_cat_Transport', ''),
('bx_organizations_cats', '33', 33, '_bx_orgs_cat_Travel', ''),
('bx_organizations_cats', '34', 34, '_bx_orgs_cat_University', ''),
('bx_organizations_cats', '35', 35, '_bx_orgs_cat_Other', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_organizations', '_bx_orgs', 'bx_organizations', 'added', 'edited', 'deleted', '', ''),
('bx_organizations_cmts', '_bx_orgs_cmts', 'bx_organizations', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_organizations', 'bx_organizations_administration', 'td`.`id', '', ''),
('bx_organizations', 'bx_organizations_common', 'td`.`id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_organizations', 'bx_organizations', 'bx_organizations', '_bx_orgs_search_extended', 1, '', ''),
('bx_organizations_cmts', 'bx_organizations_cmts', 'bx_organizations', '_bx_orgs_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_organizations', '_bx_orgs', '_bx_orgs', 'bx_organizations@modules/boonex/organizations/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_organizations', '{url_studio}module.php?name=bx_organizations', '', 'bx_organizations@modules/boonex/organizations/|std-icon.svg', '_bx_orgs', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

