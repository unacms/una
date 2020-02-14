

-- Email templates

DELETE FROM `sys_email_templates` WHERE `Name` IN('t_ChangeStatusAccountActivate', 't_ChangeStatusAccountSuspend');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_account_change_status_activate', 't_ChangeStatusAccountActivate', '_sys_et_txt_subject_account_change_status_activate', '_sys_et_txt_body_account_change_status_activate'),
('system', '_sys_et_txt_name_account_change_status_suspended', 't_ChangeStatusAccountSuspend', '_sys_et_txt_subject_account_change_status_suspended', '_sys_et_txt_body_account_change_status_suspended');

-- Settings

DELETE FROM `sys_options` WHERE `name` IN('sys_session_lifetime_in_min', 'add_to_mobile_homepage', 'smart_app_banner', 'smart_app_banner_ios_app_id', 'sys_profile_bot', 'sys_hide_post_to_context_for_privacy');

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_session_lifetime_in_min', '_adm_stg_cpt_option_sys_session_lifetime_in_min', '129600', 'digit', '', '', '', 110);

SET @iCategoryIdSiteSettings = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdSiteSettings, 'smart_app_banner', '_adm_stg_cpt_option_smart_app_banner', '', 'checkbox', '', '', '', 14),
(@iCategoryIdSiteSettings, 'smart_app_banner_ios_app_id', '_adm_stg_cpt_option_smart_app_banner_ios_app_id', '', 'digit', '', '', '', 15),
(@iCategoryIdSiteSettings, 'sys_profile_bot', '_adm_stg_cpt_option_sys_profile_bot', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:23:"get_options_profile_bot";s:5:"class";s:13:"TemplServices";}', '', '', 40),
(@iCategoryIdSiteSettings, 'sys_hide_post_to_context_for_privacy', '_adm_stg_cpt_option_sys_hide_post_to_context_for_privacy', '', 'list', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:44:"get_options_module_list_for_privacy_selector";s:5:"class";s:13:"TemplServices";}', '', '', 50);

-- Settings: Audit

SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

