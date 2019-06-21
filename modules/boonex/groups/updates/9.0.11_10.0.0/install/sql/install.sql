-- TABLES
ALTER TABLE `bx_groups_data` CHANGE `allow_view_to` `allow_view_to` varchar(16) NOT NULL DEFAULT '3';


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_groups_avatar_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_avatar_big', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_groups_avatar_big';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_groups_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_groups_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_groups_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_groups_avatar';

-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_group' AND `name` IN ('allow_post_to', 'labels');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_group', 'bx_groups', 'allow_post_to', 3, '', 0, 'custom', '_bx_groups_form_profile_input_sys_allow_post_to', '_bx_groups_form_profile_input_allow_post_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_group', 'bx_groups', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_group_add', 'bx_group_edit') AND `input_name`='allow_post_to';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_group_add', 'allow_post_to', 2147483647, 1, 8),
('bx_group_edit', 'allow_post_to', 2147483647, 1, 7);


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_groups';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_groups', 'bx_groups', 'bx_groups_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-group-profile&id={object_id}', '', '', '', 'bx_groups_data', 'id', 'author', 'group_name', 'comments', '', '');


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name`='bx_groups';
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_groups', 'bx_groups_views_track', '86400', '1', 'bx_groups_data', 'id', 'author', 'views', '', '');


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_groups';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_groups', 'bx_groups_votes', 'bx_groups_votes_track', '604800', '1', '1', '0', '1', 'bx_groups_data', 'id', 'author', 'rate', 'votes', '', '');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_groups';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_groups', 'bx_groups', 'bx_groups_scores', 'bx_groups_scores_track', '604800', '0', 'bx_groups_data', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_groups';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_groups', 'bx_groups_reports', 'bx_groups_reports_track', '1', 'page.php?i=view-group-profile&id={object_id}', 'bx_groups_data', 'id', 'author', 'reports', '', '');


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_groups';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_groups', 'bx_groups_favorites_track', '1', '1', '1', 'page.php?i=view-group-profile&id={object_id}', 'bx_groups_data', 'id', 'author', 'favorites', '', '');


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_groups';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_groups', '1', '1', 'page.php?i=view-group-profile&id={object_id}', 'bx_groups_data', 'id', 'author', 'featured', '', '');
