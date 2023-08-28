-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_tasks_cover';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_tasks_gallery_photos';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_tasks' WHERE `Name` IN ('bx_tasks', 'bx_tasks_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_tasks' WHERE `name`='bx_tasks';
