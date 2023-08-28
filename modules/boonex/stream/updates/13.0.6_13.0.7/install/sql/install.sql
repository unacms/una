-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_stream_cover';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_stream' WHERE `Name` IN ('bx_stream', 'bx_stream_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_stream' WHERE `name`='bx_stream';
