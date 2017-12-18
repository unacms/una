SET @sName = 'bx_forum';


UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = @sName LIMIT 1;


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_forum_gallery', 'bx_forum_cover');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_gallery', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_forum_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_forum_cover', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_forum_files";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_forum_gallery', 'bx_forum_cover');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_forum_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_forum_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0');
