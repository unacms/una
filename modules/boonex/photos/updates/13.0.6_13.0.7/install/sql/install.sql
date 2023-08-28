-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_photos_cover';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_photos' WHERE `Name` IN ('bx_photos', 'bx_photos_stars', 'bx_photos_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_photos' WHERE `name`='bx_photos';
