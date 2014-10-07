UPDATE `sys_objects_cmts` SET `BaseUrl`='page.php?i=timeline-item&id={object_id}' WHERE `Name`='bx_timeline' LIMIT 1;

DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'bx_timeline_cmts';
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES 
('bx_timeline_cmts', '_bx_timeline_cmts', 'BxTimelineCmtsSearchResult', 'modules/boonex/timeline/classes/BxTimelineCmtsSearchResult.php');
