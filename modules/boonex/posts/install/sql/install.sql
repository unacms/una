
-- TABLE: entries

CREATE TABLE IF NOT EXISTS `bx_posts_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: storages & transcoders

CREATE TABLE IF NOT EXISTS `bx_posts_files` (
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

CREATE TABLE IF NOT EXISTS `bx_posts_photos_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_posts_cmts` (
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

-- TABLE: votes

CREATE TABLE IF NOT EXISTS `bx_posts_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_posts_votes_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- TABLE: views

CREATE TABLE `bx_posts_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: metas

CREATE TABLE `bx_posts_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_posts_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- STORAGES & TRANSCODERS

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_posts_files', 'Local', '', 360, 2592000, 3, 'bx_posts_files', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_posts_photos_resized', 'Local', '', 360, 2592000, 3, 'bx_posts_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_posts_preview', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_posts_files";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_posts_preview', 'Resize', 'a:4:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');

-- FORMS

INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts', 'bx_posts', '_bx_posts_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_posts_posts', 'id', '', '', 'do_submit', '', 0, 1, 'BxPostsFormEntry', 'modules/boonex/posts/classes/BxPostsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_posts', 'bx_posts_entry_add', 'bx_posts', 0, '_bx_posts_form_entry_display_add'),
('bx_posts', 'bx_posts_entry_edit', 'bx_posts', 0, '_bx_posts_form_entry_display_edit'),
('bx_posts', 'bx_posts_entry_delete', 'bx_posts', 0, '_bx_posts_form_entry_display_delete'),
('bx_posts', 'bx_posts_entry_view', 'bx_posts', 1, '_bx_posts_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_posts_form_entry_input_sys_delete_confirm', '_bx_posts_form_entry_input_delete_confirm', '_bx_posts_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_posts_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_posts', 'bx_posts', 'do_submit', '_bx_posts_form_entry_input_do_submit', '', 0, 'submit', '_bx_posts_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'title', '', '', 0, 'text', '_bx_posts_form_entry_input_sys_title', '_bx_posts_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_posts_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_posts', 'bx_posts', 'text', '', '', 0, 'textarea', '_bx_posts_form_entry_input_sys_text', '_bx_posts_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_posts_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_posts', 'bx_posts', 'pictures', '', '', 0, 'files', '_bx_posts_form_entry_input_sys_pictures', '_bx_posts_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'allow_view_to', '', '', 0, 'custom', '_bx_posts_form_entry_input_sys_allow_view_to', '_bx_posts_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_posts', 'bx_posts', 'location', '', '', 0, 'custom', '_sys_form_input_sys_location', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_posts_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_posts_entry_add', 'title', 2147483647, 1, 2),
('bx_posts_entry_add', 'text', 2147483647, 1, 3),
('bx_posts_entry_add', 'pictures', 2147483647, 1, 4),
('bx_posts_entry_add', 'allow_view_to', 2147483647, 1, 5),
('bx_posts_entry_add', 'location', 2147483647, 1, 6),
('bx_posts_entry_add', 'do_submit', 2147483647, 1, 7),

('bx_posts_entry_edit', 'delete_confirm', 2147483647, 0, 1),
('bx_posts_entry_edit', 'title', 2147483647, 1, 2),
('bx_posts_entry_edit', 'text', 2147483647, 1, 3),
('bx_posts_entry_edit', 'pictures', 2147483647, 1, 4),
('bx_posts_entry_edit', 'allow_view_to', 2147483647, 1, 5),
('bx_posts_entry_edit', 'location', 2147483647, 1, 6),
('bx_posts_entry_edit', 'do_submit', 2147483647, 1, 7),

('bx_posts_entry_view', 'delete_confirm', 2147483647, 0, 0),
('bx_posts_entry_view', 'allow_view_to', 2147483647, 0, 0),
('bx_posts_entry_view', 'do_submit', 2147483647, 0, 0),
('bx_posts_entry_view', 'text', 2147483647, 0, 0),
('bx_posts_entry_view', 'title', 2147483647, 0, 0),
('bx_posts_entry_view', 'pictures', 2147483647, 0, 0),

('bx_posts_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_posts_entry_delete', 'do_submit', 2147483647, 1, 2),
('bx_posts_entry_delete', 'allow_view_to', 2147483647, 0, 0),
('bx_posts_entry_delete', 'pictures', 2147483647, 0, 0),
('bx_posts_entry_delete', 'text', 2147483647, 0, 0),
('bx_posts_entry_delete', 'title', 2147483647, 0, 0);

-- COMMENTS

INSERT INTO `sys_objects_cmts` (`Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_posts', 'bx_posts_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 0, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', 'bx_posts_posts', 'id', 'title', 'comments', '', '');

-- VOTES

INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_posts', 'bx_posts_votes', 'bx_posts_votes_track', '604800', '1', '1', '0', '1', 'bx_posts_posts', 'id', 'rate', 'votes', '', '');

-- VIEWS

INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_posts', 'bx_posts_views_track', '86400', '1', 'bx_posts_posts', 'id', 'views', '', '');

-- STUDIO: page & widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_posts', '_bx_posts', '_bx_posts', 'bx_posts@modules/boonex/posts/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_posts', '{url_studio}module.php?name=bx_posts', '', 'bx_posts@modules/boonex/posts/|std-wi.png', '_bx_posts', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

