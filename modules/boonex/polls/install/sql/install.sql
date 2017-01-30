
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_polls_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `text` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  `anonymous` tinyint(4) NOT NULL DEFAULT '0',
  `hidden_results` tinyint(4) NOT NULL DEFAULT '0',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_subentries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_polls_files` (
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

CREATE TABLE IF NOT EXISTS `bx_polls_photos_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_polls_cmts` (
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

-- TABLE: votes
CREATE TABLE IF NOT EXISTS `bx_polls_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_polls_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_polls_votes_subentries` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_polls_votes_subentries_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- TABLE: views
CREATE TABLE `bx_polls_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: metas
CREATE TABLE `bx_polls_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_polls_meta_locations` (
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

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_polls_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_polls_reports_track` (
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
CREATE TABLE `bx_polls_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- STORAGES & TRANSCODERS
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_polls_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_polls_files', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_polls_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_polls_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_polls_preview', 'bx_polls_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_polls_files";}', 'no', '1', '2592000', '0'),
('bx_polls_gallery', 'bx_polls_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_polls_files";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_polls_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_polls_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_polls', 'bx_polls', '_bx_polls_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_polls_entries', 'id', '', '', 'do_submit', '', 0, 1, 'BxPollsFormEntry', 'modules/boonex/polls/classes/BxPollsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_polls', 'bx_polls_entry_add', 'bx_polls', 0, '_bx_polls_form_entry_display_add'),
('bx_polls', 'bx_polls_entry_delete', 'bx_polls', 0, '_bx_polls_form_entry_display_delete'),
('bx_polls', 'bx_polls_entry_edit', 'bx_polls', 0, '_bx_polls_form_entry_display_edit'),
('bx_polls', 'bx_polls_entry_view', 'bx_polls', 1, '_bx_polls_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_polls', 'bx_polls', 'allow_view_to', '', '', 0, 'custom', '_bx_polls_form_entry_input_sys_allow_view_to', '_bx_polls_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_polls', 'bx_polls', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_polls_form_entry_input_sys_delete_confirm', '_bx_polls_form_entry_input_delete_confirm', '_bx_polls_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_polls_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_polls', 'bx_polls', 'do_publish', '_bx_polls_form_entry_input_do_publish', '', 0, 'submit', '_bx_polls_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'do_submit', '_bx_polls_form_entry_input_do_submit', '', 0, 'submit', '_bx_polls_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'pictures', 'a:1:{i:0;s:9:"sys_html5";}', 'a:2:{s:10:"sys_simple";s:26:"_sys_uploader_simple_title";s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_polls_form_entry_input_sys_pictures', '_bx_polls_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'text', '', '', 0, 'textarea', '_bx_polls_form_entry_input_sys_text', '_bx_polls_form_entry_input_text', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_polls', 'bx_polls', 'title', '', '', 0, 'text', '_bx_polls_form_entry_input_sys_title', '_bx_polls_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_polls_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'subentries', '', '', 0, 'custom', '_bx_polls_form_entry_input_sys_subentries', '_bx_polls_form_entry_input_subentries', '_bx_polls_form_entry_input_subentries_inf', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'cat', '', '#!bx_polls_cats', 0, 'select', '_bx_polls_form_entry_input_sys_cat', '_bx_polls_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_polls_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'anonymous', 1, '', 0, 'switcher', '_bx_polls_form_profile_input_sys_anonymous', '_bx_polls_form_profile_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'hidden_results', 1, '', 0, 'switcher', '_bx_polls_form_profile_input_sys_hidden_results', '_bx_polls_form_profile_input_hidden_results', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'added', '', '', 0, 'datetime', '_bx_polls_form_entry_input_sys_date_added', '_bx_polls_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'changed', '', '', 0, 'datetime', '_bx_polls_form_entry_input_sys_date_changed', '_bx_polls_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_polls_entry_add', 'cat', 2147483647, 1, 1),
('bx_polls_entry_add', 'title', 2147483647, 1, 2),
('bx_polls_entry_add', 'subentries', 2147483647, 1, 3),
('bx_polls_entry_add', 'text', 2147483647, 1, 4),
('bx_polls_entry_add', 'pictures', 2147483647, 1, 5),
('bx_polls_entry_add', 'allow_view_to', 2147483647, 1, 6),
('bx_polls_entry_add', 'anonymous', 2147483647, 1, 7),
('bx_polls_entry_add', 'hidden_results', 2147483647, 1, 8),
('bx_polls_entry_add', 'location', 2147483647, 1, 9),
('bx_polls_entry_add', 'do_publish', 2147483647, 1, 10),

('bx_polls_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_polls_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_polls_entry_edit', 'cat', 2147483647, 1, 1),
('bx_polls_entry_edit', 'title', 2147483647, 1, 2),
('bx_polls_entry_edit', 'subentries', 2147483647, 1, 3),
('bx_polls_entry_edit', 'text', 2147483647, 1, 4),
('bx_polls_entry_edit', 'pictures', 2147483647, 1, 5),
('bx_polls_entry_edit', 'allow_view_to', 2147483647, 1, 6),
('bx_polls_entry_edit', 'hidden_results', 2147483647, 1, 7),
('bx_polls_entry_edit', 'location', 2147483647, 1, 8),
('bx_polls_entry_edit', 'do_submit', 2147483647, 1, 9),

('bx_polls_entry_view', 'cat', 2147483647, 1, 1),
('bx_polls_entry_view', 'added', 2147483647, 1, 2),
('bx_polls_entry_view', 'changed', 2147483647, 1, 3);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_polls_cats', '_bx_polls_pre_lists_cats', 'bx_polls', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_polls_cats', '', 0, '_sys_please_select', ''),
('bx_polls_cats', '1', 1, '_bx_polls_cat_Animals_Pets', ''),
('bx_polls_cats', '2', 2, '_bx_polls_cat_Architecture', ''),
('bx_polls_cats', '3', 3, '_bx_polls_cat_Art', ''),
('bx_polls_cats', '4', 4, '_bx_polls_cat_Cars_Motorcycles', ''),
('bx_polls_cats', '5', 5, '_bx_polls_cat_Celebrities', ''),
('bx_polls_cats', '6', 6, '_bx_polls_cat_Design', ''),
('bx_polls_cats', '7', 7, '_bx_polls_cat_DIY_Crafts', ''),
('bx_polls_cats', '8', 8, '_bx_polls_cat_Education', ''),
('bx_polls_cats', '9', 9, '_bx_polls_cat_Film_Music_Books', ''),
('bx_polls_cats', '10', 10, '_bx_polls_cat_Food_Drink', ''),
('bx_polls_cats', '11', 11, '_bx_polls_cat_Gardening', ''),
('bx_polls_cats', '12', 12, '_bx_polls_cat_Geek', ''),
('bx_polls_cats', '13', 13, '_bx_polls_cat_Hair_Beauty', ''),
('bx_polls_cats', '14', 14, '_bx_polls_cat_Health_Fitness', ''),
('bx_polls_cats', '15', 15, '_bx_polls_cat_History', ''),
('bx_polls_cats', '16', 16, '_bx_polls_cat_Holidays_Events', ''),
('bx_polls_cats', '17', 17, '_bx_polls_cat_Home_Decor', ''),
('bx_polls_cats', '18', 18, '_bx_polls_cat_Humor', ''),
('bx_polls_cats', '19', 19, '_bx_polls_cat_Illustrations_Posters', ''),
('bx_polls_cats', '20', 20, '_bx_polls_cat_Kids_Parenting', ''),
('bx_polls_cats', '21', 21, '_bx_polls_cat_Mens_Fashion', ''),
('bx_polls_cats', '22', 22, '_bx_polls_cat_Outdoors', ''),
('bx_polls_cats', '23', 23, '_bx_polls_cat_Photography', ''),
('bx_polls_cats', '24', 24, '_bx_polls_cat_Products', ''),
('bx_polls_cats', '25', 25, '_bx_polls_cat_Quotes', ''),
('bx_polls_cats', '26', 26, '_bx_polls_cat_Science_Nature', ''),
('bx_polls_cats', '27', 27, '_bx_polls_cat_Sports', ''),
('bx_polls_cats', '28', 28, '_bx_polls_cat_Tattoos', ''),
('bx_polls_cats', '29', 29, '_bx_polls_cat_Technology', ''),
('bx_polls_cats', '30', 30, '_bx_polls_cat_Travel', ''),
('bx_polls_cats', '31', 31, '_bx_polls_cat_Weddings', ''),
('bx_polls_cats', '32', 32, '_bx_polls_cat_Womens_Fashion', '');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_polls', 'bx_polls_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-poll&id={object_id}', '', 'bx_polls_entries', 'id', 'author', 'title', 'comments', '', '');


-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_polls', 'bx_polls_votes', 'bx_polls_votes_track', '604800', '1', '1', '0', '1', 'bx_polls_entries', 'id', 'author', 'rate', 'votes', '', ''),
('bx_polls_subentries', 'bx_polls_votes_subentries', 'bx_polls_votes_subentries_track', '604800', '1', '1', '0', '1', 'bx_polls_subentries', 'id', 'author', 'rate', 'votes', 'BxPollsVoteSubentries', 'modules/boonex/polls/classes/BxPollsVoteSubentries.php');


-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls_reports', 'bx_polls_reports_track', '1', 'page.php?i=view-poll&id={object_id}', 'bx_polls_entries', 'id', 'author', 'reports', '', '');


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls_views_track', '86400', '1', 'bx_polls_entries', 'id', 'author', 'views', '', '');


-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls_favorites_track', '1', '1', '1', 'page.php?i=view-poll&id={object_id}', 'bx_polls_entries', 'id', 'author', 'favorites', '', '');


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_polls', '_bx_polls', '_bx_polls', 'bx_polls@modules/boonex/polls/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_polls', '{url_studio}module.php?name=bx_polls', '', 'bx_polls@modules/boonex/polls/|std-icon.svg', '_bx_polls', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
