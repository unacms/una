
CREATE TABLE IF NOT EXISTS `sys_transcoder_audio_files` (
  `transcoder_object` varchar(64) NOT NULL,
  `file_id` int(11) NOT NULL,
  `handler` varchar(255) NOT NULL,
  `atime` int(11) NOT NULL,
  UNIQUE KEY `transcoder_object` (`transcoder_object`(64),`handler`(127)),
  KEY `atime` (`atime`),
  KEY `file_id` (`file_id`)
);

-- Clear incorrectly queued emails

DELETE FROM `sys_queue_email` WHERE `params` LIKE "-f%";

-- Settings

UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_redirect_after_email_confirmation' AND `value` = 'page.php?i=account-settings-info';

DELETE FROM `sys_options` WHERE `name` IN('sys_revision', 'sys_metatags_hashtags_max', 'sys_metatags_mentions_max', 'sys_eq_send_per_start_to_recipient', 'sys_push_queue_send_per_start_to_recipient');

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_revision', '_adm_stg_cpt_option_sys_revision', '0', 'digit', '', '', '', 5);

SET @iCategoryIdSiteSettings = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteSettings, 'sys_metatags_hashtags_max', '_adm_stg_cpt_option_sys_metatags_hashtags_max', '9', 'digit', '', '', '', 30),
(@iCategoryIdSiteSettings, 'sys_metatags_mentions_max', '_adm_stg_cpt_option_sys_metatags_mentions_max', '9', 'digit', '', '', '', 31);

SET @iCategoryIdNotifications = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'notifications');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdNotifications, 'sys_eq_send_per_start_to_recipient', '_adm_stg_cpt_option_sys_eq_send_per_start_to_recipient', '2',  'digit', '', '', '', 11);


SET @iCategoryIdNotificationsPush = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'notifications_push');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdNotificationsPush, 'sys_push_queue_send_per_start_to_recipient', '_adm_stg_cpt_option_sys_push_queue_send_per_start_to_recipient', '2',  'digit', '', '', '', 11);

-- ACL action

UPDATE `sys_acl_actions` SET `Countable` = 1 WHERE `Module` = 'system' AND `Name` IN('connect', 'vote', 'report', 'favorite', 'comments post', 'comments remove own', 'comments edit all', 'comments remove all');

-- Forms

DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_comment' AND `name` = 'cmt_anonymous';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_comment', 'system', 'cmt_anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

-- Form pre-values

UPDATE `sys_form_pre_lists` SET `extendable` = '1' WHERE `key` = 'Country';

-- Menu

UPDATE `sys_menu_templates` SET `visible` = 8 WHERE `id` = 8;

DELETE FROM `sys_objects_menu` WHERE `object` = 'sys_cmts_item_meta';
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_cmts_item_meta', '_sys_menu_title_cmts_item_meta', 'sys_cmts_item_meta', 'system', 15, 0, 1, 'BxTemplCmtsMenuUnitMeta', '');

DELETE FROM `sys_menu_sets` WHERE `set_name` = 'sys_cmts_item_meta';
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_cmts_item_meta', 'system', '_sys_menu_set_title_cmts_item_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_cmts_item_meta', 'system', 'author', '_sys_menu_item_title_system_sm_author', '_sys_menu_item_title_sm_author', '', '', '', '', '', '', 0, 2147483647, 1, 0, 1, 1),
('sys_cmts_item_meta', 'system', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', '', 0, 2147483647, 1, 0, 1, 2),
('sys_cmts_item_meta', 'system', 'membership', '_sys_menu_item_title_system_sm_membership', '_sys_menu_item_title_sm_membership', '', '', '', '', '', '', 0, 2147483647, 0, 0, 1, 3);

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'css_system' AND `content` = '{dir_plugins_public}spin.js/|spin.css';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'css_system', '{dir_plugins_public}spin.js/|spin.css', 1, 37);

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_system' AND `content` IN('spin.js/spin.js', 'spin.min.js');
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'spin.js/spin.js', 1, 7);


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC10' WHERE (`version` = '9.0.0.RC9' OR `version` = '9.0.0-RC9') AND `name` = 'system';

