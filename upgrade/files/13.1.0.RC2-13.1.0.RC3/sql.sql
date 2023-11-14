
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Options

SET @iCategoryIdSS = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSS, 'sys_profiles_search_limit', '_adm_stg_cpt_option_sys_profiles_search_limit', '20', 'digit', '', '', '', 23);

-- Menu

UPDATE `sys_objects_menu` SET `override_class_name` = 'BxTemplMenuSubmenuWithAddons' WHERE `object` = 'sys_con_submenu';

UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:21:"profile_friends_count";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_con_submenu' AND `name` = 'friends';
UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:36:"profile_recommendation_friends_count";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_con_submenu' AND `name` = 'friend-suggestions';
UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:30:"profile_friends_requests_count";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_con_submenu' AND `name` = 'friend-requests';
UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:35:"profile_sent_friends_requests_count";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_con_submenu' AND `name` = 'sent-friend-requests';
UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:38:"profile_recommendation_following_count";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_con_submenu' AND `name` = 'follow-suggestions';
UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:23:"profile_followers_count";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_con_submenu' AND `name` = 'followers';
UPDATE `sys_menu_items` SET `addon` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:23:"profile_following_count";s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` = 'sys_con_submenu' AND `name` = 'following';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.1.0-RC3' WHERE (`version` = '13.1.0.RC2' OR `version` = '13.1.0-RC2') AND `name` = 'system';