DELETE FROM `sys_options_categories` WHERE `name` = 'audit';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'audit', '_adm_stg_cpt_category_audit', 1, 2);
SET @iCategoryIdAudit = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN('sys_audit_enable', 'sys_audit_max_records', 'sys_audit_days_before_expire', 'sys_audit_acl_levels');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAudit, 'sys_audit_enable', '_adm_stg_cpt_option_sys_audit_enable', '', 'checkbox', '', '', '', 1),
(@iCategoryIdAudit, 'sys_audit_max_records', '_adm_stg_cpt_option_sys_audit_max_records', '10000', 'digit', '', '', '', 2),
(@iCategoryIdAudit, 'sys_audit_days_before_expire', '_adm_stg_cpt_option_sys_audit_days_before_expire', '365', 'digit', '', '', '', 3),
(@iCategoryIdAudit, 'sys_audit_acl_levels', '_adm_stg_cpt_option_sys_audit_acl_levels', '7,8', 'list', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_memberships";s:6:"params";a:0:{}s:5:"class";s:16:"TemplAclServices";}', '', '', 4);

-- ACL

DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'system' AND `sys_acl_actions`.`Name` IN('set badge', 'use macros', 'switch to any profile', 'show membership levels in privacy groups', 'show membership private info', 'wiki add block', 'wiki edit block', 'wiki translate block', 'wiki delete version', 'wiki delete block', 'wiki history', 'wiki unsafe');
DELETE FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` IN('set badge', 'use macros', 'switch to any profile', 'show membership levels in privacy groups', 'show membership private info', 'wiki add block', 'wiki edit block', 'wiki translate block', 'wiki delete version', 'wiki delete block', 'wiki history', 'wiki unsafe');

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'set badge', NULL, '_sys_acl_action_set_badge', '_sys_acl_action_set_badge_desc', 0, 3);
SET @iIdActionSetBadge = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'use macros', NULL, '_sys_acl_action_use_macros', '_sys_acl_action_use_macros_desc', 0, 0);
SET @iIdActionUseMacros = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'switch to any profile', NULL, '_sys_acl_action_switch_to_any_profile', '_sys_acl_action_switch_to_any_profile', 0, 0);
SET @iIdActionSwitchToAnyProfile = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'show membership levels in privacy groups', NULL, '_sys_acl_action_show_membership_levels_in_privacy_groups', '_sys_acl_action_show_membership_levels_in_privacy_groups_desc', 0, 0);
SET @iIdActionShowMembershipLevelsInPrivacyGroups = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'show membership private info', NULL, '_sys_acl_action_show_membership_private_info', '_sys_acl_action_show_membership_private_info_desc', 0, 0);
SET @iIdActionShowMembershipPrivateInfo = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'wiki add block', NULL, '_sys_acl_action_add_block', '', 0, 1);
SET @iIdActionWikiAddBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'wiki edit block', NULL, '_sys_acl_action_edit_block', '', 0, 0);
SET @iIdActionWikiEditBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'wiki translate block', NULL, '_sys_acl_action_translate_block', '', 0, 0);
SET @iIdActionWikiTranslateBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'wiki delete version', NULL, '_sys_acl_action_delete_version', '', 0, 1);
SET @iIdActionWikiDeleteVersion = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'wiki delete block', NULL, '_sys_acl_action_delete_block', '', 0, 1);
SET @iIdActionWikiDeleteBlock = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'wiki history', NULL, '_sys_acl_action_history', '', 0, 0);
SET @iIdActionWikiHistory = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('system', 'wiki unsafe', NULL, '_sys_acl_action_unsafe', '', 0, 0);
SET @iIdActionWikiUnsafe = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- set badge
(@iAdministrator, @iIdActionSetBadge),

-- use macros
(@iModerator, @iIdActionUseMacros),
(@iAdministrator, @iIdActionUseMacros),

-- switch to any profile
(@iAdministrator, @iIdActionSwitchToAnyProfile),

-- show membership levels in privacy groups
(@iAdministrator, @iIdActionShowMembershipLevelsInPrivacyGroups),

-- show membership private info
(@iModerator, @iIdActionShowMembershipPrivateInfo),
(@iAdministrator, @iIdActionShowMembershipPrivateInfo);


-- Search objects

DELETE FROM `sys_objects_search` WHERE `ObjectName` = 'sys_pages';
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('sys_pages', '_sys_pages', 1, 'BxTemplPagesSearchResult', '');

-- Files storage table structure

ALTER TABLE  `sys_files` CHANGE  `size`  `size` BIGINT( 20 ) NOT NULL;
ALTER TABLE  `sys_images` CHANGE  `size`  `size` BIGINT( 20 ) NOT NULL;
ALTER TABLE  `sys_images_custom` CHANGE  `size`  `size` BIGINT( 20 ) NOT NULL;
ALTER TABLE  `sys_images_resized` CHANGE  `size`  `size` BIGINT( 20 ) NOT NULL;
ALTER TABLE  `sys_cmts_images` CHANGE  `size`  `size` BIGINT( 20 ) NOT NULL;
ALTER TABLE  `sys_cmts_images_preview` CHANGE  `size`  `size` BIGINT( 20 ) NOT NULL;
ALTER TABLE  `sys_transcoder_queue_files` CHANGE  `size`  `size` BIGINT( 20 ) NOT NULL;

-- Injections

DELETE FROM `sys_injections` WHERE `name` = 'sys_body_class';
INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('sys_body_class', 0, 'injection_body_class', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:13:"get_injection";s:6:"params";a:1:{i:0;s:10:"body_class";}s:5:"class";s:21:"TemplTemplateServices";}', 0, 1);

-- Audit

CREATE TABLE IF NOT EXISTS `sys_audit` (
  `id` int(11) unsigned NOT NULL auto_increment, 
  `added` int(11) NOT NULL, 
  `profile_id` int(10) NOT NULL, 
  `profile_title` varchar(255) NOT NULL, 
  `content_id` int(10) NOT NULL, 
  `content_title` varchar(255) NOT NULL, 
  `content_module` varchar(32) NOT NULL default '',
  `content_info_object` varchar(32) NOT NULL default '',
  `context_profile_id` int(10) NOT NULL, 
  `context_profile_title` varchar(255) NOT NULL, 
  `action_lang_key` varchar(255) NOT NULL, 
  `action_lang_key_params` text NOT NULL,
  `extras` text NOT NULL,
  PRIMARY KEY (`id`)
);

-- Alerts

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_cmts_sys_cmts_images_file_deleted' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES
('sys_cmts_sys_cmts_images_file_deleted', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:43:"alert_response_sys_cmts_images_file_deleted";s:6:"params";a:0:{}s:5:"class";s:17:"TemplCmtsServices";}');
SET @iIdHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_cmts_images', 'file_deleted', @iIdHandler);

-- Badges

CREATE TABLE IF NOT EXISTS `sys_badges` (
  `id` int(11) unsigned NOT NULL auto_increment, 
  `added` int(11) NOT NULL, 
  `module` varchar(32) NOT NULL default '',
  `text` varchar(255) NOT NULL default '',
  `icon` varchar(255) NOT NULL default '',
  `color` varchar(32) NOT NULL default '',
  `is_icon_only` tinyint(4) NOT NULL default '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_badges2objects` (
  `id` int(11) unsigned NOT NULL auto_increment, 
  `badge_id` int(11) NOT NULL, 
  `object_id` int(11) NOT NULL, 
  `module` varchar(32) NOT NULL, 
  `added` int(11) NOT NULL, 
  PRIMARY KEY (`id`),
  UNIQUE KEY `badge_object` (`object_id`, `badge_id`)
);

-- Cron

DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_audit_clean';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_audit_clean', '* * * * *', 'BxDolCronAudit', 'inc/classes/BxDolCronAudit.php', '');

