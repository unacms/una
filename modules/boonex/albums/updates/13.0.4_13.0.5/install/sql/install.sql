-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `params`='' WHERE `object`='bx_albums_files';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_albums' WHERE `Name` IN ('bx_albums', 'bx_albums_media', 'bx_albums_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_albums' WHERE `name` IN ('bx_albums', 'bx_albums_media');
