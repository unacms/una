-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_convos';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `ObjectScore`, `ObjectReport`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_convos', 'bx_convos', 'bx_convos_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-convo&id={object_id}', '', '', '', 'bx_convos_conversations', 'id', 'author', 'text', 'comments', 'BxCnvCmts', 'modules/boonex/convos/classes/BxCnvCmts.php');


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name`='bx_convos';
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_convos', 'bx_convos_views_track', '86400', '1', 'bx_convos_conversations', 'id', 'author', 'views', '', '');
