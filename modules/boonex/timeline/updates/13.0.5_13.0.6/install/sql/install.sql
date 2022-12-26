SET @sName = 'bx_timeline';


-- STORAGES, TRANSCODERS, UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_timeline_simple_photo', 'bx_timeline_simple_video', 'bx_timeline_simple_file');


-- FORMS
UPDATE `sys_form_inputs` SET `db_pass`='DateTimeTs' WHERE `object`='bx_timeline_post' AND `name`='date';
UPDATE `sys_form_inputs` SET `db_pass`='DateTimeTs' WHERE `object`='bx_timeline_post' AND `name`='published';

UPDATE `sys_form_inputs` SET `values`='a:1:{s:23:"bx_timeline_html5_photo";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_timeline_post' AND `name`='photo';
UPDATE `sys_form_inputs` SET `values`='a:2:{s:23:"bx_timeline_html5_video";s:25:"_sys_uploader_html5_title";s:24:"bx_timeline_record_video";s:32:"_sys_uploader_record_video_title";}' WHERE `object`='bx_timeline_post' AND `name`='video';
UPDATE `sys_form_inputs` SET `values`='a:1:{s:22:"bx_timeline_html5_file";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_timeline_post' AND `name`='file';
