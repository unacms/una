SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_hot_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_timeline_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- STORAGES, TRANSCODERS, UPLOADERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_timeline_photos_medium';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_photos_medium', 'bx_timeline_photos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_photos";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_timeline_photos_medium' AND `filter`='Resize';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('bx_timeline_photos_medium', 'Resize', 'a:3:{s:1:"w";s:3:"600";s:1:"h";s:3:"600";s:13:"square_resize";s:1:"1";}', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"300";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_timeline_photos_view' AND `filter`='Resize';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:4:"1200";s:1:"h";s:4:"1200";}' WHERE `transcoder_object`='bx_timeline_photos_big' AND `filter`='Resize';


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name` IN ('bx_timeline_post_add_profile', 'bx_timeline_post_edit');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_timeline_post_add_profile', @sName, 'bx_timeline_post', '_bx_timeline_form_post_display_add_profile', 0),
('bx_timeline_post_edit', @sName, 'bx_timeline_post', '_bx_timeline_form_post_display_edit', 0);

UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_timeline_post' AND `name`='owner_id';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_post' AND `name` IN ('date', 'controls', 'do_cancel');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_post', @sName, 'date', '', '', 0, 'datetime', '_bx_timeline_form_post_input_sys_date', '_bx_timeline_form_post_input_date', '_bx_timeline_form_post_input_date_info', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0),
('bx_timeline_post', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_timeline_post', @sName, 'do_cancel', '_bx_timeline_form_post_input_do_cancel', '', 0, 'button', '_bx_timeline_form_post_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:51:"{js_object_view}.editPostCancel(this, {content_id})";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_post_add', 'bx_timeline_post_add_public') AND `input_name`='date';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_add', 'date', 192, 1, 6),
('bx_timeline_post_add_public', 'date', 192, 1, 6);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_post_add_profile', 'bx_timeline_post_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_add_profile', 'type', 2147483647, 1, 1),
('bx_timeline_post_add_profile', 'action', 2147483647, 1, 2),
('bx_timeline_post_add_profile', 'owner_id', 2147483647, 1, 3),
('bx_timeline_post_add_profile', 'text', 2147483647, 1, 4),
('bx_timeline_post_add_profile', 'object_privacy_view', 2147483647, 1, 5),
('bx_timeline_post_add_profile', 'date', 192, 1, 6),
('bx_timeline_post_add_profile', 'location', 2147483647, 1, 7),
('bx_timeline_post_add_profile', 'link', 2147483647, 1, 8),
('bx_timeline_post_add_profile', 'photo', 2147483647, 1, 9),
('bx_timeline_post_add_profile', 'video', 2147483647, 1, 10),
('bx_timeline_post_add_profile', 'attachments', 2147483647, 1, 11),
('bx_timeline_post_add_profile', 'do_submit', 2147483647, 1, 12),

('bx_timeline_post_edit', 'type', 2147483647, 1, 1),
('bx_timeline_post_edit', 'action', 2147483647, 1, 2),
('bx_timeline_post_edit', 'owner_id', 2147483647, 1, 3),
('bx_timeline_post_edit', 'text', 2147483647, 1, 4),
('bx_timeline_post_edit', 'date', 192, 1, 5),
('bx_timeline_post_edit', 'location', 2147483647, 1, 6),
('bx_timeline_post_edit', 'controls', 2147483647, 1, 7),
('bx_timeline_post_edit', 'do_submit', 2147483647, 1, 8),
('bx_timeline_post_edit', 'do_cancel', 2147483647, 1, 9);
