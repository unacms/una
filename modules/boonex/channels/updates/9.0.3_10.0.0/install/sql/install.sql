-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_channels_avatar_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_channels_avatar_big', 'bx_channels_pics_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_channels_pics";}', 'no', '1', '2592000', '0', '', '');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_channels_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_channels_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_channels_avatar';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_channels_avatar_big';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_channels_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0');
