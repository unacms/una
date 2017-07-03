-- TABLES
CREATE TABLE IF NOT EXISTS `bx_persons_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_persons_meta_locations` (
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


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_persons_gallery';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_persons_gallery', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"2000";}' WHERE `transcoder_object`='bx_persons_cover';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_persons_gallery';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_persons_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_person' AND `name`='allow_view_to';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name`='location';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_person_add', 'bx_person_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_add', 'picture', 2147483647, 1, 1),
('bx_person_add', 'fullname', 2147483647, 1, 2),
('bx_person_add', 'description', 2147483647, 1, 3),
('bx_person_add', 'location', 2147483647, 1, 4),
('bx_person_add', 'allow_view_to', 2147483647, 1, 5),
('bx_person_add', 'do_submit', 2147483647, 1, 6),

('bx_person_edit', 'picture', 2147483647, 1, 1),
('bx_person_edit', 'fullname', 2147483647, 1, 2),
('bx_person_edit', 'description', 2147483647, 1, 3),
('bx_person_edit', 'location', 2147483647, 1, 4),
('bx_person_edit', 'allow_view_to', 2147483647, 1, 5),
('bx_person_edit', 'do_submit', 2147483647, 1, 6);