-- Wiki Form

DELETE FROM `sys_objects_form` WHERE `object` = 'sys_wiki';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_wiki', 'system', '_sys_form_wiki', '', '', 'do_submit', 'sys_pages_wiki_blocks', 'id', '', '', '', 0, 1, 'BxTemplFormWiki', '');

DELETE FROM `sys_form_displays` WHERE `display_name` IN('sys_wiki_edit', 'sys_wiki_translate');
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_wiki_edit', 'system', 'sys_wiki', '_sys_form_display_wiki_edit', 0),
('sys_wiki_translate', 'system', 'sys_wiki', '_sys_form_display_wiki_translate', 0);

DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_wiki';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_wiki', 'system', 'block_id', '', '', 0, 'hidden', '', '_sys_form_wiki_input_caption_block_id', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_wiki', 'system', 'language', '', '', 0, 'radio_set', '', '_sys_form_wiki_input_caption_lang', '_sys_form_wiki_input_caption_lang_info', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_wiki', 'system', 'content_main', '', '', 0, 'custom', '', '_sys_form_wiki_input_caption_content_main', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_wiki', 'system', 'content', '', '', 0, 'textarea', '', '_sys_form_wiki_input_caption_content', '_sys_form_wiki_input_caption_content_info', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_wiki', 'system', 'notes', '', '', 0, 'text', '', '_sys_form_wiki_input_caption_notes', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('sys_wiki', 'system', 'do_submit', '_sys_submit', '', 0, 'submit', '_sys_form_wiki_input_caption_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_wiki', 'system', 'close', '_sys_close', '', 0, 'reset', '_sys_form_wiki_input_caption_close', '', '', 0, 0, 0, 'a:2:{s:7:\"onclick\";s:46:\"$(\'.bx-popup-applied:visible\').dolPopupHide();\";s:5:\"class\";s:22:\"bx-def-margin-sec-left\";}', '', '', '', '', '', '', '', 1, 0),
('sys_wiki', 'system', 'buttons', '', 'do_submit,close', 0, 'input_set', '_sys_form_wiki_buttons', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('sys_wiki_edit', 'sys_wiki_translate');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_wiki_edit', 'block_id', 2147483647, 1, 1),
('sys_wiki_edit', 'language', 2147483647, 1, 2),
('sys_wiki_edit', 'content', 2147483647, 1, 3),
('sys_wiki_edit', 'notes', 2147483647, 1, 4),
('sys_wiki_edit', 'do_submit', 2147483647, 1, 5),
('sys_wiki_edit', 'close', 2147483647, 1, 6),
('sys_wiki_edit', 'buttons', 2147483647, 1, 7),

('sys_wiki_translate', 'block_id', 2147483647, 1, 1),
('sys_wiki_translate', 'content_main', 2147483647, 1, 2),
('sys_wiki_translate', 'language', 2147483647, 1, 3),
('sys_wiki_translate', 'content', 2147483647, 1, 4),
('sys_wiki_translate', 'notes', 2147483647, 1, 5),
('sys_wiki_translate', 'do_submit', 2147483647, 1, 6),
('sys_wiki_translate', 'close', 2147483647, 1, 7),
('sys_wiki_translate', 'buttons', 2147483647, 1, 8);

-- Forms

UPDATE `sys_form_inputs` SET `value` = '1' WHERE `object` = 'sys_account' AND `name` = 'delete_content';

UPDATE `sys_form_display_inputs` SET `active` = 1 WHERE `display_name` = 'sys_comment_edit' AND `input_name` = 'cmt_image';
UPDATE `sys_form_display_inputs` SET `active` = 1 WHERE `display_name` = 'sys_review_edit' AND `input_name` = 'cmt_image';


-- Pre-values

DELETE FROM `sys_form_pre_values` WHERE `Key` = 'sys_vote_reactions' AND `Value` IN('like', 'love', 'joy', 'surprise', 'sadness', 'anger');
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`, `Data`) VALUES
('sys_vote_reactions', 'like', 1, '_sys_pre_lists_vote_reactions_like', '', 'a:3:{s:4:"icon";s:9:"thumbs-up";s:5:"color";s:20:"sys-colored col-gray";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'love', 2, '_sys_pre_lists_vote_reactions_love', '', 'a:3:{s:4:"icon";s:5:"heart";s:5:"color";s:20:"sys-colored col-red1";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'joy', 3, '_sys_pre_lists_vote_reactions_joy', '', 'a:3:{s:4:"icon";s:11:"grin-squint";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'surprise', 4, '_sys_pre_lists_vote_reactions_surprise', '', 'a:3:{s:4:"icon";s:8:"surprise";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'sadness', 5, '_sys_pre_lists_vote_reactions_sadness', '', 'a:3:{s:4:"icon";s:8:"sad-tear";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}'),
('sys_vote_reactions', 'anger', 6, '_sys_pre_lists_vote_reactions_anger', '', 'a:3:{s:4:"icon";s:5:"angry";s:5:"color";s:20:"sys-colored col-red3";s:6:"weight";s:1:"1";}');

-- Menus

DELETE FROM `sys_menu_templates` WHERE `id` = 18;
UPDATE `sys_objects_menu` SET `template_id` = 23 WHERE `template_id` = 18;

UPDATE `sys_objects_menu` SET `template_id` = 7 WHERE `object` = 'sys_site' AND `template_id` = 14;
UPDATE `sys_objects_menu` SET `template_id` = 7 WHERE `object` = 'sys_homepage' AND `template_id` = 14;
UPDATE `sys_objects_menu` SET `template_id` = 7 WHERE `object` = 'sys_add_content' AND `template_id` = 14;
UPDATE `sys_objects_menu` SET `template_id` = 15 WHERE `object` = 'sys_create_post' AND `template_id` = 22;

DELETE FROM `sys_objects_menu` WHERE `object` IN('sys_set_badges', 'sys_vote_reactions_do', 'sys_wiki');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_set_badges', '_sys_menu_title_set_badges', '', 'system', 6, 0, 1, 'BxTemplMenuSetBadges', ''),
('sys_vote_reactions_do', '_sys_menu_title_vote_reactions_do', '', 'system', 3, 0, 1, 'BxTemplVoteReactionsMenuDo', ''),
('sys_wiki', '_sys_menu_title_wiki', 'sys_wiki', 'system', 6, 0, 1, 'BxTemplMenuWiki', '');


DELETE FROM `sys_menu_sets` WHERE `set_name` = 'sys_wiki';
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_wiki', 'system', '_sys_menu_set_title_sys_wiki', 0);


DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_dashboard_manage_tools' AND `name` = 'audit-administration';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'system', 'audit-administration', '_sys_menu_item_title_system_audit_administration', '_sys_menu_item_title_audit_administration', 'page.php?i=audit-administration', '', '', 'comments', 'a:2:{s:6:"module";s:6:"system";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, 2);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_wiki';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_wiki', 'system', 'edit', '', '_sys_menu_item_title_wiki_edit', '', '', '', 'edit', '', '', 0, 2147483646, '', 1, 0, 1, 1),
('sys_wiki', 'system', 'delete-version', '', '_sys_menu_item_title_wiki_delete_version', '', '', '', 'times', '', '', 0, 2147483646, '', 1, 0, 1, 2),
('sys_wiki', 'system', 'delete-block', '', '_sys_menu_item_title_wiki_delete_block', '', '', '', 'times-circle', '', '', 0, 2147483646, '', 1, 0, 1, 3),
('sys_wiki', 'system', 'translate', '', '_sys_menu_item_title_wiki_translate', '', '', '', 'language', '', '', 0, 2147483646, '', 1, 0, 1, 4),
('sys_wiki', 'system', 'history', '', '_sys_menu_item_title_wiki_history', '', '', '', 'history', '', '', 0, 2147483646, '', 1, 0, 1, 5);

-- Grids

DELETE FROM `sys_objects_grid` WHERE `object` IN('sys_studio_forms_fields', 'sys_studio_categories', 'sys_audit_administration', 'sys_badges_administration');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_forms_fields', 'Sql', 'SELECT `tdi`.`id` AS `id`, `ti`.`caption_system` AS `caption_system`, `ti`.`caption` AS `caption`, `ti`.`type` AS `type`, `ti`.`module` AS `module`, `tdi`.`visible_for_levels` AS `visible_for_levels`, `tdi`.`active` AS `active`, `ti`.`editable` AS `editable`, `ti`.`deletable` AS `deletable`, `tdi`.`order` AS `order` FROM `sys_form_display_inputs` AS `tdi` LEFT JOIN `sys_form_inputs` AS `ti` ON `tdi`.`input_name`=`ti`.`name` AND `ti`.`object`=? WHERE 1 AND `tdi`.`display_name`=?', 'sys_form_display_inputs', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'ti`.`type', 'ti`.`caption_system', 'like', '', '', 'BxTemplStudioFormsFields', ''),
('sys_studio_categories', 'Sql', 'SELECT * FROM `sys_categories` WHERE 1 ', 'sys_categories', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'value', '', 'like', '', '', 'BxTemplStudioFormsCategories', ''),
('sys_audit_administration', 'Sql', 'SELECT * FROM `sys_audit` WHERE 1 ', 'sys_audit', 'id', 'added', '', '', 20, NULL, 'start', '', 'value', '', 'like', '', '', 'BxTemplAuditGrid', ''),
('sys_badges_administration', 'Sql', 'SELECT * FROM `sys_badges` WHERE 1 ', 'sys_badges', 'id', 'added', '', '', 20, NULL, 'start', '', 'text', '', 'like', '', '', 'BxTemplStudioBadgesGrid', '');

UPDATE `sys_objects_grid` SET `paginate_per_page` = 1000 WHERE `object` = 'sys_studio_labels';

DELETE FROM `sys_grid_fields` WHERE `object` IN('sys_studio_categories', 'sys_audit_administration', 'sys_badges_administration');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_categories', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('sys_studio_categories', 'switcher', '_adm_prm_txt_enable', '8%', 0, 0, '', 2),
('sys_studio_categories', 'value', '_adm_form_txt_categories_value', '25%', 0, 35, '', 3),
('sys_studio_categories', 'module', '_adm_form_txt_categories_module', '15%', 0, 35, '', 4),
('sys_studio_categories', 'author', '_adm_form_txt_categories_author', '15%', 0, 35, '', 5),
('sys_studio_categories', 'added', '_adm_form_txt_categories_added', '15%', 1, 25, '', 6),
('sys_studio_categories', 'actions', '', '20%', 0, 0, '', 7),

('sys_audit_administration', 'added', '_adm_form_txt_audit_added', '15%', 1, 25, '', 1),
('sys_audit_administration', 'profile', '_adm_form_txt_audit_profile', '15%', 1, 25, '', 2),
('sys_audit_administration', 'content', '_adm_form_txt_audit_content', '25%', 1, 25, '', 3),
('sys_audit_administration', 'module', '_adm_form_txt_audit_module', '15%', 1, 25, '', 4),
('sys_audit_administration', 'context', '_adm_pgt_txt_audit_context', '15%', 1, 25, '', 5),
('sys_audit_administration', 'action', '_adm_pgt_txt_audit_action', '15%', 1, 25, '', 6),

('sys_badges_administration', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('sys_badges_administration', 'view', '_adm_form_txt_badges_view', '15%', 1, 0, '', 2),
('sys_badges_administration', 'module', '_adm_form_txt_badges_module', '15%', 1, 25, '', 3),
('sys_badges_administration', 'text', '_adm_pgt_txt_badges_text', '28%', 1, 35, '', 4),
('sys_badges_administration', 'icon', '_adm_pgt_txt_badges_icon', '20%', 1, 0, '', 5),
('sys_badges_administration', 'actions', '', '20%', 0, 0, '', 6);

DELETE FROM `sys_grid_actions` WHERE `object` IN('sys_studio_categories', 'sys_badges_administration');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('sys_studio_categories', 'bulk', 'delete', '_adm_form_btn_categories_delete', '', 1, 1),
('sys_studio_categories', 'single', 'edit', '', 'pencil-alt', 0, 1),
('sys_studio_categories', 'single', 'delete', '', 'remove', 1, 2),
('sys_studio_categories', 'independent', 'add', '_adm_form_btn_categories_add', '', 0, 1),

('sys_badges_administration', 'bulk', 'delete', '_adm_form_btn_badges_delete', '', 1, 1),
('sys_badges_administration', 'single', 'edit', '', 'pencil-alt', 0, 1),
('sys_badges_administration', 'single', 'delete', '', 'remove', 1, 2),
('sys_badges_administration', 'single', 'delete_icon', '', '', 1, 3),
('sys_badges_administration', 'independent', 'add', '_adm_form_btn_badges_add', '', 0, 1);


UPDATE `sys_objects_grid` SET `filter_fields` = 'cmt_text,email' WHERE `object` = 'sys_cmts_administration';

-- Pages

DELETE FROM `sys_objects_page` WHERE `object` IN('sys_cmts_view', 'sys_audit');
INSERT INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('sys_cmts_view' ,'cmts-view', '', '_cmt_page_view_header', 'system', 1, 5, '', 2147483647, 1, 'page.php?i=cmts-view', '', '', '', 0, 1, 0, 'BxTemplCmtsPageView', ''),
('sys_audit' ,'audit-administration', '_sys_page_title_system_audit_administration', '_sys_page_title_audit_administration', 'system', 1, 5, '', 192, 1, 'page.php?i=audit-administration', '', '', '', 0, 1, 0, '', '');

ALTER TABLE  `sys_pages_blocks` CHANGE  `type`  `type` ENUM(  'raw',  'html',  'lang',  'image',  'rss',  'menu',  'service',  'wiki' ) NOT NULL DEFAULT  'raw';

DELETE FROM `sys_pages_blocks` WHERE `module` = 'skeletons' AND `type` = 'wiki';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'skeletons', '_sys_block_type_wiki', 11, 2147483647, 'wiki', '', 0, 1, 1, 0);

-- Pages Blocks

UPDATE `sys_pages_blocks` SET `content` = '<style>\r\n    /*--- Splash ---*/\r\n  	.bx-page {\r\n        position: relative;\r\n  	}\r\n    .bx-splash-block {\r\n        position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n      	-webkit-align-items: center;\r\n        align-items: center;\r\n    }\r\n    .bx-spl-preload {\r\n        position: absolute;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 1px;\r\n        height: 1px;\r\n\r\n        overflow: hidden;\r\n    }\r\n    .bx-spl-line {\r\n      	position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: stretch;\r\n        align-items: stretch;\r\n    }\r\n  	.bx-media-phone .bx-spl-line {\r\n      	-webkit-flex-direction: column;\r\n      	flex-direction: column;\r\n    }\r\n  	.bx-spl-cell {\r\n      	position: relative;\r\n  	}\r\n  	.bx-media-phone .bx-spl-cell {\r\n      	-webkit-basis: 100% !important; \r\n      	flex-basis: 100% !important;\r\n      	width: 100% !important;\r\n  	}\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-cell {\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: center;\r\n        align-items: center;\r\n    }\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n        -webkit-flex: 1 1 70%; \r\n        flex:  1 1 70%;\r\n      	width: 70%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n      	text-align: center;\r\n  	}\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c2 {\r\n        -webkit-flex: 0 0 30%; \r\n        flex:  0 1 30%;\r\n      	-webkit-justify-content: center;\r\n        justify-content: center;\r\n      	width: 30%;\r\n    }\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-image {\r\n      	max-width: 100%;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n        -webkit-flex: 1 1 33%; \r\n        flex:  1 1 33%;\r\n      	width: 33%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n      	text-align: center;\r\n  	}\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n		position: relative;\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: flex-start;\r\n        align-items: flex-start;\r\n      	justify-content: flex-start;\r\n      	-webkit-justify-content: flex-start;\r\n    }\r\n    .bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n      	justify-content: center;\r\n      	-webkit-justify-content: center;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon .animation {\r\n        width: 4.25rem;\r\n        height: 4.25rem;\r\n    }\r\n</style>\r\n<div class=\"bx-page bx-def-color-bg-page\">\r\n  <div class=\"bx-splash-block\">\r\n      <div class=\"bx-splash-cnt bx-def-page-width bx-def-centered bx-def-padding-leftright\">\r\n          <div class=\"bx-spl-preload\">\r\n            <img src=\"<bx_image_url:spl-image-main.svg />\">\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l1\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-title bx-def-font-h1 bx-def-font-semibold\"><bx_text:_sys_txt_splash_title /></div>\r\n                <div class=\"bx-spl-slogan bx-def-padding-sec-top bx-def-padding-bottom bx-def-font-grayed\"><bx_text:_sys_txt_splash_slogan /></div>\r\n                <div class=\"bx-spl-image bx-def-padding-top\">\r\n                  <img class=\"bx-spl-image\" src=\"<bx_image_url:spl-image-main.svg />\" />\r\n                </div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2 bx-hide-when-logged-in\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">__join_form_in_box__</div>\r\n            </div>\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l2 bx-def-padding\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon connect\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_connect /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_connect_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon share\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_share /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_share_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c3\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon create\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_create /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_create_text /></div>\r\n              </div>\r\n            </div>\r\n          </div>\r\n      </div>\r\n  </div>\r\n</div>\r\n<script>\r\n  var animConnect = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.connect .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-connect.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animShare = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.share .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-share.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animCreate = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.create .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-create.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n</script>' WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_homepage_splash' AND `content` = '<style>\r\n    /*--- Splash ---*/\r\n  	.bx-page {\r\n        position: relative;\r\n  	}\r\n    .bx-splash {\r\n        position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n      	-webkit-align-items: center;\r\n        align-items: center;\r\n      	min-height: 100vh;\r\n    }\r\n    .bx-spl-preload {\r\n        position: absolute;\r\n\r\n        top: 0px;\r\n        left: 0px;\r\n        width: 1px;\r\n        height: 1px;\r\n\r\n        overflow: hidden;\r\n    }\r\n    .bx-spl-line {\r\n      	position: relative;\r\n        display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: stretch;\r\n        align-items: stretch;\r\n    }\r\n  	.bx-media-phone .bx-spl-line {\r\n      	-webkit-flex-direction: column;\r\n      	flex-direction: column;\r\n    }\r\n  	.bx-spl-cell {\r\n      	position: relative;\r\n  	}\r\n  	.bx-media-phone .bx-spl-cell {\r\n      	-webkit-basis: 100% !important; \r\n      	flex-basis: 100% !important;\r\n      	width: 100% !important;\r\n  	}\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-cell {\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: center;\r\n        align-items: center;\r\n    }\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n        -webkit-flex: 1 1 70%; \r\n        flex:  1 1 70%;\r\n      	width: 70%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c1 {\r\n      	text-align: center;\r\n  	}\r\n  	.bx-spl-line.bx-spl-l1 .bx-spl-cell.bx-spl-c2 {\r\n        -webkit-flex: 0 0 30%; \r\n        flex:  0 1 30%;\r\n      	-webkit-justify-content: center;\r\n        justify-content: center;\r\n      	width: 30%;\r\n    }\r\n    .bx-spl-line.bx-spl-l1 .bx-spl-image {\r\n      	max-width: 100%;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n        -webkit-flex: 1 1 33%; \r\n        flex:  1 1 33%;\r\n      	width: 33%;\r\n    }\r\n  	.bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cell {\r\n      	text-align: center;\r\n  	}\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n		position: relative;\r\n      	display: -webkit-flex;\r\n        display: flex;\r\n        -webkit-align-items: flex-start;\r\n        align-items: flex-start;\r\n      	justify-content: flex-start;\r\n      	-webkit-justify-content: flex-start;\r\n    }\r\n    .bx-media-phone .bx-spl-line.bx-spl-l2 .bx-spl-cicon {\r\n      	justify-content: center;\r\n      	-webkit-justify-content: center;\r\n    }\r\n    .bx-spl-line.bx-spl-l2 .bx-spl-cicon .animation {\r\n        width: 4.25rem;\r\n        height: 4.25rem;\r\n    }\r\n</style>\r\n<div class=\"bx-page bx-def-color-bg-page\">\r\n  <div class=\"bx-splash\">\r\n      <div class=\"bx-splash-cnt bx-def-page-width bx-def-centered bx-def-padding-leftright\">\r\n          <div class=\"bx-spl-preload\">\r\n            <img src=\"<bx_image_url:spl-image-main.svg />\">\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l1\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-title bx-def-font-h1 bx-def-font-semibold\"><bx_text:_sys_txt_splash_title /></div>\r\n                <div class=\"bx-spl-slogan bx-def-padding-sec-top bx-def-padding-bottom bx-def-font-grayed\"><bx_text:_sys_txt_splash_slogan /></div>\r\n                <div class=\"bx-spl-image bx-def-padding-top\">\r\n                  <img class=\"bx-spl-image\" src=\"<bx_image_url:spl-image-main.svg />\" />\r\n                </div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2 bx-hide-when-logged-in\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">__join_form_in_box__</div>\r\n            </div>\r\n          </div>\r\n          <div class=\"bx-spl-line bx-spl-l2 bx-def-padding\">\r\n            <div class=\"bx-spl-cell bx-spl-c1\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon connect\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_connect /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_connect_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c2\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon share\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_share /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_share_text /></div>\r\n              </div>\r\n            </div>\r\n            <div class=\"bx-spl-cell bx-spl-c3\">\r\n              <div class=\"bx-spl-ccnt bx-def-padding\">\r\n                <div class=\"bx-spl-cicon create\"><div class=\"animation\"></div></div>\r\n                <div class=\"bx-spl-ctitle bx-def-padding-sec-topbottom bx-def-font-h2\"><bx_text:_sys_txt_splash_create /></div>\r\n                <div class=\"bx-spl-ctext bx-def-font-grayed\"><bx_text:_sys_txt_splash_create_text /></div>\r\n              </div>\r\n            </div>\r\n          </div>\r\n      </div>\r\n  </div>\r\n</div>\r\n<script>\r\n  var animConnect = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.connect .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-connect.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animShare = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.share .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-share.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n  var animCreate = bodymovin.loadAnimation({\r\n    container: $(\'.bx-spl-cicon.create .animation\').get(0),\r\n    path: \'<bx_image_url:spl-icon-create.json />\',\r\n    renderer: \'svg\',\r\n    loop: true,\r\n    autoplay: true,\r\n  });\r\n</script>';

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_profile_stats' AND `cell_id` = 1 AND `active` = 0;
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_create_post' AND `cell_id` = 1 AND `active` = 0;

SET @iMaxOrder = (SELECT MAX(`order`) FROM `sys_pages_blocks` WHERE `cell_id` = 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_home', 1, 'system', '', '_sys_page_block_title_profile_stats', 3, 2147483644, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:13:"profile_stats";s:6:"params";a:0:{}s:5:"class";s:20:"TemplServiceProfiles";}', 0, 0, 0, @iMaxOrder + 1),
('sys_home', 1, 'system', '_sys_page_block_title_sys_create_post', '_sys_page_block_title_create_post', 3, 2147483644, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_create_post_form";s:6:"params";a:1:{i:0;i:0;}s:5:"class";s:13:"TemplServices";}', 0, 0, 0, @iMaxOrder + 2);

DELETE FROM `sys_pages_blocks` WHERE `object` IN('sys_cmts_view', 'sys_audit');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_cmts_view', 1, 'system', '', '_cmt_page_view_title', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"get_block_view";s:6:"params";a:3:{i:0;s:8:"{system}";i:1;s:11:"{object_id}";i:2;s:12:"{comment_id}";}s:5:"class";s:17:"TemplCmtsServices";}', 0, 0, 1, 1),
('sys_audit', 1, 'system', '_sys_page_block_title_system_audit_administration', '_sys_page_block_title_audit_administration', 11, 192, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:12:"manage_tools";s:6:"params";a:1:{i:0;s:14:"administration";}s:5:"class";s:18:"TemplAuditServices";}', 0, 1, 1, 1);

-- Categories

CREATE TABLE IF NOT EXISTS `sys_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `module` varchar(32) NOT NULL,
  `value` varchar(100) NOT NULL,
  `status` enum ('active', 'hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_categories2objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL,
  `object_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

--  Wiki

CREATE TABLE IF NOT EXISTS `sys_objects_wiki` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `uri` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `module` varchar(32) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`),
  UNIQUE KEY `uri` (`uri`)
);

