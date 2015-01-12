DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_posts_gallery' LIMIT 1;
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_posts_gallery', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_posts_files";}', 'no', '1', '2592000', '0');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_posts_gallery' LIMIT 1;
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_posts_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


UPDATE `sys_objects_cmts` SET `IsRatable`='1' WHERE `Name`='bx_posts' LIMIT 1;