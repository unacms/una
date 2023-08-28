-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `params`='' WHERE `object`='bx_videos_videos';

UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_videos_cover';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_videos' WHERE `Name` IN ('bx_videos', 'bx_videos_stars', 'bx_videos_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_videos' WHERE `name`='bx_videos';