DELETE FROM `sys_objects_wiki` WHERE `object` = 'system';
INSERT INTO `sys_objects_wiki` (`object`, `uri`, `title`, `module`, `override_class_name`, `override_class_file`) VALUES
('system', 'sys', '_sys_wiki_system_title', 'system', '', '');


CREATE TABLE IF NOT EXISTS `sys_pages_wiki_blocks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL,
  `revision` int(11) NOT NULL,
  `language` varchar(5) NOT NULL,
  `main_lang` tinyint(4) NOT NULL DEFAULT '0',
  `profile_id` int(10) UNSIGNED NOT NULL,
  `content` mediumtext NOT NULL,
  `unsafe` tinyint(4) NOT NULL DEFAULT '0',
  `notes` varchar(255) NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block_lang_rev` (`block_id`,`language`,`revision`)
);


-- Rewrite Rules

CREATE TABLE IF NOT EXISTS `sys_rewrite_rules` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `preg` varchar(255) NOT NULL,
  `service` varchar(255) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
);

DELETE FROM `sys_rewrite_rules` WHERE `preg` = '^sys-action/(.*)$';
INSERT INTO `sys_rewrite_rules` (`preg`, `service`, `active`) VALUES
('^sys-action/(.*)$', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"wiki_action";s:6:"params";a:2:{i:0;s:3:"sys";i:1;s:3:"{1}";}s:5:"class";s:16:"TemplServiceWiki";}', '1');


