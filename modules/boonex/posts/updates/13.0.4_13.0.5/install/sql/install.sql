-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object` IN ('bx_posts_miniature', 'bx_posts_miniature_photos');
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_miniature', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_posts_miniature_photos', 'bx_posts_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_posts_photos";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN ('bx_posts_miniature', 'bx_posts_miniature_photos');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_posts_miniature', 'Resize', 'a:1:{s:1:"w";s:3:"300";}', '0'),
('bx_posts_miniature_photos', 'Resize', 'a:4:{s:1:"w";s:3:"300";s:1:"h";s:3:"300";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- FORMS
UPDATE `sys_form_inputs` SET `values`='a:1:{s:14:"bx_posts_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_posts' AND `name`='covers';
UPDATE `sys_form_inputs` SET `values`='a:1:{s:21:"bx_posts_photos_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_posts' AND `name`='pictures';
UPDATE `sys_form_inputs` SET `values`='a:2:{s:21:"bx_posts_videos_html5";s:25:"_sys_uploader_html5_title";s:28:"bx_posts_videos_record_video";s:32:"_sys_uploader_record_video_title";}' WHERE `object`='bx_posts' AND `name`='videos';
UPDATE `sys_form_inputs` SET `values`='a:1:{s:21:"bx_posts_sounds_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_posts' AND `name`='sounds';
UPDATE `sys_form_inputs` SET `values`='a:1:{s:20:"bx_posts_files_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_posts' AND `name`='files';

UPDATE `sys_form_inputs` SET `db_pass`='DateTimeTs' WHERE `object`='bx_posts' AND `name`='published';
