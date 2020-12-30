-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:4:{s:1:"w";s:3:"600";s:1:"h";s:3:"600";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}' WHERE `transcoder_object`='bx_ads_gallery_photos' AND `filter`='Resize';
UPDATE `sys_objects_transcoder` SET `ts`=UNIX_TIMESTAMP() WHERE `object`='bx_ads_gallery_photos';

UPDATE `sys_transcoder_filters` SET `filter_params`='a:4:{s:1:"w";s:3:"600";s:1:"h";s:3:"600";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}' WHERE `transcoder_object`='bx_ads_gallery_files' AND `filter`='Resize';
UPDATE `sys_objects_transcoder` SET `ts`=UNIX_TIMESTAMP() WHERE `object`='bx_ads_gallery_files';

-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:2:{i:0;s:19:"bx_ads_videos_html5";i:1;s:26:"bx_ads_videos_record_video";}', `values`='a:3:{s:20:"bx_ads_videos_simple";s:26:"_sys_uploader_simple_title";s:19:"bx_ads_videos_html5";s:25:"_sys_uploader_html5_title";s:26:"bx_ads_videos_record_video";s:32:"_sys_uploader_record_video_title";}' WHERE `object`='bx_ads' AND `name`='videos';


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_ads' WHERE `name`='bx_ads';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_ads' WHERE `name`='bx_ads';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_ads' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
