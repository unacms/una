
-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_market_products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(11) unsigned NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  `changed` int(11) NOT NULL default '0',
  `thumb` int(11) NOT NULL default '0',
  `cover` int(11) NOT NULL default '0',
  `package` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `notes` text NOT NULL,
  `cat` int(11) NOT NULL,
  `price_single` float NOT NULL default '0',
  `price_recurring` float NOT NULL default '0',
  `duration_recurring` varchar(16) NOT NULL default 'month',
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `allow_view_to` varchar(32) NOT NULL DEFAULT '3',
  `allow_purchase_to` varchar(32) NOT NULL DEFAULT '3',
  `allow_comment_to` varchar(32) NOT NULL DEFAULT 'c',
  `allow_vote_to` varchar(32) NOT NULL DEFAULT 'c',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: licenses
CREATE TABLE IF NOT EXISTS `bx_market_licenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `product_id` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `order` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `license` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `type` varchar(16) collate utf8_unicode_ci NOT NULL default '',
  `domain` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `expired` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`profile_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_market_files` (
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

CREATE TABLE IF NOT EXISTS `bx_market_files2products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) unsigned NOT NULL,
  `file_id` int(11) NOT NULL,
  `type` enum('version','update') NOT NULL DEFAULT 'version',
  `version` varchar(255) NOT NULL,
  `version_to` varchar(255) NOT NULL,
  `downloads` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_content` (`file_id`,`content_id`),
  KEY `content_id` (`content_id`)
);

CREATE TABLE IF NOT EXISTS `bx_market_downloads_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `profile_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_market_photos` (
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

CREATE TABLE IF NOT EXISTS `bx_market_photos2products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(11) unsigned NOT NULL,
  `file_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_content` (`file_id`,`content_id`),
  KEY `content_id` (`content_id`)
);

CREATE TABLE IF NOT EXISTS `bx_market_photos_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_market_cmts` (
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
CREATE TABLE IF NOT EXISTS `bx_market_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_market_votes_track` (
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
CREATE TABLE `bx_market_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: metas
CREATE TABLE `bx_market_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_market_meta_locations` (
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

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_market_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_market_reports_track` (
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
('bx_market_files', 'Local', '', 360, 2592000, 3, 'bx_market_files', 'allow-deny', 'zip', '', 0, 0, 0, 0, 0, 0),
('bx_market_photos', 'Local', '', 360, 2592000, 3, 'bx_market_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_market_photos_resized', 'Local', '', 360, 2592000, 3, 'bx_market_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_market_preview', 'bx_market_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_market_photos";}', 'no', '1', '2592000', '0'),
('bx_market_cover', 'bx_market_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_market_photos";}', 'no', '1', '2592000', '0'),
('bx_market_screenshot', 'bx_market_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_market_photos";}', 'no', '1', '2592000', '0'),
('bx_market_gallery', 'bx_market_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_market_photos";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_market_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_market_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_market_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),
('bx_market_screenshot', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"120";s:11:"crop_resize";s:1:"1";}', '0');

-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market', 'bx_market', '_bx_market_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_market_products', 'id', '', '', 'do_submit', '', 0, 1, 'BxMarketFormEntry', 'modules/boonex/market/classes/BxMarketFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_market', 'bx_market_entry_add', 'bx_market', 0, '_bx_market_form_entry_display_add'),
('bx_market', 'bx_market_entry_delete', 'bx_market', 0, '_bx_market_form_entry_display_delete'),
('bx_market', 'bx_market_entry_edit', 'bx_market', 0, '_bx_market_form_entry_display_edit'),
('bx_market', 'bx_market_entry_view', 'bx_market', 1, '_bx_market_form_entry_display_view'),
('bx_market', 'bx_market_entry_view_full', 'bx_market', 1, '_bx_market_form_entry_display_view_full');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market', 'bx_market', 'allow_view_to', '', '', 0, 'custom', '_bx_market_form_entry_input_sys_allow_view_to', '_bx_market_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_market', 'bx_market', 'allow_purchase_to', '', '', 0, 'custom', '_bx_market_form_entry_input_sys_allow_purchase_to', '_bx_market_form_entry_input_allow_purchase_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_market', 'bx_market', 'allow_comment_to', '', '', 0, 'custom', '_bx_market_form_entry_input_sys_allow_comment_to', '_bx_market_form_entry_input_allow_comment_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_market', 'bx_market', 'allow_vote_to', '', '', 0, 'custom', '_bx_market_form_entry_input_sys_allow_vote_to', '_bx_market_form_entry_input_allow_vote_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_market', 'bx_market', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_market_form_entry_input_sys_delete_confirm', '_bx_market_form_entry_input_delete_confirm', '_bx_market_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_market_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_market', 'bx_market', 'do_publish', '_bx_market_form_entry_input_do_publish', '', 0, 'submit', '_bx_market_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'do_submit', '_bx_market_form_entry_input_do_submit', '', 0, 'submit', '_bx_market_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'location', '', '', 0, 'custom', '_sys_form_input_sys_location', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'pictures', 'a:1:{i:0;s:15:"bx_market_html5";}', 'a:2:{s:16:"bx_market_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_market_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_market_form_entry_input_sys_pictures', '_bx_market_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'files', 'a:1:{i:0;s:15:"bx_market_html5";}', 'a:2:{s:16:"bx_market_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_market_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_market_form_entry_input_sys_files', '_bx_market_form_entry_input_files', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'text', '', '', 0, 'textarea', '_bx_market_form_entry_input_sys_text', '_bx_market_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_market_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_market', 'bx_market', 'notes', '', '', 0, 'textarea', '_bx_market_form_entry_input_sys_notes', '_bx_market_form_entry_input_notes', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_market', 'bx_market', 'title', '', '', 0, 'text', '_bx_market_form_entry_input_sys_title', '_bx_market_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_market_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_market', 'bx_market', 'name', '', '', 0, 'text', '_bx_market_form_entry_input_sys_name', '_bx_market_form_entry_input_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_market_form_entry_input_name_err', 'Xss', '', 1, 0),
('bx_market', 'bx_market', 'cat', '', '#!bx_market_cats', 0, 'select', '_bx_market_form_entry_input_sys_cat', '_bx_market_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_market_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_market', 'bx_market', 'price_single', '', '', 0, 'text', '_bx_market_form_entry_input_sys_price_single', '_bx_market_form_entry_input_price_single', '_bx_market_form_entry_input_price_single_inf', 0, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_market', 'bx_market', 'price_recurring', '', '#!bx_market_prices', 0, 'select', '_bx_market_form_entry_input_sys_price_recurring', '_bx_market_form_entry_input_price_recurring', '_bx_market_form_entry_input_price_recurring_inf', 0, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0),
('bx_market', 'bx_market', 'duration_recurring', 'month', '#!bx_market_durations', 0, 'select', '_bx_market_form_entry_input_sys_duration_recurring', '_bx_market_form_entry_input_duration_recurring', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_market', 'bx_market', 'added', '', '', 0, 'datetime', '_bx_market_form_entry_input_sys_added', '_bx_market_form_entry_input_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'changed', '', '', 0, 'datetime', '_bx_market_form_entry_input_sys_changed', '_bx_market_form_entry_input_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_beg_single', '', '', 0, 'block_header', '_bx_market_form_entry_input_sys_header_beg_single', '_bx_market_form_entry_input_header_beg_single', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_beg_recurring', '', '', 0, 'block_header', '_bx_market_form_entry_input_sys_header_beg_recurring', '_bx_market_form_entry_input_header_beg_recurring', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_beg_privacy', '', '', 0, 'block_header', '_bx_market_form_entry_input_sys_header_beg_privacy', '_bx_market_form_entry_input_header_beg_privacy', '', 0, 1, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_end_single', '', '', 0, 'block_end', '_bx_market_form_entry_input_sys_header_end_single', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_end_recurring', '', '', 0, 'block_end', '_bx_market_form_entry_input_sys_header_end_recurring', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market', 'bx_market', 'header_end_privacy', '', '', 0, 'block_end', '_bx_market_form_entry_input_sys_header_end_privacy', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_market_entry_add', 'title', 2147483647, 1, 2),
('bx_market_entry_add', 'name', 2147483647, 1, 3),
('bx_market_entry_add', 'cat', 2147483647, 1, 4),
('bx_market_entry_add', 'text', 2147483647, 1, 5),
('bx_market_entry_add', 'pictures', 2147483647, 1, 6),
('bx_market_entry_add', 'files', 2147483647, 1, 7),
('bx_market_entry_add', 'header_beg_single', 2147483647, 1, 8),
('bx_market_entry_add', 'price_single', 2147483647, 1, 9),
('bx_market_entry_add', 'header_end_single', 2147483647, 1, 10),
('bx_market_entry_add', 'header_beg_recurring', 2147483647, 1, 11),
('bx_market_entry_add', 'duration_recurring', 2147483647, 1, 12),
('bx_market_entry_add', 'price_recurring', 2147483647, 1, 13),
('bx_market_entry_add', 'header_end_recurring', 2147483647, 1, 14),
('bx_market_entry_add', 'header_beg_privacy', 2147483647, 1, 15),
('bx_market_entry_add', 'allow_view_to', 2147483647, 1, 16),
('bx_market_entry_add', 'allow_purchase_to', 2147483647, 1, 17),
('bx_market_entry_add', 'allow_comment_to', 2147483647, 1, 18),
('bx_market_entry_add', 'allow_vote_to', 2147483647, 1, 19),
('bx_market_entry_add', 'header_end_privacy', 2147483647, 1, 20),
('bx_market_entry_add', 'notes', 2147483647, 1, 21),
('bx_market_entry_add', 'location', 2147483647, 1, 22),
('bx_market_entry_add', 'do_submit', 2147483647, 0, 23),
('bx_market_entry_add', 'do_publish', 2147483647, 1, 24),

('bx_market_entry_delete', 'notes', 2147483647, 0, 0),
('bx_market_entry_delete', 'location', 2147483647, 0, 0),
('bx_market_entry_delete', 'cat', 2147483647, 0, 0),
('bx_market_entry_delete', 'header_beg_single', 2147483647, 0, 0),
('bx_market_entry_delete', 'price_single', 2147483647, 0, 0),
('bx_market_entry_delete', 'header_end_single', 2147483647, 0, 0),
('bx_market_entry_delete', 'header_beg_recurring', 2147483647, 0, 0),
('bx_market_entry_delete', 'duration_recurring', 2147483647, 0, 0),
('bx_market_entry_delete', 'price_recurring', 2147483647, 0, 0),
('bx_market_entry_delete', 'header_end_recurring', 2147483647, 0, 0),
('bx_market_entry_delete', 'pictures', 2147483647, 0, 0),
('bx_market_entry_delete', 'files', 2147483647, 0, 0),
('bx_market_entry_delete', 'text', 2147483647, 0, 0),
('bx_market_entry_delete', 'do_publish', 2147483647, 0, 0),
('bx_market_entry_delete', 'title', 2147483647, 0, 0),
('bx_market_entry_delete', 'name', 2147483647, 0, 0),
('bx_market_entry_delete', 'header_beg_privacy', 2147483647, 0, 0),
('bx_market_entry_delete', 'allow_view_to', 2147483647, 0, 0),
('bx_market_entry_delete', 'allow_purchase_to', 2147483647, 0, 0),
('bx_market_entry_delete', 'allow_comment_to', 2147483647, 0, 0),
('bx_market_entry_delete', 'allow_vote_to', 2147483647, 0, 0),
('bx_market_entry_delete', 'header_end_privacy', 2147483647, 0, 0),
('bx_market_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_market_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_market_entry_edit', 'do_publish', 2147483647, 0, 1),
('bx_market_entry_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_market_entry_edit', 'title', 2147483647, 1, 3),
('bx_market_entry_edit', 'name', 2147483647, 1, 4),
('bx_market_entry_edit', 'cat', 2147483647, 1, 5),
('bx_market_entry_edit', 'text', 2147483647, 1, 6),
('bx_market_entry_edit', 'pictures', 2147483647, 1, 7),
('bx_market_entry_edit', 'files', 2147483647, 1, 8),
('bx_market_entry_edit', 'header_beg_single', 2147483647, 1, 9),
('bx_market_entry_edit', 'price_single', 2147483647, 1, 10),
('bx_market_entry_edit', 'header_end_single', 2147483647, 1, 11),
('bx_market_entry_edit', 'header_beg_recurring', 2147483647, 1, 12),
('bx_market_entry_edit', 'duration_recurring', 2147483647, 1, 13),
('bx_market_entry_edit', 'price_recurring', 2147483647, 1, 14),
('bx_market_entry_edit', 'header_end_recurring', 2147483647, 1, 15),
('bx_market_entry_edit', 'header_beg_privacy', 2147483647, 1, 16),
('bx_market_entry_edit', 'allow_view_to', 2147483647, 1, 17),
('bx_market_entry_edit', 'allow_purchase_to', 2147483647, 1, 18),
('bx_market_entry_edit', 'allow_comment_to', 2147483647, 1, 19),
('bx_market_entry_edit', 'allow_vote_to', 2147483647, 1, 20),
('bx_market_entry_edit', 'header_end_privacy', 2147483647, 1, 21),
('bx_market_entry_edit', 'notes', 2147483647, 1, 22),
('bx_market_entry_edit', 'location', 2147483647, 1, 23),
('bx_market_entry_edit', 'do_submit', 2147483647, 1, 24),

('bx_market_entry_view', 'notes', 2147483647, 0, 0),
('bx_market_entry_view', 'location', 2147483647, 0, 0),
('bx_market_entry_view', 'cat', 2147483647, 1, 1),
('bx_market_entry_view', 'header_beg_single', 2147483647, 0, 0),
('bx_market_entry_view', 'price_single', 2147483647, 0, 0),
('bx_market_entry_view', 'header_end_single', 2147483647, 0, 0),
('bx_market_entry_view', 'header_beg_recurring', 2147483647, 0, 0),
('bx_market_entry_view', 'duration_recurring', 2147483647, 0, 0),
('bx_market_entry_view', 'price_recurring', 2147483647, 0, 0),
('bx_market_entry_view', 'header_end_recurring', 2147483647, 0, 0),
('bx_market_entry_view', 'pictures', 2147483647, 0, 0),
('bx_market_entry_view', 'files', 2147483647, 0, 0),
('bx_market_entry_view', 'delete_confirm', 2147483647, 0, 0),
('bx_market_entry_view', 'text', 2147483647, 0, 0),
('bx_market_entry_view', 'do_publish', 2147483647, 0, 0),
('bx_market_entry_view', 'title', 2147483647, 0, 0),
('bx_market_entry_view', 'name', 2147483647, 0, 0),
('bx_market_entry_view', 'do_submit', 2147483647, 0, 0),
('bx_market_entry_view', 'header_beg_privacy', 2147483647, 0, 0),
('bx_market_entry_view', 'allow_view_to', 2147483647, 0, 0),
('bx_market_entry_view', 'allow_purchase_to', 2147483647, 0, 0),
('bx_market_entry_view', 'allow_comment_to', 2147483647, 0, 0),
('bx_market_entry_view', 'allow_vote_to', 2147483647, 0, 0),
('bx_market_entry_view', 'header_end_privacy', 2147483647, 0, 0),
('bx_market_entry_view', 'added', 2147483647, 1, 2),
('bx_market_entry_view', 'changed', 2147483647, 1, 3),

('bx_market_entry_view_full', 'cat', 2147483647, 1, 1),
('bx_market_entry_view_full', 'added', 2147483647, 1, 2),
('bx_market_entry_view_full', 'changed', 2147483647, 1, 3),
('bx_market_entry_view_full', 'notes', 2147483647, 1, 4);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_market_cats', '_bx_market_pre_lists_cats', 'bx_market', '0'),
('bx_market_prices', '_bx_market_pre_lists_prices', 'bx_market', '0'),
('bx_market_durations', '_bx_market_pre_lists_durations', 'bx_market', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_market_cats', '', 0, '_sys_please_select', ''),
('bx_market_cats', '1', 1, '_bx_market_cat_administration', ''),
('bx_market_cats', '2', 2, '_bx_market_cat_adult', ''),
('bx_market_cats', '3', 3, '_bx_market_cat_advertisement', ''),
('bx_market_cats', '4', 4, '_bx_market_cat_affiliates', ''),
('bx_market_cats', '5', 5, '_bx_market_cat_authentication', ''),
('bx_market_cats', '6', 6, '_bx_market_cat_calendars', ''),
('bx_market_cats', '7', 7, '_bx_market_cat_communication', ''),
('bx_market_cats', '8', 8, '_bx_market_cat_content', ''),
('bx_market_cats', '9', 9, '_bx_market_cat_conversion', ''),
('bx_market_cats', '10', 10, '_bx_market_cat_core_changes', ''),
('bx_market_cats', '11', 11, '_bx_market_cat_dating', ''),
('bx_market_cats', '12', 12, '_bx_market_cat_documentation', ''),
('bx_market_cats', '13', 13, '_bx_market_cat_ecommerce', ''),
('bx_market_cats', '14', 14, '_bx_market_cat_events', ''),
('bx_market_cats', '15', 15, '_bx_market_cat_feedback', ''),
('bx_market_cats', '16', 16, '_bx_market_cat_flash', ''),
('bx_market_cats', '17', 17, '_bx_market_cat_games', ''),
('bx_market_cats', '18', 18, '_bx_market_cat_graphics', ''),
('bx_market_cats', '19', 19, '_bx_market_cat_hosting', ''),
('bx_market_cats', '20', 20, '_bx_market_cat_integrations', ''),
('bx_market_cats', '21', 21, '_bx_market_cat_location', ''),
('bx_market_cats', '22', 22, '_bx_market_cat_management', ''),
('bx_market_cats', '23', 23, '_bx_market_cat_mobile', ''),
('bx_market_cats', '24', 24, '_bx_market_cat_multimedia', ''),
('bx_market_cats', '25', 25, '_bx_market_cat_music', ''),
('bx_market_cats', '26', 26, '_bx_market_cat_navigation', ''),
('bx_market_cats', '27', 27, '_bx_market_cat_other', ''),
('bx_market_cats', '28', 28, '_bx_market_cat_performance', ''),
('bx_market_cats', '29', 29, '_bx_market_cat_photos', ''),
('bx_market_cats', '30', 30, '_bx_market_cat_search', ''),
('bx_market_cats', '31', 31, '_bx_market_cat_security', ''),
('bx_market_cats', '32', 32, '_bx_market_cat_services', ''),
('bx_market_cats', '33', 33, '_bx_market_cat_social', ''),
('bx_market_cats', '34', 34, '_bx_market_cat_spam_prevention', ''),
('bx_market_cats', '35', 35, '_bx_market_cat_statistics', ''),
('bx_market_cats', '36', 36, '_bx_market_cat_templates', ''),
('bx_market_cats', '37', 37, '_bx_market_cat_tools', ''),
('bx_market_cats', '38', 38, '_bx_market_cat_translations', ''),
('bx_market_cats', '39', 39, '_bx_market_cat_video', ''),

('bx_market_prices', '0', 0, '_bx_market_cat_price_0', ''),
('bx_market_prices', '1', 1, '_bx_market_cat_price_1', ''),
('bx_market_prices', '2', 2, '_bx_market_cat_price_2', ''),
('bx_market_prices', '3', 3, '_bx_market_cat_price_3', ''),
('bx_market_prices', '4', 4, '_bx_market_cat_price_4', ''),
('bx_market_prices', '5', 5, '_bx_market_cat_price_5', ''),

('bx_market_durations', 'week', 1, '_bx_market_cat_duration_week', ''),
('bx_market_durations', 'month', 2, '_bx_market_cat_duration_month', ''),
('bx_market_durations', 'year', 3, '_bx_market_cat_duration_year', '');

-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_market', 'bx_market_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-product&id={object_id}', '', 'bx_market_products', 'id', 'author', 'title', 'comments', 'BxMarketCmts', 'modules/boonex/market/classes/BxMarketCmts.php');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_market', 'bx_market_votes', 'bx_market_votes_track', '604800', '1', '5', '0', '1', 'bx_market_products', 'id', 'author', 'rate', 'votes', 'BxMarketVote', 'modules/boonex/market/classes/BxMarketVote.php');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_market', 'bx_market_reports', 'bx_market_reports_track', '1', 'page.php?i=view-product&id={object_id}', 'bx_market_products', 'id', 'author', 'reports', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_market', 'bx_market_views_track', '86400', '1', 'bx_market_products', 'id', 'views', '', '');

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_market', '_bx_market', '_bx_market', 'bx_market@modules/boonex/market/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_market', '{url_studio}module.php?name=bx_market', '', 'bx_market@modules/boonex/market/|std-wi.png', '_bx_market', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
