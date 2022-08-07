
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Email templates

DELETE FROM `sys_email_templates` WHERE `Name` = 't_AccountPasswordExpired';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_system_account_password_expired', 't_AccountPasswordExpired', '_sys_et_txt_subject_account_password_expired', '_sys_et_txt_body_account_password_expired');

-- Options: hidden

DELETE FROM `sys_options` WHERE `name` IN('sys_quill_allow_empty_tags', 'sys_relations_enable');
SET @iCategoryIdHid = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHid, 'sys_quill_allow_empty_tags', '_adm_stg_cpt_option_sys_quill_allow_empty_tags', 'on', 'checkbox', '', '', '', '', 66),
(@iCategoryIdHid, 'sys_relations_enable', '_adm_stg_cpt_option_sys_relations_enable', 'on', 'checkbox', '', '', '', '', 90);

UPDATE `sys_options` SET `value` = '[\'bold\',\'italic\',\'underline\',\'clean\',{ \'header\': [1, 2, 3, 4, 5, 6, false] },{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{\'indent\': \'-1\'},{\'indent\': \'+1\'},{ \'align\':\'\'},{\'align\':\'center\'},{\'align\':\'right\'},{\'align\':\'justify\'},\'blockquote\',\'link\',\'image\',\'embed\',\'emoji\']' WHERE `name` = 'sys_quill_toolbar_standard';
UPDATE `sys_options` SET `value` = '[{ \'header\': [1, 2, 3, 4, 5, 6, false] },\'bold\',\'italic\',\'underline\',\'clean\'],
  [{ \'align\': [] },{\'list\':\'ordered\'}, {\'list\':\'bullet\'},{\'indent\': \'-1\'},{\'indent\': \'+1\'},\'blockquote\',{ \'color\': [] }, { \'background\': [] },{ \'direction\': \'rtl\' },\'link\',\'image\',\'embed\',\'code-block\',\'emoji\']' WHERE `name` = 'sys_quill_toolbar_full';

UPDATE `sys_options` SET `order` = 91 WHERE `name` = 'sys_relations';

-- Options: account

DELETE FROM `sys_options` WHERE `name` IN('sys_account_accounts_password_log_count');
SET @iCategoryIdAcc = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAcc, 'sys_account_accounts_password_log_count', '_adm_stg_cpt_option_sys_accounts_password_log_count', '0', 'digit', '', '', '', 57);

-- Accounts

CREATE TABLE IF NOT EXISTS `sys_accounts_password` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `account_id` int(10) NOT NULL,
    `password` varchar(40) NOT NULL,
    `password_changed` int(11) NOT NULL DEFAULT '0',
    `salt` varchar(10) NOT NULL,
    PRIMARY KEY (`id`)
);

-- Alerts

CREATE TABLE IF NOT EXISTS `sys_alerts_cache_triggers` (
  `id` int(11) UNSIGNED NOT NULL auto_increment,
  `unit` varchar(128) NOT NULL DEFAULT '',
  `action` varchar(32) NOT NULL DEFAULT '',
  `cache_key` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
);

TRUNCATE TABLE `sys_alerts_cache_triggers`;

INSERT INTO `sys_alerts_cache_triggers` (`unit`, `action`, `cache_key`) VALUES
('sys_profiles_subscriptions', 'connection_added', 'menu_sys_profile_stats_profile-stats-subscribed-me_{content}_{_hash}.php');

-- Forms

UPDATE `sys_form_inputs` SET `checker_func` = 'Password' WHERE `object` = 'sys_account' AND `name` = 'password';

-- Menu

UPDATE `sys_menu_items` SET `visible_for_levels` = 192 WHERE `set_name` = 'sys_account_dashboard' AND `name` IN('dashboard-content', 'dashboard-reports', 'dashboard-audit') AND `visible_for_levels` = '2147483646';

-- Grid

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_grid_related_me' AND `type` = 'single' AND `name` = 'delete';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_grid_related_me', 'single', 'delete', '_Delete', 'remove', 1, 1, 4);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-B3' WHERE (`version` = '13.0.0.B2' OR `version` = '13.0.0-B2') AND `name` = 'system';

