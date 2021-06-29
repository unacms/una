SET @sName = 'bx_forum';


-- STORAGES & TRANSCODERS
UPDATE `sys_objects_storage` SET `params`='a:1:{s:6:"fields";a:1:{s:10:"dimensions";s:17:"getFileDimensions";}}' WHERE `object`='bx_forum_videos';
