-- TABLES
CREATE TABLE IF NOT EXISTS `bx_groups_cmts` (
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


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_groups_gallery';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_groups_gallery', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"2000";}' WHERE `transcoder_object`='bx_groups_cover';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_groups_gallery';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_groups_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_group' AND `name`='allow_view_to';
UPDATE `sys_form_inputs` SET `html`='2', `db_pass`='XssHtml' WHERE `object`='bx_group' AND `name`='group_desc';
