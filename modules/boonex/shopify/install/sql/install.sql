SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');


-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_shopify_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL default '',
  `cat` int(11) NOT NULL,
  `location` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rrate` float NOT NULL default '0',
  `rvotes` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `sc_up` int(11) NOT NULL default '0',
  `sc_down` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `cf` int(11) NOT NULL default '1',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`)
);

-- TABLE: settings
CREATE TABLE IF NOT EXISTS `bx_shopify_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `domain` varchar(255) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `author` (`author`)
);

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_shopify_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_shopify_cmts_notes` (
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

-- TABLE: votes
CREATE TABLE IF NOT EXISTS `bx_shopify_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_reactions_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- TABLE: views
CREATE TABLE IF NOT EXISTS `bx_shopify_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE IF NOT EXISTS `bx_shopify_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_meta_locations` (
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

CREATE TABLE IF NOT EXISTS `bx_shopify_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_shopify_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_reports_track` (
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

-- TABLE: favorites
CREATE TABLE IF NOT EXISTS `bx_shopify_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_shopify_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_shopify_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_shopify', 'bx_shopify', '_bx_shopify_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_shopify_entries', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', '', 0, 1, 'BxShopifyFormEntry', 'modules/boonex/shopify/classes/BxShopifyFormEntry.php'),
('bx_shopify_settings', 'bx_shopify', '_bx_shopify_form_settings', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_shopify_settings', 'id', '', '', 'do_submit', '', 0, 1, 'BxShopifyFormSettings', 'modules/boonex/shopify/classes/BxShopifyFormSettings.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_shopify', 'bx_shopify_entry_add', 'bx_shopify', 0, '_bx_shopify_form_entry_display_add'),
('bx_shopify', 'bx_shopify_entry_delete', 'bx_shopify', 0, '_bx_shopify_form_entry_display_delete'),
('bx_shopify', 'bx_shopify_entry_edit', 'bx_shopify', 0, '_bx_shopify_form_entry_display_edit'),
('bx_shopify', 'bx_shopify_entry_view', 'bx_shopify', 1, '_bx_shopify_form_entry_display_view'),

('bx_shopify_settings', 'bx_shopify_settings_edit', 'bx_shopify', 0, '_bx_shopify_form_settings_display_edit');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_shopify', 'bx_shopify', 'cf', '', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_shopify', 'bx_shopify', 'allow_view_to', '', '', 0, 'custom', '_bx_shopify_form_entry_input_sys_allow_view_to', '_bx_shopify_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_shopify', 'bx_shopify', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_shopify_form_entry_input_sys_delete_confirm', '_bx_shopify_form_entry_input_delete_confirm', '_bx_shopify_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_shopify_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_shopify', 'bx_shopify', 'do_publish', '_bx_shopify_form_entry_input_do_publish', '', 0, 'submit', '_bx_shopify_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_shopify', 'bx_shopify', 'do_submit', '_bx_shopify_form_entry_input_do_submit', '', 0, 'submit', '_bx_shopify_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_shopify', 'bx_shopify', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_shopify', 'bx_shopify', 'title', '', '', 0, 'text', '_bx_shopify_form_entry_input_sys_title', '_bx_shopify_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_shopify_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_shopify', 'bx_shopify', 'code', '', '', 0, 'text', '_bx_shopify_form_entry_input_sys_code', '_bx_shopify_form_entry_input_code', '_bx_shopify_form_entry_input_code_inf', 1, 0, 0, '', '', '', 'Avail', '', '_bx_shopify_form_entry_input_code_err', 'Xss', '', 1, 0),
('bx_shopify', 'bx_shopify', 'cat', '', '#!bx_shopify_cats', 0, 'select', '_bx_shopify_form_entry_input_sys_cat', '_bx_shopify_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_shopify_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_shopify', 'bx_shopify', 'added', '', '', 0, 'datetime', '_bx_shopify_form_entry_input_sys_date_added', '_bx_shopify_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_shopify', 'bx_shopify', 'changed', '', '', 0, 'datetime', '_bx_shopify_form_entry_input_sys_date_changed', '_bx_shopify_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),

('bx_shopify_settings', 'bx_shopify', 'domain', '', '', 0, 'text', '_bx_shopify_form_settings_input_sys_domain', '_bx_shopify_form_settings_input_domain', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_shopify_form_settings_input_domain_err', 'Xss', '', 1, 0),
('bx_shopify_settings', 'bx_shopify', 'access_token', '', '', 0, 'text', '_bx_shopify_form_settings_input_sys_access_token', '_bx_shopify_form_settings_input_access_token', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_shopify_form_settings_input_access_token_err', 'Xss', '', 1, 0),
('bx_shopify_settings', 'bx_shopify', 'do_submit', '_bx_shopify_form_settings_input_do_submit', '', 0, 'submit', '_bx_shopify_form_settings_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_shopify_entry_add', 'code', 2147483647, 1, 1),
('bx_shopify_entry_add', 'title', 2147483647, 1, 2),
('bx_shopify_entry_add', 'cat', 2147483647, 1, 3),
('bx_shopify_entry_add', 'allow_view_to', 2147483647, 1, 4),
('bx_shopify_entry_add', 'cf', 2147483647, 1, 5),
('bx_shopify_entry_add', 'location', 2147483647, 1, 6),
('bx_shopify_entry_add', 'do_publish', 2147483647, 1, 7),

('bx_shopify_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_shopify_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_shopify_entry_edit', 'code', 2147483647, 1, 1),
('bx_shopify_entry_edit', 'title', 2147483647, 1, 2),
('bx_shopify_entry_edit', 'cat', 2147483647, 1, 3),
('bx_shopify_entry_edit', 'allow_view_to', 2147483647, 1, 4),
('bx_shopify_entry_edit', 'cf', 2147483647, 1, 5),
('bx_shopify_entry_edit', 'location', 2147483647, 1, 6),
('bx_shopify_entry_edit', 'do_submit', 2147483647, 1, 7),

('bx_shopify_entry_view', 'cat', 2147483647, 1, 1),
('bx_shopify_entry_view', 'added', 2147483647, 1, 2),
('bx_shopify_entry_view', 'changed', 2147483647, 1, 3),

('bx_shopify_entry_view_full', 'cat', 2147483647, 1, 1),
('bx_shopify_entry_view_full', 'added', 2147483647, 1, 2),
('bx_shopify_entry_view_full', 'changed', 2147483647, 1, 3),

('bx_shopify_settings_edit', 'mode', 2147483647, 1, 1),
('bx_shopify_settings_edit', 'domain', 2147483647, 1, 2),
('bx_shopify_settings_edit', 'access_token', 2147483647, 1, 3),
('bx_shopify_settings_edit', 'do_submit', 2147483647, 1, 4);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('bx_shopify_cats', '_bx_shopify_pre_lists_cats', 'bx_shopify', '0', '1');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_shopify_cats', '', 0, '_sys_please_select', ''),
('bx_shopify_cats', '1', 1, '_bx_shopify_cat_automotive_and_industrial', ''),
('bx_shopify_cats', '2', 2, '_bx_shopify_cat_beauty_health_food', ''),
('bx_shopify_cats', '3', 3, '_bx_shopify_cat_books', ''),
('bx_shopify_cats', '4', 4, '_bx_shopify_cat_clothing_shoes_jewelry', ''),
('bx_shopify_cats', '5', 5, '_bx_shopify_cat_electronics_and_computers', ''),
('bx_shopify_cats', '6', 6, '_bx_shopify_cat_handmade', ''),
('bx_shopify_cats', '7', 7, '_bx_shopify_cat_home_garden_tools', ''),
('bx_shopify_cats', '8', 8, '_bx_shopify_cat_movies_music_games', ''),
('bx_shopify_cats', '9', 9, '_bx_shopify_cat_sports_and_outdoors', ''),
('bx_shopify_cats', '10', 10, '_bx_shopify_cat_toys_kids_baby', '');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_shopify', 'bx_shopify', 'bx_shopify_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-shopify-entry&id={object_id}', '', 'bx_shopify_entries', 'id', 'author', 'title', 'comments', '', ''),
('bx_shopify_notes', 'bx_shopify', 'bx_shopify_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-shopify-entry&id={object_id}', '', 'bx_shopify_entries', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');


-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_shopify', 'bx_shopify_votes', 'bx_shopify_votes_track', '604800', '1', '1', '0', '1', 'bx_shopify_entries', 'id', 'author', 'rate', 'votes', '', ''),
('bx_shopify_reactions', 'bx_shopify_reactions', 'bx_shopify_reactions_track', '604800', '1', '1', '1', '1', 'bx_shopify_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');


-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_shopify', 'bx_shopify', 'bx_shopify_scores', 'bx_shopify_scores_track', '604800', '0', 'bx_shopify_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_shopify', 'bx_shopify', 'bx_shopify_reports', 'bx_shopify_reports_track', '1', 'page.php?i=view-shopify-entry&id={object_id}', 'bx_shopify_notes', 'bx_shopify_entries', 'id', 'author', 'reports', '', '');


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_shopify', 'bx_shopify_views_track', '86400', '1', 'bx_shopify_entries', 'id', 'author', 'views', '', '');


-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_shopify', 'bx_shopify_favorites_track', '1', '1', '1', 'page.php?i=view-shopify-entry&id={object_id}', 'bx_shopify_entries', 'id', 'author', 'favorites', '', '');


-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_shopify', 'bx_shopify', '1', '1', 'page.php?i=view-shopify-entry&id={object_id}', 'bx_shopify_entries', 'id', 'author', 'featured', '', '');


-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_shopify', '_bx_shopify', 'bx_shopify', 'added', 'edited', 'deleted', '', ''),
('bx_shopify_cmts', '_bx_shopify_cmts', 'bx_shopify', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_shopify', 'bx_shopify_administration', 'id', '', ''),
('bx_shopify', 'bx_shopify_common', 'id', '', '');


-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_shopify', 'bx_shopify', 'bx_shopify', '_bx_shopify_search_extended', 1, '', ''),
('bx_shopify_cmts', 'bx_shopify_cmts', 'bx_shopify', '_bx_shopify_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_shopify', '_bx_shopify', '_bx_shopify', 'bx_shopify@modules/boonex/shopify/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_shopify', 'integrations', '{url_studio}module.php?name=bx_shopify', '', 'bx_shopify@modules/boonex/shopify/|std-icon.svg', '_bx_shopify', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
