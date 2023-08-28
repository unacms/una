-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_spaces_cover';


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_spaces' WHERE `name`='bx_spaces';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_spaces' WHERE `Name`='bx_spaces';
