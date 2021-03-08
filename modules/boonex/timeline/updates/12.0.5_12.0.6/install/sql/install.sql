SET @sName = 'bx_timeline';


-- TABLES
SET @iSystem = (SELECT `ID` FROM `sys_objects_cmts` WHERE `Name`='bx_convos' LIMIT 1);

DELETE FROM `te`
USING `bx_timeline_events` AS `te` INNER JOIN `sys_cmts_ids` AS `tc` ON `te`.`object_id`=`tc`.`id` AND `tc`.`system_id`=@iSystem
WHERE `te`.`type`='comment' AND `te`.`action`='added';
