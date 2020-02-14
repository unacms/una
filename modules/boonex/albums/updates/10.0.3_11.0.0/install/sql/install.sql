-- TABLES
ALTER TABLE `bx_albums_files` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_albums_photos_resized` CHANGE `size` `size` bigint(20) NOT NULL;


-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:1:{i:0;s:15:"bx_albums_html5";}' WHERE `object`='bx_albums' AND `name`='pictures';
