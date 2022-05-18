SET @sName = 'bx_timeline';


-- TABLES
DROP TABLE IF EXISTS `bx_timeline_cache`;


-- FORMS
UPDATE `sys_form_inputs` SET `value`='1' WHERE `object`='bx_timeline_post' AND `name`='object_cf';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `ClassName`='BxTimelineCmts', `ClassFile`='modules/boonex/timeline/classes/BxTimelineCmts.php' WHERE `Name`='bx_timeline';
