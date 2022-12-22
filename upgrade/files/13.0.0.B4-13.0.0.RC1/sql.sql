
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Options: Hidden

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_files_ext_images', '_adm_stg_cpt_option_sys_files_ext_images', 'jpg,jpeg,jpe,gif,png,webp', 'digit', '', '', '', '', 200),
(@iCategoryIdHidden, 'sys_files_ext_video', '_adm_stg_cpt_option_sys_files_ext_video', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc,ts', 'digit', '', '', '', '', 210),
(@iCategoryIdHidden, 'sys_files_ext_audio', '_adm_stg_cpt_option_sys_files_ext_audio', 'mp3,m4a,m4b,wma,wav,3gp', 'digit', '', '', '', '', 220),
(@iCategoryIdHidden, 'sys_files_ext_imagevideo', '_adm_stg_cpt_option_sys_files_ext_imagevideo', 'jpg,jpeg,jpe,gif,png,svg,webp,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc,ts', 'digit', '', '', '', '', 230),
(@iCategoryIdHidden, 'sys_files_ext_dangerous', '_adm_stg_cpt_option_sys_files_ext_dangerous', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 'digit', '', '', '', '', 240);

-- Options: System

SET @iCategoryIdSystem = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');

UPDATE `sys_options` SET `order` = 15 WHERE `name` = 'sys_site_icon';

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSystem, 'sys_site_icon_svg', '', '0', 'digit', '', '', '', 16),
(@iCategoryIdSystem, 'sys_site_icon_apple', '', '0', 'digit', '', '', '', 17),
(@iCategoryIdSystem, 'sys_site_icon_android', '', '0', 'digit', '', '', '', 18),
(@iCategoryIdSystem, 'sys_site_icon_android_splash', '', '0', 'digit', '', '', '', 19);

UPDATE `sys_options` SET `order` = 22 WHERE `name` = 'sys_site_logo_aspect_ratio';

-- Options: Langs

DELETE FROM `sys_options` WHERE `name` IN('sys_format_input_date', 'sys_format_input_datetime');

-- Options: Security

SET @iCategoryIdSec = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'security');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSec, 'sys_confirmation_before_redirect', '_adm_stg_cpt_option_sys_confirmation_before_redirect', 'on', 'checkbox', '', '', '', 31);

-- Options: Site Settings

SET @iCategoryIdSite = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSite, 'sys_create_post_form_preloading_list', '_adm_stg_cpt_option_sys_create_post_form_preloading_list', '', 'list', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:44:"get_options_create_post_form_preloading_list";s:5:"class";s:13:"TemplServices";}', '', '', 80);



-- ACL

UPDATE `sys_acl_actions` SET `DisabledForLevels` = 0 WHERE `Module` = 'system' AND `Name` = 'comments post';



-- Storage

UPDATE `sys_objects_storage` SET `ext_allow` = '{image},svg' WHERE `ext_allow` = 'jpg,jpeg,jpe,gif,png,svg';
UPDATE `sys_objects_storage` SET `ext_allow` = '{image}' WHERE `ext_allow` = 'jpg,jpeg,jpe,gif,png';

UPDATE `sys_objects_storage` SET `ext_deny` = '{dangerous}' WHERE `ext_deny` = 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf';
UPDATE `sys_objects_storage` SET `ext_deny` = 'jpg,jpeg,jpe,gif,png,{dangerous}' WHERE `ext_deny` = 'jpg,jpeg,jpe,gif,png,action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf';

UPDATE `sys_objects_storage` SET `ext_allow` = '{imagevideo}' WHERE `ext_allow` = 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc';
UPDATE `sys_objects_storage` SET `ext_allow` = '{imagevideo}' WHERE `ext_allow` = 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc,ts';

UPDATE `sys_objects_storage` SET `ext_allow` = '{video}' WHERE `ext_allow` = 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc';
UPDATE `sys_objects_storage` SET `ext_allow` = '{video}' WHERE `ext_allow` = 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc,ts';

UPDATE `sys_objects_storage` SET `ext_allow` = '{audio}' WHERE `ext_allow` = 'mp3,m4a,m4b,wma,wav,3gp';



-- Uploaders

DELETE FROM `sys_objects_uploader` WHERE `object` IN('sys_simple', 'sys_cmts_simple', 'sys_builder_page_simple');



-- Transcoders

INSERT IGNORE INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('sys_icon_android', 'sys_images_resized', 'Storage', 'a:2:{s:6:"object";s:10:"sys_images";s:14:"disable_retina";b:1;}', 'no', '0', '0', '0', '', ''),
('sys_icon_android_splash', 'sys_images_resized', 'Storage', 'a:2:{s:6:"object";s:10:"sys_images";s:14:"disable_retina";b:1;}', 'no', '0', '0', '0', '', '');

UPDATE `sys_objects_transcoder` SET `ts` = UNIX_TIMESTAMP() WHERE `object` = 'sys_icon_apple';

UPDATE `sys_transcoder_filters` SET `filter_params` = 'a:3:{s:1:"w";s:3:"180";s:1:"h";s:3:"180";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object` = 'sys_icon_apple';

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('sys_icon_android', 'sys_icon_android_splash');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('sys_icon_android', 'Resize', 'a:3:{s:1:"w";s:3:"192";s:1:"h";s:3:"192";s:13:"square_resize";s:1:"1";}', '0'),
('sys_icon_android_splash', 'Resize', 'a:3:{s:1:"w";s:3:"512";s:1:"h";s:3:"512";s:13:"square_resize";s:1:"1";}', '0');



-- Pages

INSERT IGNORE INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `sticky_columns`) VALUES
('sys_redirect', 'redirect', '', '_sys_page_title_redirect', 'system', 0, 18, '', 2147483647, 1, 'page.php?i=redirect', '', '', '', 0, 1, 0, '', '', 0);


DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_redirect' AND `title` = '_sys_page_block_title_redirect';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_redirect', 1, 'system', '', '_sys_page_block_title_redirect', 11, 0, 0, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:8:\"redirect\";s:5:\"class\";s:13:\"TemplServices\";}', 0, 1, 1, 1);



-- Preloader

DELETE FROM `sys_preloader` WHERE `content` = '_sys_redirect_confirmation';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_translation', '_sys_redirect_confirmation', 1, 5);



-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-RC1' WHERE (`version` = '13.0.0.B4' OR `version` = '13.0.0-B4') AND `name` = 'system';

