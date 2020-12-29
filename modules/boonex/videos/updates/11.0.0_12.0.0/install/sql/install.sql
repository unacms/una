-- TABLES
CREATE TABLE IF NOT EXISTS `bx_videos_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);


-- FORMS
UPDATE `sys_form_inputs` SET `value`='a:2:{i:0;s:15:"bx_videos_html5";i:1;s:22:"bx_videos_record_video";}', `values`='a:3:{s:16:"bx_videos_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_videos_html5";s:25:"_sys_uploader_html5_title";s:22:"bx_videos_record_video";s:32:"_sys_uploader_record_video_title";}' WHERE `object`='bx_videos' AND `name`='videos';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_videos' AND `name`='multicat';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_videos', 'bx_videos', 'multicat', '', '', 0, 'custom', '_bx_videos_form_entry_input_sys_multicat', '_bx_videos_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_videos_form_entry_input_multicat_err', 'Xss', '', 1, 0);


-- REPORTS
UPDATE `sys_objects_report` SET `module`='bx_videos' WHERE `name`='bx_videos';


-- FAVORITES
UPDATE `sys_objects_favorite` SET `table_lists`='bx_videos_favorites_lists' WHERE `name`='bx_videos';


-- FEATURED
UPDATE `sys_objects_feature` SET `module`='bx_videos' WHERE `name`='bx_videos';


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='bx_videos' LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='content' WHERE `page_id`=@iPageId;