-- Preloader

SET @iMaxOrder = (SELECT MAX(`order`) FROM `sys_preloader` WHERE `type` = 'js_system' AND `order` < 10000);
DELETE FROM `sys_preloader` WHERE `type` = 'js_system' AND `content` = 'BxDolMenuMoreAuto.js';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'BxDolMenuMoreAuto.js', 1, @iMaxOrder + 1);

-- Studio Widgets

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` IN('audit', 'badges');

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'audit', '_adm_page_cpt_audit', '_adm_page_cpt_audit', 'wi-audit.svg');
SET @iIdAudit = LAST_INSERT_ID();

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'badges', '_adm_page_cpt_badges', '_adm_page_cpt_badges', 'wi-badges.svg');
SET @iIdBadges = LAST_INSERT_ID();

SET @iIdHome = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdAudit, 'system', '{url_studio}audit.php', '', 'wi-audit.svg', '_adm_wgt_cpt_audit', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 11);

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdBadges, 'system', '{url_studio}badges.php', '', 'wi-badges.svg', '_adm_wgt_cpt_badges', '', '');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 11);

-- default bot profile

SET @iAccountIdBot = (SELECT `id` FROM `sys_accounts` WHERE `name` = 'Robot' AND `password` = 'una1100');
DELETE FROM `sys_accounts` WHERE `id` = @iAccountIdBot;
DELETE FROM `sys_profiles` WHERE `account_id` = @iAccountIdBot;


INSERT INTO `sys_accounts` (`name`, `profile_id`, `email`, `email_confirmed`, `receive_updates`, `receive_news`, `password`, `salt`, `role`, `added`) VALUES 
('Robot', 0, '', 0, 0, 0, 'una1100', '', 3, UNIX_TIMESTAMP());

SET @iAccountIdBot = LAST_INSERT_ID();

INSERT INTO `sys_profiles` (`account_id`, `type`, `content_id`, `status`) VALUES
(@iAccountIdBot, 'system', @iAccountIdBot, 'active');

SET @iProfileIdBot = LAST_INSERT_ID();

UPDATE `sys_options` SET `VALUE` = @iProfileIdBot WHERE `Name` = 'sys_profile_bot';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '11.0.0-B1' WHERE `version` = '10.1.0' AND `name` = 'system';

