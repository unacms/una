

-- EMAIL TEMPLATES

DELETE FROM `sys_email_templates` WHERE `Name` = 't_Reported';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_system_reported', 't_Reported', '_sys_et_txt_subject_system_reported', '_sys_et_txt_body_system_reported');


-- SETTINGS 

UPDATE `sys_options` SET `value` = '' WHERE `name` = 'template';

ALTER TABLE `sys_options` CHANGE `type` `type` ENUM('digit','text','checkbox','select','combobox','file','image','list','rlist','rgb','rgba') NOT NULL DEFAULT 'digit';

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

-- SETTINGS CATEGORY: HIDDEN

DELETE FROM `sys_options_categories` WHERE `name` = 'hidden';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'hidden', '_adm_stg_cpt_category_hidden', 1, 0);
SET @iCategoryHidden = LAST_INSERT_ID();

UPDATE `sys_options` SET `category_id` = @iCategoryHidden WHERE `name` IN('sys_install_time', 'sys_ftp_login', 'sys_ftp_password', 'sys_ftp_dir', 'sys_template_cache_image_enable', 'sys_template_cache_image_max_size', 'sys_email_confirmation', 'sys_redirect_after_account_added', 'sys_editor_default', 'sys_captcha_default', 'sys_live_updates_interval');

UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_install_time' WHERE `name` = 'sys_install_time';

UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_template_cache_image_enable', `order` = 20 WHERE `name` = 'sys_template_cache_image_enable';
UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_template_cache_image_max_size', `order` = 21 WHERE `name` = 'sys_template_cache_image_max_size';

UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_email_confirmation', `order` = 30 WHERE `name` = 'sys_email_confirmation';

UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_redirect_after_account_added', `order` = 40, `value` = 'page.php?i=account-profile-switcher' WHERE `name` = 'sys_redirect_after_account_added';

UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_editor_default', `order` = 50 WHERE `name` = 'sys_editor_default';
UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_captcha_default', `order` = 51 WHERE `name` = 'sys_captcha_default';

UPDATE `sys_options` SET `caption` = '_adm_stg_cpt_option_sys_live_updates_interval', `order` = 60 WHERE `name` = 'sys_live_updates_interval';

-- SETTINGS CATEGORY: SYSTEM

SET @iCategorySystem = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');

DELETE FROM `sys_options` WHERE `name` IN('sys_site_cover_common', 'sys_site_cover_home');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategorySystem, 'sys_site_cover_common', '', '0', 'digit', '', '', '', 27),
(@iCategorySystem, 'sys_site_cover_home', '', '0', 'digit', '', '', '', 28);

UPDATE `sys_options` SET `name` = 'sys_site_splash_code' WHERE `name` = 'sys_site_cover_code';
UPDATE `sys_options` SET `name` = 'sys_site_splash_enabled' WHERE `name` = 'sys_site_cover_enabled';

-- SETTINGS CATEGORY: GENERAL

SET @iCategoryGeneral = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');

DELETE FROM `sys_options` WHERE `name` IN('sys_default_payment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryGeneral, 'sys_default_payment', '_adm_stg_cpt_option_sys_default_payment', 'payment', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:12:"get_payments";s:5:"class";s:21:"TemplPaymentsServices";}', '', '', 60);

-- SETTINGS CATEGORY: ACCOUNT

SET @iCategoryAccount = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');

DELETE FROM `sys_options` WHERE `name` IN('sys_account_online_time');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryAccount, 'sys_account_online_time', '_adm_stg_cpt_option_sys_account_online_time', '5', 'digit', '', 'Avail', '_adm_stg_err_option_sys_account_online_time', 1);

-- SETTINGS CATEGORY: ACL

