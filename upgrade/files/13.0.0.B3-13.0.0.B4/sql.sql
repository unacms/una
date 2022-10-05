
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

ALTER TABLE `sys_options` CHANGE `type` `type` ENUM('value','digit','text','code','checkbox','select','combobox','file','image','list','rlist','rgb','rgba','datetime') NOT NULL DEFAULT 'digit';

-- Options

SET @iCategoryIdSecurity = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'security');
UPDATE `sys_options` SET `category_id` = @iCategoryIdSecurity, `extra` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:27:"get_options_captcha_default";s:5:"class";s:13:"TemplServices";}', `order` = 19 WHERE `name` = 'sys_captcha_default';

UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_quill_allowed_tags_mini';
UPDATE `sys_options` SET `value` = '[{ \'header\': [1, 2, 3, 4, 5, 6, false] },\'bold\',\'italic\',\'underline\',\'clean\'],
  [{ \'align\': [] },{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{\'indent\': \'-1\'},{\'indent\': \'+1\'},\'blockquote\',{ \'color\': [] }, { \'background\': [] },{ \'direction\': \'rtl\' },\'link\',\'image\',\'embed\',\'code-block\',\'emoji\',\'show-html\']' WHERE `name` = 'sys_quill_toolbar_full';

-- Options: Site Settings

SET @iCategoryIdSiteSettings = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteSettings, 'sys_attach_links_max', '_adm_stg_cpt_option_sys_attach_links_max', '0', 'digit', '', '', '', 35);

-- Options: Account

SET @iCategoryIdAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAccount, 'sys_account_accounts_force_password_change_after_expiration', '_adm_stg_cpt_option_sys_accounts_force_password_change_after_expiration', '', 'checkbox', '', '', '', 58);

-- Options: Social

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

INSERT IGNORE INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'social_settings','_adm_stg_cpt_category_social_settings', 0, 21);
SET @iCategoryIdSocial = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'social_settings');

UPDATE `sys_options` SET `category_id` = @iCategoryIdSocial, `order` = 1 WHERE `name` = 'site_login_social_compact';

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSocial, 'sys_a2a_enable', '_adm_stg_cpt_option_sys_a2a_enable', '', 'checkbox', '', '', '', 10),
(@iCategoryIdSocial, 'sys_a2a_code', '_adm_stg_cpt_option_sys_a2a_code', '', 'code', '', '', '', 11);

-- Menu

UPDATE `sys_objects_menu` SET `set_name` = 'sys_account_popup' WHERE `object` = 'sys_account_popup';

DELETE FROM `sys_menu_sets` WHERE `set_name` = 'sys_account_popup';
INSERT IGNORE INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_account_popup', 'system', '_sys_menu_set_title_account_popup', 0);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_popup';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_popup', 'system', 'profile-active', '_sys_menu_item_title_system_ap_profile_active', '', '', '', '', '', '', '', 2147483646, 1, 1, 1),
('sys_account_popup', 'system', 'profile-notifications', '_sys_menu_item_title_system_ap_profile_notifications', '', '', '', '', '', '', '', 2147483646, 1, 1, 2),
('sys_account_popup', 'system', 'profile-switcher', '_sys_menu_item_title_system_ap_profile_switcher', '', '', '', '', '', '', '', 2147483646, 1, 1, 3),
('sys_account_popup', 'system', 'profile-create', '_sys_menu_item_title_system_ap_profile_create', '', '', '', '', '', '', '', 2147483646, 1, 1, 4);

UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_popup(''sys_switch_language_popup'', window);' WHERE `set_name` = 'sys_studio_account_popup' AND `name` = 'language';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-B4' WHERE (`version` = '13.0.0.B3' OR `version` = '13.0.0-B3') AND `name` = 'system';

