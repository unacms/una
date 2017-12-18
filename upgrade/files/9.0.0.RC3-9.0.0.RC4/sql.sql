
-- Captcha

DELETE FROM `sys_objects_captcha` WHERE `object` = 'sys_recaptcha_invisible';
INSERT INTO `sys_objects_captcha` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_recaptcha_invisible', 'reCAPTCHA Invisible', 'BxTemplCaptchaReCAPTCHAInvisible', '');


-- Settings

DELETE FROM `sys_options_categories` WHERE `name` IN('notifications_push');
DELETE FROM `sys_options` WHERE `name` IN('sys_cron_time', 'sys_redirect_after_email_confirmation', 'sys_autoupdate_modules', 'sys_account_auto_profile_creation', 'sys_push_app_id', 'sys_push_rest_api', 'sys_push_short_name', 'sys_push_safari_id');


SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_cron_time', '_adm_stg_cpt_option_sys_cron_time', '0', 'digit', '', '', '', 2),
(@iCategoryIdHidden, 'sys_redirect_after_email_confirmation', '_adm_stg_cpt_option_sys_redirect_after_email_confirmation', 'page.php?i=account-settings-info', 'digit', '', '', '', 41);

UPDATE `sys_options` SET `value` = 'page.php?i=account-profile-switcher&register=1' WHERE `name` = 'sys_redirect_after_account_added' AND `value` = 'page.php?i=account-profile-switcher';
UPDATE `sys_options` SET `value` = 'sys_recaptcha_new' WHERE `name` = 'sys_captcha_default' AND `value` = 'sys_recaptcha';
UPDATE `sys_options` SET `value` = '<html>\r\n    <head></head>\r\n    <body bgcolor="#eee" style="margin:0; padding:0;">\r\n        <div style="padding:20px; background-color:#eee;">\r\n            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; border:1px solid #ccc; border-radius:3px; background-color:#fff; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>' WHERE `name` = 'site_email_html_template_header' AND `value` = '<html>\r\n    <head></head>\r\n    <body bgcolor="#eee" style="margin:0; padding:0;">\r\n        <div style="padding:20px; background-color:#eee;">\r\n            <table width="600" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; border:1px solid #999; background-color:#fff; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>';
UPDATE `sys_options` SET `name` = 'sys_autoupdate', `caption` = '_adm_stg_cpt_option_sys_autoupdate' WHERE `name` = 'sys_autoupdate_system';


SET @iCategoryIdAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAccount, 'sys_account_auto_profile_creation', '_adm_stg_cpt_option_sys_account_auto_profile_creation', 'on', 'checkbox', '', '', '', 15);


SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'notifications_push', '_adm_stg_cpt_category_notifications_push', 0, 17);
SET @iCategoryIdNoficationsPush = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdNoficationsPush, 'sys_push_app_id', '_adm_stg_cpt_option_sys_push_app_id', '', 'digit', '', '', '', 1),
(@iCategoryIdNoficationsPush, 'sys_push_rest_api', '_adm_stg_cpt_option_sys_push_rest_api', '', 'digit', '', '', '', 2),
(@iCategoryIdNoficationsPush, 'sys_push_short_name', '_adm_stg_cpt_option_sys_push_short_name', '', 'digit', '', '', '', 3),
(@iCategoryIdNoficationsPush, 'sys_push_safari_id', '_adm_stg_cpt_option_sys_push_safari_id', '', 'digit', '', '', '', 4);


-- ACL

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'system' AND `sys_acl_actions`.`Name` = 'post links';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` = 'post links';

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'post links', NULL, '_sys_acl_action_post_links', '_sys_acl_action_post_links_desc', 0, 3);
SET @iIdActionPostLinks = LAST_INSERT_ID();

SET @iStandard = 3;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iStandard, @iIdActionPostLinks),
(@iModerator, @iIdActionPostLinks),
(@iAdministrator, @iIdActionPostLinks),
(@iPremium, @iIdActionPostLinks);


-- Injections

DELETE FROM `sys_injections` WHERE `name` IN ('sys_popup_alert', 'sys_popup_confirm', 'sys_popup_prompt', 'sys_push_init');

INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('sys_popup_alert', 0, 'injection_footer', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"add_popup_alert";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1),
('sys_popup_confirm', 0, 'injection_footer', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:17:"add_popup_confirm";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1),
('sys_popup_prompt', 0, 'injection_footer', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"add_popup_prompt";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1),
('sys_push_init', 0, 'injection_header', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:13:"add_push_init";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1);

DELETE FROM `sys_injections_admin` WHERE `name` IN ('sys_popup_alert', 'sys_popup_confirm', 'sys_popup_prompt');

INSERT INTO `sys_injections_admin`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('sys_popup_alert', 0, 'injection_footer', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"add_popup_alert";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1),
('sys_popup_confirm', 0, 'injection_footer', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:17:"add_popup_confirm";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1),
('sys_popup_prompt', 0, 'injection_footer', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"add_popup_prompt";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', 0, 1);


-- Menus

UPDATE `sys_menu_items` SET `visible_for_levels` = 2147483644 WHERE `set_name` = 'sys_account_notifications' AND `module` = 'system' AND `name` = 'profile' AND `visible_for_levels` = 2147483646;

-- Pages

UPDATE `sys_pages_blocks` SET `visible_for_levels` = 2147483644 WHERE `object` = 'sys_dashboard' AND `module`= 'system' AND `title` = '_sys_page_block_title_profile_stats' AND `visible_for_levels` = 2147483646;



-- last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC4' WHERE (`version` = '9.0.0.RC3' OR `version` = '9.0.0-RC3') AND `name` = 'system';

