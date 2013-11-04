SET @sName = 'bx_timeline';

-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_events` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) unsigned NOT NULL default '0',
  `type` varchar(255) collate utf8_unicode_ci NOT NULL,
  `action` varchar(255) collate utf8_unicode_ci NOT NULL,
  `object_id` text collate utf8_unicode_ci NOT NULL,
  `object_privacy_view` int(11) NOT NULL default '3',
  `content` text collate utf8_unicode_ci NOT NULL,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `comments` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`owner_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_handlers` (
  `id` int(11) NOT NULL auto_increment,
  `type` enum('insert','update','delete') NOT NULL DEFAULT 'insert',
  `alert_unit` varchar(64) NOT NULL default '',
  `alert_action` varchar(64) NOT NULL default '',
  `content` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE `handler` (`alert_unit`, `alert_action`)
);

INSERT INTO `bx_timeline_handlers`(`type`, `alert_unit`, `alert_action`, `content`) VALUES
('insert', 'timeline_common_text', '', ''),
('insert', 'timeline_common_link', '', ''),
('insert', 'timeline_common_photos', '', ''),
('insert', 'timeline_common_sounds', '', ''),
('insert', 'timeline_common_videos', '', '');

-- TABLES: UPLOADERS
CREATE TABLE IF NOT EXISTS `bx_timeline_photos` (
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

CREATE TABLE IF NOT EXISTS `bx_timeline_photos_preview` (
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

CREATE TABLE IF NOT EXISTS `bx_timeline_photos2events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL DEFAULT '0',
  `photo_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `photo` (`event_id`, `photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- TABLES: COMMENTS
CREATE TABLE IF NOT EXISTS `bx_timeline_comments` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_comments_track` (
  `cmt_system_id` int(11) NOT NULL DEFAULT '0',
  `cmt_id` int(11) NOT NULL DEFAULT '0',
  `cmt_rate` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_rate_author_nip` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_rate_ts` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_system_id`,`cmt_id`,`cmt_rate_author_nip`)
);


-- Forms -> Text
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('mod_tml_text', @sName, '_bx_timeline_form_text', '', '', 'do_submit', 'bx_timeline_events', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('mod_tml_text_add', @sName, 'mod_tml_text', '_bx_timeline_form_text_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('mod_tml_text', @sName, 'type', 'text', '', 0, 'hidden', '_bx_timeline_form_text_input_sys_type', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('mod_tml_text', @sName, 'action', '', '', 0, 'hidden', '_bx_timeline_form_text_input_sys_action', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('mod_tml_text', @sName, 'owner_id', '0', '', 0, 'hidden', '_bx_timeline_form_text_input_sys_owner_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('mod_tml_text', @sName, 'content', '', '', 0, 'textarea', '_bx_timeline_form_text_input_sys_content', '_bx_timeline_form_text_input_content', '', 0, 0, 0, '', '', '', 'Avail', '', '_bx_timeline_form_text_input_err_content', 'Xss', '', 0, 0),
('mod_tml_text', @sName, 'do_submit', '_bx_timeline_form_text_input_do_submit', '', 0, 'submit', '_bx_timeline_form_text_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('mod_tml_text_add', 'type', 2147483647, 1, 1),
('mod_tml_text_add', 'action', 2147483647, 1, 2),
('mod_tml_text_add', 'owner_id', 2147483647, 1, 3),
('mod_tml_text_add', 'content', 2147483647, 1, 4),
('mod_tml_text_add', 'do_submit', 2147483647, 1, 5);

-- Forms -> Link
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('mod_tml_link', @sName, '_bx_timeline_form_link', '', '', 'do_submit', 'bx_timeline_events', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('mod_tml_link_add', @sName, 'mod_tml_link', '_bx_timeline_form_link_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('mod_tml_link', @sName, 'type', 'link', '', 0, 'hidden', '_bx_timeline_form_link_input_sys_type', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('mod_tml_link', @sName, 'action', '', '', 0, 'hidden', '_bx_timeline_form_link_input_sys_action', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('mod_tml_link', @sName, 'owner_id', '0', '', 0, 'hidden', '_bx_timeline_form_link_input_sys_owner_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('mod_tml_link', @sName, 'content', '', '', 0, 'text', '_bx_timeline_form_link_input_sys_content', '_bx_timeline_form_link_input_content', '', 0, 0, 0, '', '', '', 'Avail', '', '_bx_timeline_form_link_input_err_content', 'Xss', '', 0, 0),
('mod_tml_link', @sName, 'do_submit', '_bx_timeline_form_link_input_do_submit', '', 0, 'submit', '_bx_timeline_form_link_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('mod_tml_link_add', 'type', 2147483647, 1, 1),
('mod_tml_link_add', 'action', 2147483647, 1, 2),
('mod_tml_link_add', 'owner_id', 2147483647, 1, 3),
('mod_tml_link_add', 'content', 2147483647, 1, 4),
('mod_tml_link_add', 'do_submit', 2147483647, 1, 5);

-- Forms -> Photo
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('mod_tml_photo', @sName, '_bx_timeline_form_photo', '', '', 'do_submit', 'bx_timeline_events', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('mod_tml_photo_add', @sName, 'mod_tml_photo', '_bx_timeline_form_photo_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('mod_tml_photo', @sName, 'type', 'photo', '', 0, 'hidden', '_bx_timeline_form_photo_input_sys_type', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('mod_tml_photo', @sName, 'action', '', '', 0, 'hidden', '_bx_timeline_form_photo_input_sys_action', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('mod_tml_photo', @sName, 'owner_id', '0', '', 0, 'hidden', '_bx_timeline_form_photo_input_sys_owner_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('mod_tml_photo', @sName, 'content', '', '', 0, 'files', '_bx_timeline_form_photo_input_sys_content', '_bx_timeline_form_photo_input_content', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('mod_tml_photo', @sName, 'do_submit', '_bx_timeline_form_photo_input_do_submit', '', 0, 'submit', '_bx_timeline_form_photo_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('mod_tml_photo_add', 'type', 2147483647, 1, 1),
('mod_tml_photo_add', 'action', 2147483647, 1, 2),
('mod_tml_photo_add', 'owner_id', 2147483647, 1, 3),
('mod_tml_photo_add', 'content', 2147483647, 1, 4),
('mod_tml_photo_add', 'do_submit', 2147483647, 1, 5);

-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_timeline', '_bx_timeline', '_bx_timeline', 'bx_timeline@modules/boonex/timeline/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_timeline', '{url_studio}module.php?name=bx_timeline', '', 'bx_timeline@modules/boonex/timeline/|std-wi.png', '_bx_timeline', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
