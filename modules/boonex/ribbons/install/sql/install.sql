SET @sName = 'bx_ribbons';

-- TABLE: data
CREATE TABLE `bx_ribbons_data` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `added` int(11) NOT NULL default '0',
  `changed` int(11) NOT NULL default '0',
  `thumb` int(11) NOT NULL,
  `title` text NOT NULL,
  `picture` int(11) NOT NULL,
  `text` text NOT NULL,
  `status` enum ('active', 'hidden') DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT INDEX `search_fields` (`title`, `text`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_ribbons_pictures` (
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

CREATE TABLE IF NOT EXISTS `bx_ribbons_pictures_resized` (
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

-- TABLE: profile binding
CREATE TABLE `bx_ribbons_profiles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) DEFAULT NULL,
  `ribbon_id` int(11) DEFAULT NULL,
  `added` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ribbons_profiles` (`profile_id`, `ribbon_id`)
);

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_ribbons', '_bx_ribbons', 'bx_ribbons@modules/boonex/ribbons/|std-icon.svg');

SET @iPageId = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`, `bookmark`) 
VALUES(@iPageId, @sName, '{url_studio}module.php?name=bx_ribbons', '', 'bx_ribbons@modules/boonex/ribbons/|std-icon.svg', '_bx_ribbons', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}', 0);

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);

INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder);

-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_ribbons_pictures', @sStorageEngine, '', 360, 2592000, 3, 'bx_ribbons_pictures', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_ribbons_pictures_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_ribbons_pictures_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_ribbons_pictures', 'bx_ribbons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_ribbons_pictures";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_ribbons_pictures', 'Resize', 'a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"0";}', '0');

-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) 
VALUES(@sName, @sName, '_bx_ribbons_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'do_submit', 'bx_ribbons_data', 'id', '', '', '', 0, 1, 'BxRibbonsFormEntry', 'modules/boonex/ribbons/classes/BxRibbonsFormEntry.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_ribbons_entry_add', @sName, @sName, '_bx_ribbons_form_entry_display_add', 0),
('bx_ribbons_entry_edit', @sName, @sName, '_bx_ribbons_form_entry_display_edit', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `unique`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'do_submit', '_bx_ribbons_form_entry_input_do_submit', '', 0, 'submit', '_bx_ribbons_form_entry_input_sys_do_submit', '', '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'title', '', '', 0, 'text', '_bx_ribbons_form_entry_input_sys_title', '_bx_ribbons_form_entry_input_title', '', 0, 0, 0, 2, '', '', '', '', '', '_bx_ribbons_form_entry_input_title_err', 'XssHtml', '', 1, 0),
(@sName, @sName, 'pictures', 'a:1:{i:0;s:9:"sys_html5";}', 'a:2:{s:10:"sys_simple";s:26:"_sys_uploader_simple_title";s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_ribbons_form_entry_input_sys_image', '_bx_ribbons_form_entry_input_image', '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'text', '', '', 0, 'textarea', '_bx_ribbons_form_entry_input_sys_text', '_bx_ribbons_form_entry_input_text', '', 0, 0, 0, 2, '', '', '', '', '', '_bx_ribbons_form_entry_input_text_err', 'XssHtml', '', 1, 0),
(@sName, @sName, 'cancel', '_bx_ribbons_form_entry_input_cancel', '', 0, 'button', '_bx_ribbons_form_entry_input_sys_cancel', '', '', 0, 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', 'Avail', '', '', '', '', 0, 0),
(@sName, @sName, 'controls', '', 'do_submit,cancel', 0, 'input_set', '', '', '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_ribbons_entry_add', 'title', 2147483647, 1, 1),
('bx_ribbons_entry_add', 'pictures', 2147483647, 1, 2),
('bx_ribbons_entry_add', 'text', 2147483647, 1, 3),
('bx_ribbons_entry_add', 'controls', 2147483647, 1, 4),
('bx_ribbons_entry_add', 'do_submit', 2147483647, 1, 5),
('bx_ribbons_entry_add', 'cancel', 2147483647, 1, 6),
('bx_ribbons_entry_edit', 'title', 2147483647, 1, 1),
('bx_ribbons_entry_edit', 'pictures', 2147483647, 1, 2),
('bx_ribbons_entry_edit', 'text', 2147483647, 1, 3),
('bx_ribbons_entry_edit', 'controls', 2147483647, 1, 4),
('bx_ribbons_entry_edit', 'do_submit', 2147483647, 1, 5),
('bx_ribbons_entry_edit', 'cancel', 2147483647, 1, 6);