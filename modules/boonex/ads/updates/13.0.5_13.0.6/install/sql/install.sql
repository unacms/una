-- TABLES
CREATE TABLE IF NOT EXISTS `bx_ads_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- FORMS
UPDATE `sys_form_inputs` SET `values`='a:1:{s:12:"bx_ads_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_ads' AND `name`='covers';
UPDATE `sys_form_inputs` SET `values`='a:1:{s:19:"bx_ads_photos_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_ads' AND `name`='pictures';
UPDATE `sys_form_inputs` SET `values`='a:2:{s:19:"bx_ads_videos_html5";s:25:"_sys_uploader_html5_title";s:26:"bx_ads_videos_record_video";s:32:"_sys_uploader_record_video_title";}' WHERE `object`='bx_ads' AND `name`='videos';
UPDATE `sys_form_inputs` SET `values`='a:1:{s:18:"bx_ads_files_html5";s:25:"_sys_uploader_html5_title";}' WHERE `object`='bx_ads' AND `name`='files';
UPDATE `sys_form_inputs` SET `attrs`='', `checker_func`='Avail', `checker_error`='_bx_ads_form_entry_input_category_select_err', `db_pass`='Int' WHERE `object`='bx_ads' AND `name`='category_select';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_ads_entry_add' AND `input_name`='do_submit';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_add', 'do_submit', 2147483647, 1, 2);


-- FAFORITES
UPDATE `sys_objects_favorite` SET `table_lists`='bx_ads_favorites_lists' WHERE `name`='bx_ads';
