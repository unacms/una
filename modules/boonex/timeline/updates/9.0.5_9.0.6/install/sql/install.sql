SET @sName = 'bx_timeline';


UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = @sName LIMIT 1;


-- STORAGES, TRANSCODERS, UPLOADERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_timeline_photos_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_photos_big', 'bx_timeline_photos_processed', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_photos";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_timeline_photos_big' AND `filter`='Resize';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('bx_timeline_photos_big', 'Resize', 'a:2:{s:1:"w";s:4:"1280";s:1:"h";s:4:"1280";}', '0');
