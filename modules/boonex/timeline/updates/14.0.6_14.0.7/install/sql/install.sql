SET @sName = 'bx_timeline';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldAuthor`='object_owner_id' WHERE `Name`=@sName;


-- VIEWS
UPDATE `sys_objects_view` SET `trigger_field_author`='object_owner_id' WHERE `name`=@sName;


-- VOTES
UPDATE `sys_objects_vote` SET `TriggerFieldAuthor`='object_owner_id' WHERE `Name` IN (@sName, 'bx_timeline_reactions');


-- SCORES
UPDATE `sys_objects_score` SET `trigger_field_author`='object_owner_id' WHERE `name`=@sName;
