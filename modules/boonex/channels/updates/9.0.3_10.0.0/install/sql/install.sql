-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_channels_avatar_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_channels_avatar_big', 'bx_channels_pics_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_channels_pics";}', 'no', '1', '2592000', '0', '', '');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_channels_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_channels_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_channels_avatar';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_channels_avatar_big';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_channels_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0');


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_channels';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_channels', 'bx_channels', 'bx_cnl_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-channel-profile&id={object_id}', '', '', '', 'bx_cnl_data', 'id', 'author', 'channel_name', 'comments', '', '');


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name`='bx_channels';
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_channels', 'bx_cnl_views_track', '86400', '1', 'bx_cnl_data', 'id', 'author', 'views', '', '');


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_channels';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_channels', 'bx_cnl_votes', 'bx_cnl_votes_track', '604800', '1', '1', '0', '1', 'bx_cnl_data', 'id', 'author', 'rate', 'votes', '', '');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_channels';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_channels', 'bx_channels', 'bx_cnl_scores', 'bx_cnl_scores_track', '604800', '0', 'bx_cnl_data', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_channels';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_channels', 'bx_cnl_reports', 'bx_cnl_reports_track', '1', 'page.php?i=view-channel-profile&id={object_id}', 'bx_cnl_data', 'id', 'author', 'reports', '', '');


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_channels';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_channels', 'bx_cnl_favorites_track', '1', '1', '1', 'page.php?i=view-channel-profile&id={object_id}', 'bx_cnl_data', 'id', 'author', 'favorites', '', '');


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_channels';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_channels', '1', '1', 'page.php?i=view-channel-profile&id={object_id}', 'bx_cnl_data', 'id', 'author', 'featured', '', '');
