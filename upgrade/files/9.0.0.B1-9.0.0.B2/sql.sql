

DELETE FROM `sys_email_templates` WHERE `Name` = 't_Account';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_system_account', 't_Account', '_sys_et_txt_subject_account', '_sys_et_txt_body_account');



SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

DELETE FROM `sys_options` WHERE `name` IN('sys_site_cover_home', 'sys_maps_api_key', 'site_tour_home', 'site_tour_studio', 'sys_account_activation_letter', 'sys_account_limit_profiles_number', 'enable_notification_pruning', 'enable_notification_account');

SET @iCategoryIdGeneral = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdGeneral, 'sys_maps_api_key', '_adm_stg_cpt_option_sys_maps_api_key', '', 'digit', '', '', '', 70);

SET @iCategoryIdSiteSettings = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteSettings, 'site_tour_home', '_adm_stg_cpt_option_site_tour_home', 'on', 'checkbox', '', '', '', 6),
(@iCategoryIdSiteSettings, 'site_tour_studio', '_adm_stg_cpt_option_site_tour_studio', 'on', 'checkbox', '', '', '', 7);

SET @iCategoryIdAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAccount, 'sys_account_activation_letter', '_adm_stg_cpt_option_sys_account_activation_letter', '', 'checkbox', '', '', '', 11),
(@iCategoryIdAccount, 'sys_account_limit_profiles_number', '_adm_stg_cpt_option_sys_account_limit_profiles_number', '0', 'digit', '', '', '', 20);

DELETE FROM `sys_options_categories` WHERE `name` = 'notifications';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'notifications', '_adm_stg_cpt_category_notifications', 0, 16);
SET @iCategoryIdNotifications = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdNotifications, 'enable_notification_pruning', '_adm_stg_cpt_option_enable_notification_pruning', 'on', 'checkbox', '', '', '', 1),
(@iCategoryIdNotifications, 'enable_notification_account', '_adm_stg_cpt_option_enable_notification_account', 'on', 'checkbox', '', '', '', 2);



CREATE TABLE IF NOT EXISTS `sys_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `sys_images_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);



DELETE FROM `sys_injections` WHERE `name` IN('sys_head', 'sys_body');
INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('sys_head', 0, 'injection_head', 'text', '', 0, 1),
('sys_body', 0, 'injection_footer', 'text', '', 0, 1);



DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_account';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_account', '0 0 * * *', 'BxDolCronAccount', 'inc/classes/BxDolCronAccount.php', '');



DELETE FROM `sys_objects_storage` WHERE `object` IN('sys_images_resized', 'sys_files');
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sys_images_resized', 'Local', '', 360, 2592000, 0, 'sys_images_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png,svg', '', 0, 0, 0, 0, 0, 0),
('sys_files', 'Local', '', 360, 2592000, 3, 'sys_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0);



UPDATE `sys_form_inputs` SET `attrs` = 'a:1:{s:12:"autocomplete";s:3:"off";}' WHERE `object` = 'sys_comment' AND `module` = 'system' AND `name` = 'cmt_text';

UPDATE `sys_objects_menu` SET `override_class_name` = 'BxTemplMenuProfileAdd' WHERE `object` = 'sys_add_profile';

UPDATE `sys_grid_fields` SET `width` = '30%' WHERE `object` = 'sys_studio_lang_keys' AND `name` = 'string';




DELETE FROM `sys_objects_grid` WHERE `object` IN('sys_studio_strg_files', 'sys_studio_strg_images');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_strg_files', 'Sql', 'SELECT * FROM `sys_files` WHERE 1 ', 'sys_files', 'id', '', '', '', 20, NULL, 'start', '', 'file_name,mime_type', '', 'auto', '', '', 'BxTemplStudioStoragesFiles', ''),
('sys_studio_strg_images', 'Sql', 'SELECT * FROM `sys_images` WHERE 1 ', 'sys_images', 'id', '', '', '', 20, NULL, 'start', '', 'file_name', '', 'auto', '', '', 'BxTemplStudioStoragesImages', '');

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_studio_strg_files', 'sys_studio_strg_images');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_strg_files', 'checkbox', '', '1%', 0, '', '', 1),
('sys_studio_strg_files', 'file_name', '_adm_strg_txt_file_name', '24%', 0, '25', '', 2),
('sys_studio_strg_files', 'path', '_adm_strg_txt_path', '25%', 0, '', '', 3),
('sys_studio_strg_files', 'mime_type', '_adm_strg_txt_mime_type', '15%', 0, '15', '', 4),
('sys_studio_strg_files', 'added', '_adm_strg_txt_added', '10%', 0, '10', '', 5),
('sys_studio_strg_files', 'actions', '', '25%', 0, '', '', 6),
('sys_studio_strg_images', 'checkbox', '', '1%', 0, '', '', 1),
('sys_studio_strg_images', 'file_name', '_adm_strg_txt_file_name', '24%', 0, '25', '', 2),
('sys_studio_strg_images', 'path', '_adm_strg_txt_path', '25%', 0, '', '', 3),
('sys_studio_strg_images', 'mime_type', '_adm_strg_txt_mime_type', '15%', 0, '15', '', 4),
('sys_studio_strg_images', 'added', '_adm_strg_txt_added', '10%', 0, '10', '', 5),
('sys_studio_strg_images', 'actions', '', '25%', 0, '', '', 6);

