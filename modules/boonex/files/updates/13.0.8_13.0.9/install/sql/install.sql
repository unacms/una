-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_files' WHERE `Name` IN ('bx_files', 'bx_files_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_files' WHERE `name`='bx_files';
