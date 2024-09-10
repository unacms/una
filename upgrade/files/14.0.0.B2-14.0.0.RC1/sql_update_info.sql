
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_embed_microlink_key', '_adm_stg_cpt_option_sys_embed_microlink_key', '', 'digit', '', '', '', '', 300);

UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_site_title' WHERE `name` = 'site_title';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_site_email' WHERE `name` = 'site_email';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_site_email_notify' WHERE `name` = 'site_email_notify';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_site_tour_home' WHERE `name` = 'site_tour_home';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_site_tour_studio' WHERE `name` = 'site_tour_studio';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_autoupdate' WHERE `name` = 'sys_autoupdate';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_autoupdate_force_modified_files' WHERE `name` = 'sys_autoupdate_force_modified_files';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_smart_app_banner' WHERE `name` = 'smart_app_banner';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_smart_app_banner_ios_app_id' WHERE `name` = 'smart_app_banner_ios_app_id';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_per_page_search_keyword_single' WHERE `name` = 'sys_per_page_search_keyword_single';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_per_page_search_keyword_plural' WHERE `name` = 'sys_per_page_search_keyword_plural';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_live_search_limit' WHERE `name` = 'sys_live_search_limit';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_profiles_search_limit' WHERE `name` = 'sys_profiles_search_limit';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_profile_bot' WHERE `name` = 'sys_profile_bot';
UPDATE `sys_options` SET `info` = '_adm_stg_inf_option_sys_create_post_form_preloading_list' WHERE `name` = 'sys_create_post_form_preloading_list';

