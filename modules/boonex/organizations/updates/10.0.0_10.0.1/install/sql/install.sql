-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_organizations';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_organizations', 'bx_organizations', 'bx_organizations_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-organization-profile&id={object_id}', '', '', '', 'bx_organizations_data', 'id', 'author', 'org_name', 'comments', '', '');


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name`='bx_organizations';
INSERT INTO `sys_objects_view` (`name`, `module`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_organizations', 'bx_organizations', 'bx_organizations_views_track', '86400', '1', 'bx_organizations_data', 'id', 'author', 'views', '', '');


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`='bx_organizations';
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_organizations', 'bx_organizations_votes', 'bx_organizations_votes_track', '604800', '1', '1', '0', '1', 'bx_organizations_data', 'id', '', 'rate', 'votes', 'BxOrgsVote', 'modules/boonex/organizations/classes/BxOrgsVote.php');


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_organizations';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_organizations', 'bx_organizations', 'bx_organizations_scores', 'bx_organizations_scores_track', '604800', '0', 'bx_organizations_data', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_organizations';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_organizations', 'bx_organizations_favorites_track', '1', '1', '0', 'page.php?i=view-organization-profile&id={object_id}', 'bx_organizations_data', 'id', 'author', 'favorites', 'BxOrgsFavorite', 'modules/boonex/organizations/classes/BxOrgsFavorite.php');


-- FEATURED
DELETE FROM `sys_objects_feature` WHERE `name`='bx_organizations';
INSERT INTO `sys_objects_feature` (`name`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_organizations', '1', '1', 'page.php?i=view-organization-profile&id={object_id}', 'bx_organizations_data', 'id', 'author', 'featured', '', '');


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_organizations';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_organizations', 'bx_organizations_reports', 'bx_organizations_reports_track', '1', 'page.php?i=view-organization-profile&id={object_id}', 'bx_organizations_data', 'id', 'author', 'reports', 'BxOrgsReport', 'modules/boonex/organizations/classes/BxOrgsReport.php');
