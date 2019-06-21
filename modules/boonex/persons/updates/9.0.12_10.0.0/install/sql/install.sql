-- TABLES
ALTER TABLE `bx_persons_data` CHANGE `allow_view_to` `allow_view_to` VARCHAR(16) NOT NULL DEFAULT '3';


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_persons_avatar_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_avatar_big', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_persons_avatar_big';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_persons_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_avatar';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:3:"960";s:1:"h";s:3:"480";}' WHERE `transcoder_object`='bx_persons_cover';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name` IN ('allow_post_to', 'friends_count', 'followers_count');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'allow_post_to', 3, '', 0, 'custom', '_bx_persons_form_profile_input_sys_allow_post_to', '_bx_persons_form_profile_input_allow_post_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'friends_count', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_friends_count', '_bx_persons_form_profile_input_friends_count', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'followers_count', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_followers_count', '_bx_persons_form_profile_input_followers_count', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_inputs` SET `editable`='0' WHERE `object`='bx_person' AND `name` IN ('profile_email', 'profile_status', 'profile_ip');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_person_add', 'bx_person_edit') AND `input_name`='allow_post_to';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_add', 'allow_post_to', 2147483647, 1, 8),
('bx_person_edit', 'allow_post_to', 2147483647, 1, 8);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view' AND `input_name` IN ('friends_count', 'followers_count');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_view', 'friends_count', 2147483647, 1, 9),
('bx_person_view', 'followers_count', 2147483647, 1, 10);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view_full' AND `input_name`='description';


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_persons';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_persons', 'bx_persons', 'bx_persons_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-persons-profile&id={object_id}', '', '', '', 'bx_persons_data', 'id', 'author', 'fullname', 'comments', '', '');


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_view` (`name`, `module`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_persons', 'bx_persons', 'bx_persons_views_track', '86400', '1', 'bx_persons_data', 'id', 'author', 'views', '', '');


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_persons';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_persons', 'bx_persons_votes', 'bx_persons_votes_track', '604800', '1', '1', '0', '1', 'bx_persons_data', 'id', '', 'rate', 'votes', 'BxPersonsVote', 'modules/boonex/persons/classes/BxPersonsVote.php');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_persons', 'bx_persons', 'bx_persons_scores', 'bx_persons_scores_track', '604800', '0', 'bx_persons_data', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_persons', 'bx_persons_favorites_track', '1', '1', '0', 'page.php?i=view-persons-profile&id={object_id}', 'bx_persons_data', 'id', 'author', 'favorites', 'BxPersonsFavorite', 'modules/boonex/persons/classes/BxPersonsFavorite.php');


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_persons', '1', '1', 'page.php?i=view-persons-profile&id={object_id}', 'bx_persons_data', 'id', 'author', 'featured', '', '');


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_persons', 'bx_persons_reports', 'bx_persons_reports_track', '1', 'page.php?i=view-persons-profile&id={object_id}', 'bx_persons_data', 'id', 'author', 'reports', 'BxPersonsReport', 'modules/boonex/persons/classes/BxPersonsReport.php');
