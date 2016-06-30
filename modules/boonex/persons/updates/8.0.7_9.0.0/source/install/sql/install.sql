
-- TABLE: PROFILES
CREATE TABLE IF NOT EXISTS `bx_persons_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  `cover` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `fullname` (`fullname`)
);

-- TABLE: STORAGES & TRANSCODERS
CREATE TABLE `bx_persons_pictures` (
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

CREATE TABLE `bx_persons_pictures_resized` (
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
CREATE TABLE `bx_persons_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: metas

CREATE TABLE `bx_persons_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- STORAGES & TRANSCODERS

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_persons_pictures', 'Local', '', 360, 2592000, 3, 'bx_persons_pictures', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_persons_pictures_resized', 'Local', '', 360, 2592000, 3, 'bx_persons_pictures_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_persons_icon', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0'),
('bx_persons_thumb', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0'),
('bx_persons_avatar', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0'),
('bx_persons_picture', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0'),
('bx_persons_cover', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0'),
('bx_persons_cover_thumb', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_persons_icon', 'Resize', 'a:3:{s:1:"w";s:2:"32";s:1:"h";s:2:"32";s:13:"square_resize";s:1:"1";}', '0'),
('bx_persons_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_persons_avatar', 'Resize', 'a:3:{s:1:"w";s:2:"96";s:1:"h";s:2:"96";s:13:"square_resize";s:1:"1";}', '0'),
('bx_persons_picture', 'Resize', 'a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}', '0'),
('bx_persons_cover', 'Resize', 'a:1:{s:1:"w";s:4:"1024";}', '0'),
('bx_persons_cover_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0');

-- FORMS

INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_person', 'bx_persons', '_bx_persons_form_profile', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_persons_data', 'id', '', '', 'do_submit', '', 0, 1, 'BxPersonsFormEntry', 'modules/boonex/persons/classes/BxPersonsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_person', 'bx_person_add', 'bx_persons', 0, '_bx_persons_form_profile_display_add'),
('bx_person', 'bx_person_delete', 'bx_persons', 0, '_bx_persons_form_profile_display_delete'),
('bx_person', 'bx_person_edit', 'bx_persons', 0, '_bx_persons_form_profile_display_edit'),
('bx_person', 'bx_person_edit_cover', 'bx_persons', 0, '_bx_persons_form_profile_display_edit_cover'),
('bx_person', 'bx_person_view', 'bx_persons', 1, '_bx_persons_form_profile_display_view'),
('bx_person', 'bx_person_view_full', 'bx_persons', 1, '_bx_persons_form_profile_display_view_full');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'allow_view_to', 3, '', 0, 'custom', '_bx_persons_form_profile_input_sys_allow_view_to', '_bx_persons_form_profile_input_allow_view_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_person', 'bx_persons', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_persons_form_profile_input_sys_delete_confirm', '_bx_persons_form_profile_input_delete_confirm', '_bx_persons_form_profile_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_persons_form_profile_input_delete_confirm_error', '', '', 1, 0),
('bx_person', 'bx_persons', 'do_submit', '_bx_persons_form_profile_input_submit', '', 0, 'submit', '_bx_persons_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'description', '', '', 0, 'textarea', '_bx_persons_form_profile_input_sys_desc', '_bx_persons_form_profile_input_desc', '', 0, 0, 0, '', '', '', '', '', '', 'XssMultiline', '', 1, 1),
('bx_person', 'bx_persons', 'fullname', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_fullname', '_bx_persons_form_profile_input_fullname', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_persons_form_profile_input_fullname_err', 'Xss', '', 1, 0),
('bx_person', 'bx_persons', 'cover', 'a:1:{i:0;s:21:"bx_persons_cover_crop";}', 'a:1:{s:21:"bx_persons_cover_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_persons_form_profile_input_sys_cover', '_bx_persons_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'picture', 'a:1:{i:0;s:23:"bx_persons_picture_crop";}', 'a:1:{s:23:"bx_persons_picture_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_persons_form_profile_input_sys_picture', '_bx_persons_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_persons_form_profile_input_picture_err', '', '', 1, 0);


INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_add', 'delete_confirm', 2147483647, 0, 3),
('bx_person_add', 'cover', 2147483647, 0, 4),
('bx_person_add', 'picture', 2147483647, 1, 5),
('bx_person_add', 'fullname', 2147483647, 1, 6),
('bx_person_add', 'description', 2147483647, 1, 8),
('bx_person_add', 'allow_view_to', 2147483647, 1, 9),
('bx_person_add', 'do_submit', 2147483647, 1, 10),

('bx_person_delete', 'cover', 2147483647, 0, 0),
('bx_person_delete', 'picture', 2147483647, 0, 0),
('bx_person_delete', 'delete_confirm', 2147483647, 1, 0),
('bx_person_delete', 'do_submit', 2147483647, 1, 1),
('bx_person_delete', 'fullname', 2147483647, 0, 2),

('bx_person_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_person_edit', 'cover', 2147483647, 0, 3),
('bx_person_edit', 'picture', 2147483647, 1, 5),
('bx_person_edit', 'fullname', 2147483647, 1, 6),
('bx_person_edit', 'description', 2147483647, 1, 7),
('bx_person_edit', 'allow_view_to', 2147483647, 1, 8),
('bx_person_edit', 'do_submit', 2147483647, 1, 9),

('bx_person_edit_cover', 'delete_confirm', 2147483647, 0, 1),
('bx_person_edit_cover', 'fullname', 2147483647, 0, 2),
('bx_person_edit_cover', 'picture', 2147483647, 0, 3),
('bx_person_edit_cover', 'cover', 2147483647, 1, 7),
('bx_person_edit_cover', 'do_submit', 2147483647, 1, 8),

('bx_person_view', 'delete_confirm', 2147483647, 0, 3),
('bx_person_view', 'picture', 2147483647, 0, 4),
('bx_person_view', 'cover', 2147483647, 0, 5),
('bx_person_view', 'do_submit', 2147483647, 0, 6),
('bx_person_view', 'fullname', 2147483647, 1, 7),
('bx_person_view', 'description', 2147483647, 0, 8),

('bx_person_view_full', 'fullname', 2147483647, 1, 1),
('bx_person_view_full', 'description', 2147483647, 1, 2);

-- STUDIO PAGE & WIDGET

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_persons', '_bx_persons', '_bx_persons', 'bx_persons@modules/boonex/persons/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_persons', '{url_studio}module.php?name=bx_persons', '', 'bx_persons@modules/boonex/persons/|std-wi.png', '_bx_persons', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