DELETE FROM `sys_options_categories` WHERE `name` = 'acl';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'acl', '_adm_stg_cpt_category_acl', 0, 15);
SET @iCategoryAcl = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN('sys_acl_expire_notification_days', 'sys_acl_expire_notify_once');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryAcl, 'sys_acl_expire_notification_days', '_adm_stg_cpt_option_sys_acl_expire_notification_days', '1', 'digit', '', '', '', 1),
(@iCategoryAcl, 'sys_acl_expire_notify_once', '_adm_stg_cpt_option_sys_acl_expire_notify_once', 'on', 'checkbox', '', '', '', 2);

-- SETTINGS MIXES

CREATE TABLE IF NOT EXISTS `sys_options_mixes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `type` varchar(64) NOT NULL default '',
  `category` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `title` varchar(64) NOT NULL default '',
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_options_mixes2options` (
  `option` varchar(64) NOT NULL default '',
  `mix_id` int(11) unsigned NOT NULL default '0',
  `value` mediumtext NOT NULL,
  UNIQUE KEY `value`(`option`, `mix_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- ALERTS

SET @iIdActionReportOld = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` = 'report');
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` = 'report';
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'report', NULL, '_sys_acl_action_report', '', 0, 0);
SET @iIdActionReport = LAST_INSERT_ID();

SET @iIdActionReportViewOld = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` = 'report_view');
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` = 'report_view';
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'report_view', NULL, '_sys_acl_action_report_view', '', 0, 0);
SET @iIdActionReportView = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

DELETE FROM `sys_acl_matrix` WHERE @iIdActionReportOld IS NOT NULL AND `IDAction` = @iIdActionReportOld;
DELETE FROM `sys_acl_matrix` WHERE @iIdActionReportViewOld IS NOT NULL AND `IDAction` = @iIdActionReportViewOld;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
-- report 
(@iStandard, @iIdActionReport),
(@iModerator, @iIdActionReport),
(@iAdministrator, @iIdActionReport),
(@iPremium, @iIdActionReport),

-- report view 
(@iModerator, @iIdActionReportView),
(@iAdministrator, @iIdActionReportView);


-- ACL LEVELS

DELETE FROM `sys_acl_levels` WHERE `Name` IN('_adm_prm_txt_level_unauthenticated', '_adm_prm_txt_level_account', '_adm_prm_txt_level_standard', '_adm_prm_txt_level_unconfirmed', '_adm_prm_txt_level_pending', '_adm_prm_txt_level_suspended', '_adm_prm_txt_level_moderator', '_adm_prm_txt_level_administrator', '_adm_prm_txt_level_premium');

INSERT INTO `sys_acl_levels` (`ID`, `Name`, `Icon`, `Description`, `Active`, `Purchasable`, `Removable`, `QuotaSize`, `QuotaNumber`, `QuotaMaxFileSize`, `Order`) VALUES
(1, '_adm_prm_txt_level_unauthenticated', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 1),
(2, '_adm_prm_txt_level_account', 'user col-green1', '', 'yes', 'no', 'no', 0, 0, 0, 2),
(3, '_adm_prm_txt_level_standard', 'user col-red1', '', 'yes', 'no', 'no', 0, 0, 0, 3),
(4, '_adm_prm_txt_level_unconfirmed', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 4),
(5, '_adm_prm_txt_level_pending', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 5),
(6, '_adm_prm_txt_level_suspended', 'user bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 6),
(7, '_adm_prm_txt_level_moderator', 'user-secret bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 7),
(8, '_adm_prm_txt_level_administrator', 'user-secret bx-def-font-color', '', 'yes', 'no', 'no', 0, 0, 0, 8),
(9, '_adm_prm_txt_level_premium', 'user col-red3', '', 'yes', 'yes', 'no', 0, 0, 0, 9);


-- ALERTS

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_settings_sys_images_custom_file_deleted' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES
('sys_settings_sys_images_custom_file_deleted', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:45:"alert_response_sys_images_custom_file_deleted";s:6:"params";a:0:{}s:5:"class";s:27:"TemplStudioSettingsServices";}');
SET @iIdHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_images_custom', 'file_deleted', @iIdHandler);


-- OBJECTS: REPORT

CREATE TABLE IF NOT EXISTS `sys_objects_report` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL,
  `table_main` varchar(32) NOT NULL,
  `table_track` varchar(32) NOT NULL,
  `is_on` tinyint(4) NOT NULL default '1',
  `base_url` varchar(256) NOT NULL default '',
  `trigger_table` varchar(32) NOT NULL,
  `trigger_field_id` varchar(32) NOT NULL,
  `trigger_field_author` varchar(32) NOT NULL,
  `trigger_field_count` varchar(32) NOT NULL,
  `class_name` varchar(32) NOT NULL default '',
  `class_file` varchar(256) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- CRON JOBS

DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_acl';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_acl', '0 0 * * *', 'BxDolCronAcl', 'inc/classes/BxDolCronAcl.php', '');


-- OBJECTS: STORAGE

UPDATE `sys_objects_storage` SET `ext_allow` = 'jpg,jpeg,jpe,gif,png,svg' WHERE `object` = 'sys_images';


-- OBJECTS: UPLOADER

INSERT IGNORE INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_crop', 1, 'BxTemplUploaderCrop', ''),
('sys_settings_html5', 1, 'BxTemplStudioSettingsUploaderHTML5', ''),
('sys_builder_page_simple', 1, 'BxTemplStudioBuilderPageUploaderSimple', ''),
('sys_builder_page_html5', 1, 'BxTemplStudioBuilderPageUploaderHTML5', '');


-- OBJECTS: FORM

INSERT IGNORE INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_report', 'system', '_sys_form_report', 'report.php', 'a:3:{s:2:"id";s:0:"";s:4:"name";s:0:"";s:5:"class";s:17:"bx-report-do-form";}', 'submit', '', 'id', '', '', '', 0, 1, '', '');


INSERT IGNORE INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_report_post', 'system', 'sys_report', '_sys_form_display_report_post', 0);


DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_login' AND `module` = 'system' AND `name` = 'submit_block';

UPDATE `sys_form_inputs` SET `editable` = 1 WHERE `object` = 'sys_login' AND `module` = 'system' AND `name` = 'rememberMe';

UPDATE `sys_form_inputs` SET `checked` = 1 WHERE `object` = 'sys_account' AND `module` = 'system' AND `name` = 'receive_news';

UPDATE `sys_form_inputs` SET `value` = 'a:1:{i:0;s:15:"sys_cmts_simple";}', `values` = 'a:1:{s:15:"sys_cmts_simple";s:26:"_sys_uploader_simple_title";}' WHERE `object` = 'sys_comment' AND `module` = 'system' AND `name` = 'cmt_image';

INSERT IGNORE INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_account', 'system', 'agreement', '', '', 0, 'custom', '_sys_form_login_input_caption_system_agreement', '_sys_form_account_input_agreement', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_report', 'system', 'sys', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_report', 'system', 'object_id', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_object_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_report', 'system', 'action', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_report', 'system', 'id', '', '', 0, 'hidden', '_sys_form_report_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_report', 'system', 'type', '', '#!sys_report_types', 0, 'select', '_sys_form_report_input_caption_system_type', '_sys_form_report_input_caption_type', '', 1, 0, 0, '', '', '', 'Avail', '', '_Please select value', 'Xss', '', 1, 0),
('sys_report', 'system', 'text', '', '', 0, 'textarea', '_sys_form_report_input_caption_system_text', '_sys_form_report_input_caption_text', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_report', 'system', 'submit', '_sys_form_report_input_caption_submit', '', 0, 'submit', '_sys_form_report_input_caption_system_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_login';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_login', 'role', 2147483647, 1, 1),
('sys_login', 'relocate', 2147483647, 1, 2),
('sys_login', 'ID', 2147483647, 1, 3),
('sys_login', 'Password', 2147483647, 1, 4),
('sys_login', 'rememberMe', 2147483647, 1, 5),
('sys_login', 'login', 2147483647, 1, 6),
('sys_login', 'submit_text', 2147483647, 1, 7);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_account_create';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_account_create', 'name', 2147483647, 1, 1),
('sys_account_create', 'email', 2147483647, 1, 2),
('sys_account_create', 'password', 2147483647, 1, 3),
('sys_account_create', 'do_submit', 2147483647, 1, 4),
('sys_account_create', 'agreement', 2147483647, 1, 5);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_report_post';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_report_post', 'sys', 2147483647, 1, 1),
('sys_report_post', 'object_id', 2147483647, 1, 2),
('sys_report_post', 'action', 2147483647, 1, 3),
('sys_report_post', 'id', 2147483647, 0, 4),
('sys_report_post', 'type', 2147483647, 1, 5),
('sys_report_post', 'text', 2147483647, 1, 6),
('sys_report_post', 'submit', 2147483647, 1, 7);


INSERT IGNORE INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('sys_report_types', '_sys_pre_lists_report_types', 'system', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_report_types';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('sys_report_types', 'spam', 1, '_sys_pre_lists_report_types_spam', ''),
('sys_report_types', 'scam', 2, '_sys_pre_lists_report_types_scam', ''),
('sys_report_types', 'fraud', 3, '_sys_pre_lists_report_types_fraud', ''),
('sys_report_types', 'nude', 4, '_sys_pre_lists_report_types_nude', ''),
('sys_report_types', 'other', 5, '_sys_pre_lists_report_types_other', '');


-- OBJECTS: MENU

DELETE FROM `sys_menu_templates` WHERE `id` = 1;
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`) VALUES
(1, 'menu_empty.html', '_sys_menu_template_title_empty');

