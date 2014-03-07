
-- TABLE: entries

CREATE TABLE IF NOT EXISTS `bx_notes_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `summary` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
);

-- TABLE: storages & transcoders

CREATE TABLE IF NOT EXISTS `bx_notes_photos` (
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

CREATE TABLE IF NOT EXISTS `bx_notes_photos_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_notes_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_notes_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_notes_votes_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- TABLE: views

CREATE TABLE `bx_notes_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- FORMS

INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes', 'bx_notes', '_bx_notes_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_notes_posts', 'id', '', '', 'do_submit', '', 0, 1, 'BxNotesFormEntry', 'modules/boonex/notes/classes/BxNotesFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_notes', 'bx_notes_entry_add', 'bx_notes', 0, '_bx_notes_form_entry_display_add'),
('bx_notes', 'bx_notes_entry_edit', 'bx_notes', 0, '_bx_notes_form_entry_display_edit'),
('bx_notes', 'bx_notes_entry_delete', 'bx_notes', 0, '_bx_notes_form_entry_display_delete'),
('bx_notes', 'bx_notes_entry_view', 'bx_notes', 1, '_bx_notes_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_notes', 'bx_notes', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_notes_form_entry_input_sys_delete_confirm', '_bx_notes_form_entry_input_delete_confirm', '_bx_notes_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_notes_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_notes', 'bx_notes', 'do_submit', '_bx_notes_form_entry_input_do_submit', '', 0, 'submit', '_bx_notes_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_notes', 'bx_notes', 'title', '', '', 0, 'text', '_bx_notes_form_entry_input_sys_title', '_bx_notes_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_notes_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_notes', 'bx_notes', 'text', '', '', 0, 'textarea', '_bx_notes_form_entry_input_sys_text', '_bx_notes_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_notes_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_notes', 'bx_notes', 'summary', '', '', 0, 'textarea', '_bx_notes_form_entry_input_sys_summary', '_bx_notes_form_entry_input_summary', '_bx_notes_form_entry_input_summary_info', 0, 0, 3, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_notes', 'bx_notes', 'pictures', '', '', 0, 'files', '_bx_notes_form_entry_input_sys_pictures', '_bx_notes_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_notes', 'bx_notes', 'allow_view_to', '', '', 0, 'custom', '_bx_notes_form_entry_input_sys_allow_view_to', '_bx_notes_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_notes_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_notes_entry_add', 'title', 2147483647, 1, 2),
('bx_notes_entry_add', 'text', 2147483647, 1, 3),
('bx_notes_entry_add', 'pictures', 2147483647, 1, 4),
('bx_notes_entry_add', 'summary', 2147483647, 1, 5),
('bx_notes_entry_add', 'allow_view_to', 2147483647, 1, 6),
('bx_notes_entry_add', 'do_submit', 2147483647, 1, 7),

('bx_notes_entry_edit', 'delete_confirm', 2147483647, 0, 1),
('bx_notes_entry_edit', 'title', 2147483647, 1, 2),
('bx_notes_entry_edit', 'text', 2147483647, 1, 3),
('bx_notes_entry_edit', 'pictures', 2147483647, 1, 4),
('bx_notes_entry_edit', 'summary', 2147483647, 1, 5),
('bx_notes_entry_edit', 'allow_view_to', 2147483647, 1, 6),
('bx_notes_entry_edit', 'do_submit', 2147483647, 1, 7),

('bx_notes_entry_view', 'delete_confirm', 2147483647, 0, 0),
('bx_notes_entry_view', 'allow_view_to', 2147483647, 0, 0),
('bx_notes_entry_view', 'do_submit', 2147483647, 0, 0),
('bx_notes_entry_view', 'summary', 2147483647, 0, 0),
('bx_notes_entry_view', 'text', 2147483647, 0, 0),
('bx_notes_entry_view', 'title', 2147483647, 0, 0),
('bx_notes_entry_view', 'pictures', 2147483647, 0, 0),

('bx_notes_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_notes_entry_delete', 'do_submit', 2147483647, 1, 2),
('bx_notes_entry_delete', 'allow_view_to', 2147483647, 0, 0),
('bx_notes_entry_delete', 'pictures', 2147483647, 0, 0),
('bx_notes_entry_delete', 'summary', 2147483647, 0, 0),
('bx_notes_entry_delete', 'text', 2147483647, 0, 0),
('bx_notes_entry_delete', 'title', 2147483647, 0, 0);

-- STUDIO: page & widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_notes', '_bx_notes', '_bx_notes', 'bx_notes@modules/boonex/notes/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_notes', '{url_studio}module.php?name=bx_notes', '', 'bx_notes@modules/boonex/notes/|std-wi.png', '_bx_notes', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