DELETE FROM `sys_grid_actions` WHERE `object` IN('sys_studio_strg_files', 'sys_studio_strg_images');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_strg_files', 'bulk', 'delete', '_adm_strg_btn_delete', '', 0, 1, 1),
('sys_studio_strg_files', 'single', 'download', '_adm_strg_btn_download', 'download', 1, 0, 1),
('sys_studio_strg_files', 'single', 'delete', '_adm_strg_btn_delete', 'remove', 1, 1, 2),
('sys_studio_strg_files', 'independent', 'add', '_adm_strg_btn_add', '', 0, 0, 1),
('sys_studio_strg_images', 'bulk', 'delete', '_adm_strg_btn_delete', '', 0, 1, 1),
('sys_studio_strg_images', 'single', 'download', '_adm_strg_btn_download', 'download', 1, 0, 1),
('sys_studio_strg_images', 'single', 'resize', '_adm_strg_btn_resize', 'compress', 1, 0, 2),
('sys_studio_strg_images', 'single', 'delete', '_adm_strg_btn_delete', 'remove', 1, 1, 3),
('sys_studio_strg_images', 'independent', 'add', '_adm_strg_btn_add', '', 0, 0, 1);



DELETE FROM `sys_objects_transcoder` WHERE `object` = 'sys_image_resize';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('sys_image_resize', 'sys_images_resized', 'Storage', 'a:2:{s:6:"object";s:10:"sys_images";s:14:"disable_retina";b:1;}', 'no', '0', '0', '0', '', '');

UPDATE `sys_objects_transcoder` SET `storage_object` = 'sys_images_resized' WHERE `storage_object` = 'sys_images' AND `object` IN('sys_icon_apple', 'sys_icon_facebook', 'sys_icon_favicon', 'sys_cover', 'sys_builder_page_preview', 'sys_builder_page_embed', 'sys_custom_images');



DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` = 'sys_image_resize';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('sys_image_resize', 'ResizeVar', '', '0');



DELETE FROM `sys_pages_layouts` WHERE `name` IN('topbottom_area_bar_right', 'topbottom_area_bar_left');

SET @iMaxId = (SELECT MAX(`id`) FROM `sys_pages_layouts`);
UPDATE `sys_pages_layouts` SET `id` = @iMaxId + 1 WHERE `id` = 12;
UPDATE `sys_pages_layouts` SET `id` = @iMaxId + 2 WHERE `id` = 13;

INSERT INTO `sys_pages_layouts` (`id`, `name`, `icon`, `title`, `template`, `cells_number`) VALUES
(12, 'topbottom_area_bar_right', 'layout_topbottom_area_bar_right.png', '_sys_layout_topbottom_area_bar_right', 'layout_topbottom_area_bar_right.html', 4),
(13, 'topbottom_area_bar_left', 'layout_topbottom_area_bar_left.png', '_sys_layout_topbottom_area_bar_left', 'layout_topbottom_area_bar_left.html', 4);



DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'storages';

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'storages', '_adm_page_cpt_storages', '_adm_page_cpt_storages', 'pi-storages.png');
SET @iIdManagerStorages = LAST_INSERT_ID();

SET @iIdHome = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdManagerStorages, 'system', '{url_studio}storages.php', '', 'wi-storages.png', '_adm_wgt_cpt_storages', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 1);


-- last step is to update current version


UPDATE `sys_modules` SET `version` = '9.0.0.B2' WHERE `version` = '9.0.0.B1' AND `name` = 'system';