UPDATE `sys_objects_menu` SET `template_id` = 14 WHERE `object` = 'sys_add_profile';

DELETE FROM `sys_objects_menu` WHERE `object` IN ('sys_switch_language', 'sys_switch_language_popup', 'sys_switch_language_inline');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_switch_language_popup', '_sys_menu_title_switch_language_popup', 'sys_switch_language', 'system', 6, 0, 1, 'BxTemplMenuSwitchLanguage', ''),
('sys_switch_language_inline', '_sys_menu_title_switch_language_inline', 'sys_switch_language', 'system', 3, 0, 1, 'BxTemplMenuSwitchLanguage', '');

-- footer

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_footer' AND `module` = 'system' AND `name` IN('terms', 'privacy');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_footer', 'system', 'terms', '_sys_menu_item_title_system_terms', '_sys_menu_item_title_terms', 'page.php?i=terms', '', '', '', '', 2147483647, 1, 1, 2),
('sys_footer', 'system', 'privacy', '_sys_menu_item_title_system_privacy', '_sys_menu_item_title_privacy', 'page.php?i=privacy', '', '', '', '', 2147483647, 1, 1, 3);

UPDATE `sys_menu_items` SET `onclick` = 'bx_menu_popup(''sys_switch_language_popup'', window);', `active` = 0, `order` = 4 WHERE `set_name` = 'sys_footer' AND `module` = 'system' AND `name` = 'switch_language';
UPDATE `sys_menu_items` SET `order` = 5 WHERE `set_name` = 'sys_footer' AND `module` = 'system' AND `name` = 'switch_template';

