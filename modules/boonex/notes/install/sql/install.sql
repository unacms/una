
-- TABLE: NOTES

CREATE TABLE IF NOT EXISTS `bx_notes_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `summary` text NOT NULL,
  PRIMARY KEY (`id`)
);

-- TABLE: STORAGES & TRANSCODERS

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

-- FORMS

INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes', 'bx_notes', '_bx_notes_form_note', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_notes_posts', 'id', '', '', 'do_submit', '', 0, 1, 'BxNotesFormNote', 'modules/boonex/notes/classes/BxNotesFormNote.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_notes', 'bx_notes_note_add', 'bx_notes', 0, '_bx_notes_form_note_display_add'),
('bx_notes', 'bx_notes_note_edit', 'bx_notes', 0, '_bx_notes_form_note_display_edit'),
('bx_notes', 'bx_notes_note_delete', 'bx_notes', 0, '_bx_notes_form_note_display_delete'),
('bx_notes', 'bx_notes_note_view', 'bx_notes', 1, '_bx_notes_form_note_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_notes', 'bx_notes', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_notes_form_note_input_sys_delete_confirm', '_bx_notes_form_note_input_delete_confirm', '_bx_notes_form_note_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_notes_form_note_input_delete_confirm_error', '', '', 1, 0),
('bx_notes', 'bx_notes', 'do_submit', '_bx_notes_form_note_input_do_submit', '', 0, 'submit', '_bx_notes_form_note_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_notes', 'bx_notes', 'title', '', '', 0, 'text', '_bx_notes_form_note_input_sys_title', '_bx_notes_form_note_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_notes_form_note_input_title_err', 'Xss', '', 1, 0),
('bx_notes', 'bx_notes', 'text', '', '', 0, 'textarea', '_bx_notes_form_note_input_sys_text', '_bx_notes_form_note_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_notes_form_note_input_text_err', 'XssHtml', '', 1, 0),
('bx_notes', 'bx_notes', 'summary', '', '', 0, 'textarea', '_bx_notes_form_note_input_sys_summary', '_bx_notes_form_note_input_summary', '_bx_notes_form_note_input_summary_info', 0, 0, 3, '', '', '', '', '', '', 'XssHtml', '', 1, 0);

-- STUDIO PAGE & WIDGET

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_notes', '_bx_notes', '_bx_notes', 'bx_notes@modules/boonex/notes/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_notes', '{url_studio}module.php?name=bx_notes', '', 'bx_notes@modules/boonex/notes/|std-wi.png', '_bx_notes', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

