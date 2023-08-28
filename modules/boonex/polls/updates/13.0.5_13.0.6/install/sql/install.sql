-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_polls_cover';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_polls' WHERE `Name` IN ('bx_polls', 'bx_polls_subentries', 'bx_polls_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_polls' WHERE `name`='bx_polls';