-- notifications 

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `module` = 'system';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'system', 'cart', '_sys_menu_item_title_system_cart', '_sys_menu_item_title_cart', 'cart.php', '', '', 'cart-plus col-red3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_cart_items_count";s:6:"params";a:0:{}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 1, 1, 1),
('sys_account_notifications', 'system', 'orders', '_sys_menu_item_title_system_orders', '_sys_menu_item_title_orders', 'orders.php', '', '', 'cart-arrow-down col-green3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_orders_count";s:6:"params";a:1:{i:0;s:3:"new";}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 1, 1, 2);


-- OBJECTS: GRID

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_forms_pre_values';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_forms_pre_values', 'checkbox', '', '1%', 0, '', '', 1),
('sys_studio_forms_pre_values', 'order', '', '1%', 0, '', '', 2),
('sys_studio_forms_pre_values', 'LKey', '_adm_form_txt_pre_values_gl_lkey', '78%', 1, '75', '', 3),
('sys_studio_forms_pre_values', 'actions', '', '20%', 0, '', '', 4);

INSERT IGNORE INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_forms_pre_values', 'bulk', 'delete', '_adm_form_btn_pre_values_delete', '', 1, 1);


-- OBJECTS: TRANSCODER

INSERT IGNORE INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('sys_cover', 'sys_images', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '0', '0', '0', '', ''),
('sys_builder_page_preview', 'sys_images', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '1', '2592000', '0', '', ''),
('sys_builder_page_embed', 'sys_images', 'Storage', 'a:1:{s:6:"object";s:10:"sys_images";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object` IN('sys_cover', 'sys_builder_page_preview', 'sys_builder_page_embed');
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('sys_cover', 'Resize', 'a:3:{s:1:"w";s:4:"1920";s:1:"h";s:3:"720";s:10:"force_type";s:3:"png";}', '0'),
('sys_builder_page_preview', 'Resize', 'a:4:{s:1:"w";s:3:"128";s:1:"h";s:3:"128";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('sys_builder_page_embed', 'ResizeVar', '', '0');


-- OBJECTS: PAGE

INSERT IGNORE INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('sys_terms', 'terms', '_sys_page_title_system_terms', '_sys_page_title_terms', 'system', 5, 2147483647, 1, 'page.php?i=terms', '', '', '', 0, 1, 0, '', ''),
('sys_privacy', 'privacy', '_sys_page_title_system_privacy', '_sys_page_title_privacy', 'system', 5, 2147483647, 1, 'page.php?i=privacy', '', '', '', 0, 1, 0, '', '');


DELETE FROM `sys_pages_blocks` WHERE `object` IN('sys_terms', 'sys_privacy');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_terms', 1, 'system', '', '_sys_page_block_title_terms', 11, 2147483647, 'lang', '_sys_page_lang_block_terms', 0, 1, 1),
('sys_privacy', 1, 'system', '', '_sys_page_block_title_privacy', 11, 2147483647, 'lang', '_sys_page_lang_block_privacy', 0, 1, 1);


DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `title` = '_sys_page_block_title_profile_membership';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_dashboard', 2, 'system', '', '_sys_page_block_title_profile_membership', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"profile_membership";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 1, 1);


DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_login' AND `title` IN('_sys_page_block_title_login', '_sys_page_block_title_login', '_sys_page_block_title_login');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_login', 1, 'system', '_sys_page_block_system_title_login', '_sys_page_block_title_login', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:10:\"login_form\";s:5:\"class\";s:17:\"TemplServiceLogin\";}', 0, 1, 1),
('sys_login', 0, 'system', '_sys_page_block_system_title_login_only', '_sys_page_block_title_login', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:15:\"login_form_only\";s:5:\"class\";s:17:\"TemplServiceLogin\";}', 0, 1, 0);


UPDATE `sys_pages_blocks` SET `order` = 2 WHERE `object` = 'sys_account_profile_switcher' AND `title` IN('_sys_page_block_title_account_profile_switcher');
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_account_profile_switcher' AND `title` IN('_sys_page_block_title_account_profile_create');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_account_profile_switcher', 1, 'system', '', '_sys_page_block_title_account_profile_create', 11, 2147483647, 'menu', 'sys_add_profile', 0, 1, 1);


-- OBJECTS: PAYMENTS

CREATE TABLE IF NOT EXISTS `sys_objects_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `uri` varchar(32) NOT NULL default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`),
  UNIQUE KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- last step is to update current version


UPDATE `sys_modules` SET `version` = '9.0.0.B1' WHERE `version` = '8.0.1' AND `name` = 'system';

