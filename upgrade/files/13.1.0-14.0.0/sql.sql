
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- ------------------ 14.0.0.A1

-- FORMS

INSERT IGNORE INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_labels', 'system', 'list_context', '', '', 0, 'custom', '_sys_form_labels_input_caption_system_list_context', '_sys_form_labels_input_caption_list_context', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT IGNORE INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_labels_select', 'list_context', 2147483647, 1, 4);

-- ------------------ 14.0.0.A2

-- ACL

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

SET @iIdActionVoteView = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` = 'vote_view');

DELETE FROM `sys_acl_matrix` WHERE `IDAction` = @iIdActionVoteView AND `IDLevel` IN(@iUnauthenticated, @iAccount, @iUnconfirmed, @iPending);

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iUnauthenticated, @iIdActionVoteView),
(@iAccount, @iIdActionVoteView),
(@iUnconfirmed, @iIdActionVoteView),
(@iPending, @iIdActionVoteView);

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_translation' AND `content` IN('_sys_form_input_password_show', '_sys_form_input_password_hide');

INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_translation', '_sys_form_input_password_show', 1, 6),
('system', 'js_translation', '_sys_form_input_password_hide', 1, 7);

-- ------------------ 14.0.0.A3

-- Options

UPDATE `sys_options` SET `value` = '<html>\r\n    <head></head>\r\n    <body bgcolor="#fff" style="margin:0; padding:0;">\r\n        <div style="background-color:#fff;">\r\n            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>' WHERE `name` = 'site_email_html_template_header' AND `value` = '<html>\r\n    <head></head>\r\n    <body bgcolor="#eee" style="margin:0; padding:0;">\r\n        <div style="padding:20px; background-color:#eee;">\r\n            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">\r\n                <tr><td valign="top">\r\n                    <div style="color:#333; padding:20px; border:1px solid #ccc; border-radius:3px; background-color:#fff; font:14px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-bottom:2px solid #eee; padding-bottom:10px; margin-bottom:20px; font-weight:bold; font-size:22px; color:#999;">{site_name}</div>';

UPDATE `sys_options` SET `value` = '\r\n                    </div>\r\n                </td></tr>\r\n                <tr><td valign="top">\r\n                    <div style="color:#999; padding:0 20px 20px 20px; font:11px Helvetica, Arial, sans-serif;">\r\n                        <div style="border-top:2px solid #eee; padding-top:10px;">{about_us}&nbsp;&nbsp;&nbsp;{unsubscribe}</div>\r\n                    </div>\r\n                </td></tr>\r\n            </table>\r\n        </div>\r\n    </body>\r\n</html>' WHERE `name` = 'site_email_html_template_footer' AND `value` = '\r\n                    </div>\r\n                </td></tr>\r\n                <tr><td valign="top">\r\n                    <div style="margin-top:5px; text-align:center; color:#999; font:11px Helvetica, Arial, sans-serif;">{about_us}&nbsp;&nbsp;&nbsp;{unsubscribe}</div>\r\n                </td></tr>\r\n            </table>\r\n        </div>\r\n    </body>\r\n</html>';


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'security');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_cf_unauthenticated', '_adm_stg_cpt_option_sys_cf_unauthenticated', '1', 'list', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:30:"get_options_cf_unauthenticated";s:6:"params";a:0:{}s:5:"class";s:13:"TemplServices";}', '', '', 43);

 
-- Connections

INSERT IGNORE INTO `sys_objects_connection` (`object`, `table`, `profile_initiator`, `profile_content`, `type`, `override_class_name`, `override_class_file`) VALUES
('sys_profiles_bans', 'sys_profiles_conn_bans', 1, 1, 'one-way', 'BxDolBan', '');

CREATE TABLE IF NOT EXISTS `sys_profiles_conn_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(32) NOT NULL DEFAULT '',
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);

-- ------------------ 14.0.0.B1

-- Embeds

DELETE FROM `sys_objects_embeds` WHERE `object` = 'sys_system';
INSERT INTO `sys_objects_embeds` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_system', 'System', 'BxTemplEmbedSystem', '');

CREATE TABLE IF NOT EXISTS `sys_embeded_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `data` text DEFAULT NULL,
  `added` int(11) DEFAULT NULL,
  `theme` varchar(10) DEFAULT NULL,
  PRIMARY KEY (id)
);

-- Viewer

DELETE FROM `sys_objects_file_handlers` WHERE `object` IN('sys_sounds_viewer', 'sys_videos_viewer');
INSERT INTO `sys_objects_file_handlers` (`object`, `title`, `preg_ext`, `active`, `order`, `override_class_name`, `override_class_file`) VALUES
('sys_sounds_viewer', '_sys_file_handlers_sounds_viewer', '/\\.(mp3|m4a|m4b|wma|wav|3gp)$/i', 1, 5, 'BxTemplFileHandlerSoundsViewer', ''),
('sys_videos_viewer', '_sys_file_handlers_videos_viewer', '/\\.(avi|flv|mpg|mpeg|wmv|mp4|m4v|mov|qt|divx|xvid|3gp|3g2|webm|mkv|ogv|ogg|rm|rmvb|asf|drc|ts)$/i', 1, 5, 'BxTemplFileHandlerVideosViewer', '');


-- Options

UPDATE `sys_options_categories` SET `order` = 6 WHERE `name` = 'cache';
UPDATE `sys_options_categories` SET `order` = 4 WHERE `name` = 'site_settings';
UPDATE `sys_options_categories` SET `order` = 5 WHERE `name` = 'general';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_std_show_header_left', '_adm_stg_cpt_option_sys_std_show_header_left', '', 'checkbox', '', '', '', '', 270),
(@iCategoryId, 'sys_std_show_header_center', '_adm_stg_cpt_option_sys_std_show_header_center', 'on', 'checkbox', '', '', '', '', 271),
(@iCategoryId, 'sys_std_show_header_right', '_adm_stg_cpt_option_sys_std_show_header_right', 'on', 'checkbox', '', '', '', '', 272),
(@iCategoryId, 'sys_std_show_header_right_search', '_adm_stg_cpt_option_sys_std_show_header_right_search', 'on', 'checkbox', '', '', '', '', 275),
(@iCategoryId, 'sys_std_show_header_right_site', '_adm_stg_cpt_option_sys_std_show_header_right_site', 'on', 'checkbox', '', '', '', '', 276),
(@iCategoryId, 'sys_std_show_launcher_left', '_adm_stg_cpt_option_sys_std_show_launcher_left', '', 'checkbox', '', '', '', '', 280);

UPDATE `sys_options` SET `value` = 'sys_system' WHERE `name` = 'sys_embed_default' AND `value` = 'sys_oembed';

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_metatags_hashtags_only', '_adm_stg_cpt_option_sys_metatags_hashtags_only', '', 'checkbox', '', '', '', 30);

UPDATE `sys_options` SET `order` = 31 WHERE `name` = 'sys_metatags_hashtags_max';
UPDATE `sys_options` SET `order` = 32 WHERE `name` = 'sys_metatags_mentions_max';

UPDATE `sys_options` SET `type` = 'list' WHERE `name` = 'sys_account_accounts_pruning';
UPDATE `sys_options` SET `value` = '' WHERE `name` =  'sys_account_accounts_pruning' AND `value` = 'no';


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'api_general');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_api_url_root_email', '_adm_stg_cpt_option_sys_api_url_root_email', '', 'digit', '', '', '', 50),
(@iCategoryId, 'sys_api_url_root_push', '_adm_stg_cpt_option_sys_api_url_root_push', '', 'digit', '', '', '', 51);


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'api_layout');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_api_comments_modal', '_adm_stg_cpt_option_sys_api_comments_modal', '', 'checkbox', '', '', '', 15);


SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

INSERT IGNORE INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'api_config', '_adm_stg_cpt_category_api_config', 1, 3);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'api_config');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_api_config', '_adm_stg_cpt_option_sys_api_config', '', 'text', '', '', '', 1);

-- Menus

SET @iMaxIdTemplate = (SELECT IFNULL(MAX(`id`), 0) FROM `sys_menu_templates`);

UPDATE `sys_menu_templates` SET `id` = @iMaxIdTemplate + 1 WHERE `id` = 32 AND `template` != 'menu_multilevel.html';

INSERT IGNORE INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(32, 'menu_multilevel.html', '_sys_menu_template_title_multilevel', 1);

INSERT IGNORE INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_site_manage', '_sys_menu_title_manage', '', 'system', 1, 0, 1, 'BxTemplMenuManage', '');

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_studio_account_popup' AND `name` = 'site';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_studio_account_popup', 'system', 'site', '_sys_menu_item_title_system_sa_site', '_sys_menu_item_title_sa_site', '{url_root}', '', '', 'ami-site.svg', '', 2147483647, 0, 0, 0, 2);

UPDATE `sys_menu_items` SET `order` = 3 WHERE `set_name` = 'sys_studio_account_popup' AND `name` = 'edit';
UPDATE `sys_menu_items` SET `order` = 4 WHERE `set_name` = 'sys_studio_account_popup' AND `name` = 'language';
UPDATE `sys_menu_items` SET `order` = 5 WHERE `set_name` = 'sys_studio_account_popup' AND `name` = 'logout';

-- Pages

UPDATE `sys_objects_page` SET `url` = 'page.php?i=home' WHERE `object` = 'sys_home';

ALTER TABLE `sys_pages_blocks` CHANGE `type` `type` ENUM('raw','html','creative','lang','image','rss','menu','custom','service','wiki') NOT NULL DEFAULT 'raw';

DELETE FROM `sys_pages_blocks` WHERE `object` = '' AND `module` = 'skeletons' AND `title` = '_sys_block_type_creative';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'skeletons', '_sys_block_type_creative', 11, 2147483647, 'creative', '', 0, 1, 1, 0);

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_cmts_view' AND `title_system` IN ('_sys_page_block_title_system_cmts_view', '_sys_page_block_title_system_cmts_view_author');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `active_api`, `order`) VALUES
('sys_cmts_view', 1, 'system', '_sys_page_block_title_system_cmts_view_author', '_cmt_page_view_title_author', 11, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:16:"get_block_author";s:6:"params";a:3:{i:0;s:8:"{system}";i:1;s:11:"{object_id}";i:2;s:12:"{comment_id}";}s:5:"class";s:17:"TemplCmtsServices";}', 0, 0, 0, 1, 1),
('sys_cmts_view', 1, 'system', '_sys_page_block_title_system_cmts_view', '_cmt_page_view_title', 11, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"get_block_view";s:6:"params";a:3:{i:0;s:8:"{system}";i:1;s:11:"{object_id}";i:2;s:12:"{comment_id}";}s:5:"class";s:17:"TemplCmtsServices";}', 0, 0, 1, 1, 2);

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_system' AND `content` IN('htmx/htmx.min.js', 'htmx/head-support.js', 'htmx/preload.js');
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'htmx/htmx.min.js', 1, 13),
('system', 'js_system', 'htmx/head-support.js', 1, 14),
('system', 'js_system', 'htmx/preload.js', 1, 15);

-- ------------------ 14.0.0.B2

-- Cmts

DELETE FROM `sys_objects_cmts` WHERE `Name` = 'sys_agents_automators';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('sys_agents_automators', 'system', 'sys_agents_automators_messages', 1, 5000, 1000, 0, 9999, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'agents.php?page=automators&id={object_id}', '', 'sys_agents_automators', 'id', '', '', 'messages', 'BxDolStudioAgentsAutomatorsCmts', '');


-- Options

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_std_show_header_left_search', '_adm_stg_cpt_option_sys_std_show_header_left_search', 'on', 'checkbox', '', '', '', '', 271);

UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_std_show_header_left';
UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_std_show_header_right_search';

UPDATE `sys_options` SET `order` = 275 WHERE `name` = 'sys_std_show_header_center';
UPDATE `sys_options` SET `order` = 280 WHERE `name` = 'sys_std_show_header_right';
UPDATE `sys_options` SET `order` = 281 WHERE `name` = 'sys_std_show_header_right_search';
UPDATE `sys_options` SET `order` = 282 WHERE `name` = 'sys_std_show_header_right_site';
UPDATE `sys_options` SET `order` = 285 WHERE `name` = 'sys_std_show_launcher_left';


DELETE FROM `sys_options` WHERE `name` = 'sys_api_comments_modal';


SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');
INSERT IGNORE INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'agents_general', '_adm_stg_cpt_category_agents_general', 1, 1);

SET @iCategoryIdAgents = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'agents_general');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdAgents, 'sys_agents_model', '_adm_stg_cpt_option_sys_agents_model', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:24:"get_options_agents_model";s:5:"class";s:13:"TemplServices";}', '', '', 0),
(@iCategoryIdAgents, 'sys_agents_url', '_adm_stg_cpt_option_sys_agents_url', '', 'digit', '', '', '', 10),
(@iCategoryIdAgents, 'sys_agents_profile', '_adm_stg_cpt_option_sys_agents_profile', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:26:"get_options_agents_profile";s:5:"class";s:13:"TemplServices";}', '', '', 20);


-- Menus

UPDATE `sys_menu_items` SET `addon` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:23:"profile_following_count";s:6:"params";a:2:{i:0;i:0;i:1;b:1;}s:5:"class";s:20:"TemplServiceProfiles";}' WHERE `set_name` =  'sys_con_submenu' AND `name` = 'following';

-- Grids

INSERT IGNORE INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_cmts_administration', 'cmt_module', '_sys_cmts_administration_grid_column_title_adm_cmt_module', '10%', 0, '25', '', 4);

UPDATE `sys_grid_fields` SET `order` = 5 WHERE `object` = 'sys_cmts_administration' AND `name` = 'cmt_text';
UPDATE `sys_grid_fields` SET `order` = 6, `width` = '10%' WHERE `object` = 'sys_cmts_administration' AND `name` = 'cmt_time';
UPDATE `sys_grid_fields` SET `order` = 7, `width` = '15%' WHERE `object` = 'sys_cmts_administration' AND `name` = 'cmt_author_id';
UPDATE `sys_grid_fields` SET `order` = 8 WHERE `object` = 'sys_cmts_administration' AND `name` = 'actions';


-- Grids: Automators

INSERT IGNORE INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_automators', 'Sql', 'SELECT * FROM `sys_agents_automators` WHERE 1 ', 'sys_agents_automators', 'id', 'added', 'active', '', 20, NULL, 'start', '', '', '', 'like', '', '', 'BxTemplStudioAgentsAutomators', '');

INSERT IGNORE INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_automators', 'checkbox', '', '2%', 0, 0, '', '', 1),
('sys_studio_agents_automators', 'switcher', '_sys_agents_automators_txt_active', '8%', 0, 0, '', '', 2),
('sys_studio_agents_automators', 'name', '_sys_agents_automators_txt_name', '10%', 0, 0, '', '', 3),
('sys_studio_agents_automators', 'type', '_sys_agents_automators_txt_type', '8%', 0, 0, '', '', 4),
('sys_studio_agents_automators', 'model_id', '_sys_agents_automators_txt_model_id', '8%', 0, 0, '', '', 5),
('sys_studio_agents_automators', 'profile_id', '_sys_agents_automators_txt_profile_id', '10%', 0, 0, '', '', 6),
('sys_studio_agents_automators', 'message_id', '_sys_agents_automators_txt_message_id', '14%', 0, 32, '', '', 7),
('sys_studio_agents_automators', 'messages', '_sys_agents_automators_txt_messages', '10%', 0, 0, '', '', 8),
('sys_studio_agents_automators', 'added', '_sys_agents_automators_txt_added', '5%', 0, 0, '', '', 9),
('sys_studio_agents_automators', 'status', '_sys_agents_automators_txt_status', '5%', 0, 0, '', '', 10),
('sys_studio_agents_automators', 'actions', '', '20%', 0, 0, '', '', 11);

INSERT IGNORE INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_agents_automators', 'bulk', 'delete', '_Delete', '', 0, 1, 1),
('sys_studio_agents_automators', 'single', 'tune', '_sys_agents_automators_btn_tune', '', 0, 0, 1),
('sys_studio_agents_automators', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 2),
('sys_studio_agents_automators', 'single', 'delete', '_Delete', 'remove', 1, 1, 3),
('sys_studio_agents_automators', 'independent', 'add', '_sys_agents_automators_btn_add', '', 0, 0, 1);


-- Grids: Agents Helpers

INSERT IGNORE INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_helpers', 'Sql', 'SELECT * FROM `sys_agents_helpers` WHERE 1 ', 'sys_agents_helpers', 'id', 'added', 'active', '', 20, NULL, 'start', '', '', '', 'like', '', '', 2147483647, 1, 1, 'BxTemplStudioAgentsHelpers', '');

INSERT IGNORE INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_helpers', 'checkbox', '', '2%', 0, 0, '', '', 1),
('sys_studio_agents_helpers', 'switcher', '_sys_agents_helpers_txt_active', '8%', 0, 0, '', '', 2),
('sys_studio_agents_helpers', 'name', '_sys_agents_helpers_txt_name', '15%', 0, 0, '', '', 3),
('sys_studio_agents_helpers', 'model_id', '_sys_agents_helpers_txt_model_id', '10%', 0, 0, '', '', 5),
('sys_studio_agents_helpers', 'profile_id', '_sys_agents_helpers_txt_profile_id', '10%', 0, 0, '', '', 6),
('sys_studio_agents_helpers', 'prompt', '_sys_agents_helpers_txt_prompt', '25%', 0, 32, '', '', 7),
('sys_studio_agents_helpers', 'added', '_sys_agents_helpers_txt_added', '10%', 0, 0, '', '', 8),
('sys_studio_agents_helpers', 'actions', '', '20%', 0, 0, '', '', 9);

INSERT IGNORE INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `active`, `order`) VALUES
('sys_studio_agents_helpers', 'independent', 'add', '_sys_agents_helpers_btn_add', '', 0, 0, 1, 1),
('sys_studio_agents_helpers', 'single', 'tune', '_sys_agents_helpers_btn_tune', '', 0, 0, 1, 1),
('sys_studio_agents_helpers', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 1, 2),
('sys_studio_agents_helpers', 'single', 'delete', '_Delete', 'remove', 1, 1, 1, 3),
('sys_studio_agents_helpers', 'bulk', 'delete', '_Delete', '', 0, 1, 1, 1);


-- Grids: Agents Providers

INSERT IGNORE INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_providers', 'Sql', 'SELECT `tp`.*, `tpt`.`title` AS `provider_type` FROM `sys_agents_providers` AS `tp` LEFT JOIN `sys_agents_provider_types` AS `tpt` ON `tp`.`type_id`=`tpt`.`id` WHERE 1 ', 'sys_agents_providers', 'id', 'order', 'active', '', 100, NULL, 'start', '', 'tp`.`title,tpt`.`name,tpt`.`title', '', 'like', '', '', 2147483647, 'BxTemplStudioAgentsProviders', '');

INSERT IGNORE INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_agents_providers', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('sys_studio_agents_providers', 'switcher', '_sys_agents_providers_txt_active', '8%', 0, '', '', 2),
('sys_studio_agents_providers', 'name', '_sys_agents_providers_txt_provider_name', '30%', 0, 32, '', 3),
('sys_studio_agents_providers', 'provider_type', '_sys_agents_providers_txt_provider_type', '20%', 1, 16, '', 4),
('sys_studio_agents_providers', 'added', '_sys_agents_providers_txt_added', '20%', 0, '', '', 5),
('sys_studio_agents_providers', 'actions', '', '20%', 0, '', '', 6);

INSERT IGNORE INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_agents_providers', 'independent', 'add', '_sys_agents_providers_btn_add', '', 0, 0, 1),
('sys_studio_agents_providers', 'single', 'info', '_sys_agents_providers_btn_info', 'info', 1, 0, 1),
('sys_studio_agents_providers', 'single', 'edit', '_sys_agents_providers_btn_edit', 'pencil-alt', 1, 0, 2),
('sys_studio_agents_providers', 'single', 'delete', '_sys_agents_providers_btn_delete', 'remove', 1, 1, 3),
('sys_studio_agents_providers', 'bulk', 'delete', '_sys_agents_providers_btn_delete', '', 0, 1, 1);


-- Pages

ALTER TABLE `sys_pages_blocks` CHANGE `type` `type` ENUM('raw','html','creative','bento_grid','lang','image','rss','menu','custom','service','wiki') NOT NULL DEFAULT 'raw';

DELETE FROM `sys_pages_blocks` WHERE `type` = 'bento_grid';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'skeletons', '_sys_block_type_bento_grid', 11, 2147483647, 'bento_grid', '', 0, 1, 1, 0);

CREATE TABLE IF NOT EXISTS `sys_pages_blocks_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) NOT NULL DEFAULT 0,
  `content_id` int(11) NOT NULL DEFAULT 0,
  `content_module` varchar(32) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `block` (`block_id`, `content_id`, `content_module`)
);

-- Agents

CREATE TABLE IF NOT EXISTS `sys_agents_models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL default '',
  `title` varchar(64) NOT NULL default '',
  `key` varchar(64) NOT NULL default '',
  `params` text NOT NULL,
  `class_name` varchar(128) NOT NULL default '',
  `class_file` varchar(255) NOT NULL  default '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`)
);

TRUNCATE TABLE `sys_agents_models`;

INSERT INTO `sys_agents_models`(`name`, `title`, `key`, `params`, `class_name`, `class_file`) VALUES
('gpt-3.5-turbo', 'GPT-3.5-TURBO', '', '{"call":{"temperature":0.1}}', 'BxDolAIModelGpt35', ''),
('gpt-4o', 'GPT-4.O', '', '{"call":{},"assistants":{"event_init":"asst_HcEyaghqWZefkAyoEML40joY","event":"asst_wqaXtKjcsBKceMtJ2NxID2LT","scheduler_init":"asst_kEbDH1hUy2Y45nOKk9jaSTB8","scheduler":"asst_M6zOv4osQwZmRItaiYptjjOS","webhook_init":"asst_sSkOblPyXmYovS5IiEiVW17n","webhook":"asst_w7F3RiylJfdDEb9Eaa4RvO1q"}}', 'BxDolAIModelGpt40', '');

CREATE TABLE IF NOT EXISTS `sys_agents_automators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL default '',
  `model_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `type` enum('event','scheduler','webhook') NOT NULL DEFAULT 'event',
  `params` text NOT NULL,
  `alert_unit` varchar(128) NOT NULL default '',
  `alert_action` varchar(128) NOT NULL default '',
  `message_id` int(11) NOT NULL default '0',
  `code` text NOT NULL,
  `added` int(11) unsigned NOT NULL DEFAULT '0',
  `messages` int(11) NOT NULL default '0',
  `status` enum('auto','manual','ready') NOT NULL DEFAULT 'auto',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_automators_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `automator_id` int(11) NOT NULL DEFAULT '0',
  `provider_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_automators_helpers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `automator_id` int(11) NOT NULL DEFAULT '0',
  `helper_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_automators_messages` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  `cmt_cf` int(11) NOT NULL default '1',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_provider_types` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `title` varchar(128) NOT NULL default '',
  `option_prefix` varchar(32) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '0',
  `order` tinyint(4) NOT NULL default '0',
  `class_name` varchar(128) NOT NULL default '',
  `class_file` varchar(255) NOT NULL  default '',
  PRIMARY KEY(`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_provider_options` (
  `id` int(11) NOT NULL auto_increment,
  `provider_type_id` int(11) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `type` varchar(64) NOT NULL default 'text',
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `extra` varchar(255) NOT NULL default '',
  `check_type` varchar(64) NOT NULL default '',
  `check_params` varchar(128) NOT NULL default '',
  `check_error` varchar(128) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `name`(`name`)
);

TRUNCATE TABLE `sys_agents_provider_types`;
TRUNCATE TABLE `sys_agents_provider_options`;

INSERT INTO `sys_agents_provider_types`(`name`, `title`, `option_prefix`, `active`, `order`, `class_name`, `class_file`) VALUES
('shopify_admin', '_sys_agents_pvd_cpt_shopify_admin', 'shf_adm_', 1, 1, 'BxDolAIProviderShopifyAdmin', '');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `sys_agents_provider_options`(`provider_type_id`, `name`, `type`, `title`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'shf_adm_shop_domain', 'text', '_sys_agents_pvd_opt_cpt_shop_domain', '_sys_agents_pvd_opt_dsc_shop_domain', '', '', '', '', 1),
(@iProviderId, 'shf_adm_access_token', 'text', '_sys_agents_pvd_opt_cpt_access_token', '_sys_agents_pvd_opt_dsc_access_token', '', '', '', '', 2),
(@iProviderId, 'shf_adm_secret_key', 'text', '_sys_agents_pvd_opt_cpt_secret_key', '_sys_agents_pvd_opt_dsc_secret_key', '', '', '', '', 3),
(@iProviderId, 'shf_adm_webhook_url', 'value', '_sys_agents_pvd_opt_cpt_webhook_url', '_sys_agents_pvd_opt_dsc_webhook_url', '', '', '', '', 4);

CREATE TABLE IF NOT EXISTS `sys_agents_providers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(128) NOT NULL default '',
  `type_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY(`id`),
  UNIQUE KEY `name`(`name`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_providers_values` (
  `id` int(11) NOT NULL auto_increment,
  `provider_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',  
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY(`id`),
  UNIQUE KEY `value`(`provider_id`, `option_id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_helpers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `model_id` int(11) NOT NULL DEFAULT 0,
  `profile_id` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `prompt` text DEFAULT NULL,
  `added` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`)
);

-- Studio

UPDATE `sys_std_pages` SET `icon` = 'bc-home.svg' WHERE `name` = 'home';


SET @iIdAgentsPage = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'agents');
SET @iIdAgentsWidget = (SELECT `id` FROM `sys_std_widgets` WHERE `page_id` = @iIdAgentsPage);

DELETE FROM `sys_std_pages` WHERE `id` = @iIdAgentsPage;
DELETE FROM `sys_std_widgets` WHERE `id` = @iIdAgentsWidget;
DELETE FROM `sys_std_pages_widgets` WHERE `widget_id` = @iIdAgentsWidget;


INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'agents', '_adm_page_cpt_agents', '_adm_page_cpt_agents', 'wi-agents.svg');
SET @iIdAgents = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdAgents, 'system', 'configuration', '{url_studio}agents.php', '', 'wi-agents.svg', '_adm_wgt_cpt_agents', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');

SET @iIdHome = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 15);

-- ------------------ 14.0.0.RC1

-- Cmts

DELETE FROM `sys_objects_cmts` WHERE `Name` = 'sys_agents_assistants_chats';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('sys_agents_assistants_chats', 'system', 'sys_agents_assistants_chats_messages', 1, 5000, 1000, 0, 9999, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'agents.php?page=assistants&aid={assistant_id}', '', 'sys_agents_assistants_chats', 'id', '', '', 'messages', 'BxDolStudioAgentsAsstChatsCmts', '');


-- Options


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'storage');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_storage_s3_amz_iam_role', '_adm_stg_cpt_option_sys_storage_s3_amz_iam_role', '', 'checkbox', '', '', '', 10),
(@iCategoryId, 'sys_storage_s3_acl_enable', '_adm_stg_cpt_option_sys_storage_s3_acl_enable', 'on', 'checkbox', '', '', '', 12),
(@iCategoryId, 'sys_storage_s3_force_auth_urls', '_adm_stg_cpt_option_sys_storage_s3_force_auth_urls', '', 'digit', '', '', '', 14);

UPDATE `sys_options` SET `value` = '365' WHERE `name` = 'sys_account_accounts_pruning_interval' AND `value` = '0';

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'agents_general');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_agents_api_key', '_adm_stg_cpt_option_sys_agents_api_key', '', 'digit', '', '', '', 0);
UPDATE `sys_options` SET `order` = 10 WHERE `name` = 'sys_agents_model';
DELETE FROM `sys_options` WHERE `name` = 'sys_agents_url';


SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');
INSERT IGNORE INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES 
(@iTypeId, 'agents_usage', '_adm_stg_cpt_category_agents_usage', 1, 2);
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'agents_usage');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_agents_asst_chats_trans_del', '_adm_stg_cpt_option_sys_agents_asst_chats_trans_del', 'on', 'checkbox', '', '', '', 1),
(@iCategoryId, 'sys_agents_studio_assistant', '_adm_stg_cpt_option_sys_agents_sa', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:28:"get_options_studio_assistant";s:5:"class";s:13:"TemplServices";}', '', '', 10),
(@iCategoryId, 'sys_agents_live_search_assistant', '_adm_stg_cpt_option_sys_agents_lsa', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:33:"get_options_live_search_assistant";s:5:"class";s:13:"TemplServices";}', '', '', 15),
(@iCategoryId, 'sys_agents_ask_block_assistant', '_adm_stg_cpt_option_sys_agents_aba', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:31:"get_options_ask_block_assistant";s:5:"class";s:13:"TemplServices";}', '', '', 20);

-- GRIDS

-- GRID: Agents Assistants
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_assistants';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_assistants', 'Sql', 'SELECT * FROM `sys_agents_assistants` WHERE 1 ', 'sys_agents_assistants', 'id', 'added', 'active', '', 20, NULL, 'start', '', '', '', 'like', '', '', 2147483647, 1, 1, 'BxTemplStudioAgentsAssistants', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_assistants';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_assistants', 'checkbox', '', '2%', 0, 0, '', '', 1),
('sys_studio_agents_assistants', 'switcher', '_sys_agents_assistants_txt_active', '8%', 0, 0, '', '', 2),
('sys_studio_agents_assistants', 'name', '_sys_agents_assistants_txt_name', '15%', 0, 0, '', '', 3),
('sys_studio_agents_assistants', 'model_id', '_sys_agents_assistants_txt_model_id', '10%', 0, 0, '', '', 5),
('sys_studio_agents_assistants', 'profile_id', '_sys_agents_assistants_txt_profile_id', '10%', 0, 0, '', '', 6),
('sys_studio_agents_assistants', 'prompt', '_sys_agents_assistants_txt_prompt', '25%', 0, 32, '', '', 7),
('sys_studio_agents_assistants', 'added', '_sys_agents_assistants_txt_added', '10%', 0, 0, '', '', 8),
('sys_studio_agents_assistants', 'actions', '', '20%', 0, 0, '', '', 9);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_assistants';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `active`, `order`) VALUES
('sys_studio_agents_assistants', 'independent', 'add', '_sys_agents_assistants_btn_add', '', 0, 0, 1, 1),
('sys_studio_agents_assistants', 'single', 'chats', '_sys_agents_assistants_btn_chats', 'comments', 1, 0, 1, 1),
('sys_studio_agents_assistants', 'single', 'files', '_sys_agents_assistants_btn_files', 'folder', 1, 0, 1, 2),
('sys_studio_agents_assistants', 'single', 'codes', '_sys_agents_assistants_btn_codes', 'code', 1, 0, 1, 3),
('sys_studio_agents_assistants', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 1, 4),
('sys_studio_agents_assistants', 'single', 'delete', '_Delete', 'remove', 1, 1, 1, 5),
('sys_studio_agents_assistants', 'bulk', 'delete', '_Delete', '', 0, 1, 1, 1);

-- GRID: Agents Assistants Chats
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_assistants_chats';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_assistants_chats', 'Sql', 'SELECT * FROM `sys_agents_assistants_chats` WHERE 1 ', 'sys_agents_assistants_chats', 'id', 'added', '', '', 20, NULL, 'start', '', '', '', 'like', '', '', 2147483647, 1, 1, 'BxTemplStudioAgentsAsstChats', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_assistants_chats';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_agents_assistants_chats', 'checkbox', '', '2%', 0, 0, '', '', 1),
('sys_studio_agents_assistants_chats', 'name', '_sys_agents_assistants_chats_txt_name', '18%', 0, 0, '', '', 2),
('sys_studio_agents_assistants_chats', 'type', '_sys_agents_assistants_chats_txt_type', '5%', 0, 0, '', '', 3),
('sys_studio_agents_assistants_chats', 'description', '_sys_agents_assistants_chats_txt_description', '25%', 0, 16, '', '', 4),
('sys_studio_agents_assistants_chats', 'messages', '_sys_agents_assistants_chats_txt_messages', '10%', 0, 0, '', '', 5),
('sys_studio_agents_assistants_chats', 'added', '_sys_agents_assistants_chats_txt_added', '15%', 0, 0, '', '', 6),
('sys_studio_agents_assistants_chats', 'stored', '_sys_agents_assistants_chats_txt_stored', '15%', 0, 0, '', '', 7),
('sys_studio_agents_assistants_chats', 'actions', '', '20%', 0, 0, '', '', 8);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_assistants_chats';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `active`, `order`) VALUES
('sys_studio_agents_assistants_chats', 'independent', 'add', '_sys_agents_assistants_chats_btn_add', '', 0, 0, 1, 1),
('sys_studio_agents_assistants_chats', 'single', 'chat', '_sys_agents_assistants_chats_btn_chat', '', 0, 0, 1, 1),
('sys_studio_agents_assistants_chats', 'single', 'store', '_sys_agents_assistants_chats_btn_store', 'download', 1, 1, 1, 2),
('sys_studio_agents_assistants_chats', 'single', 'unstore', '_sys_agents_assistants_chats_btn_unstore', 'upload', 1, 1, 1, 3),
('sys_studio_agents_assistants_chats', 'single', 'edit', '_Edit', 'pencil-alt', 1, 0, 1, 4),
('sys_studio_agents_assistants_chats', 'single', 'delete', '_Delete', 'remove', 1, 1, 1, 5),
('sys_studio_agents_assistants_chats', 'bulk', 'delete', '_Delete', '', 0, 1, 1, 1);

-- GRID: Agents Assistants Files
DELETE FROM `sys_objects_grid` WHERE `object` = 'sys_studio_agents_assistants_files';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_agents_assistants_files', 'Sql', 'SELECT * FROM `sys_agents_assistants_files` WHERE 1 ', 'sys_agents_assistants_files', 'id', 'added', '', '', 20, NULL, 'start', '', '', '', 'like', '', '', 2147483647, 1, 1, 'BxTemplStudioAgentsAsstFiles', '');

DELETE FROM `sys_grid_fields` WHERE `object` = 'sys_studio_agents_assistants_files';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('sys_studio_agents_assistants_files', 'name', '_sys_agents_assistants_files_txt_name', '35%', 0, 32, '', 1),
('sys_studio_agents_assistants_files', 'size', '_sys_agents_assistants_files_txt_size', '15%', 0, 0, '', 2),
('sys_studio_agents_assistants_files', 'status', '_sys_agents_assistants_files_txt_status', '15%', 0, 0, '', 3),
('sys_studio_agents_assistants_files', 'added', '_sys_agents_assistants_files_txt_added', '15%', 0, 0, '', 4),
('sys_studio_agents_assistants_files', 'actions', '', '20%', 0, 0, '', 5);

DELETE FROM `sys_grid_actions` WHERE `object` = 'sys_studio_agents_assistants_files';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_agents_assistants_files', 'independent', 'add', '_sys_agents_assistants_files_btn_add', '', 0, 0, 1),
('sys_studio_agents_assistants_files', 'independent', 'sync', '_sys_agents_assistants_files_btn_sync', '', 0, 0, 2),
('sys_studio_agents_assistants_files', 'single', 'delete', '_sys_agents_assistants_files_btn_delete', 'remove', 1, 1, 1);

-- PAGES

DELETE FROM `sys_pages_blocks` WHERE  `title_system` = '_sys_page_block_title_sys_ask_aqssistant';
SET @iBlockOrder = IFNULL((SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1), 0);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'system', '_sys_page_block_title_sys_ask_aqssistant', '_sys_page_block_title_ask_aqssistant', 11, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:23:"get_block_ask_assistant";s:6:"params";a:1:{i:0;a:0:{}}s:5:"class";s:13:"TemplServices";}', 0, 1, 1, @iBlockOrder + 1);


-- AGENTS

CREATE TABLE IF NOT EXISTS `sys_agents_automators_assistants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `automator_id` int(11) NOT NULL DEFAULT '0',
  `assistant_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_assistants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `model_id` int(11) NOT NULL DEFAULT 0,
  `profile_id` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `prompt` text NOT NULL,
  `ai_vs_id` varchar(64) NOT NULL DEFAULT '',
  `ai_asst_id` varchar(64) NOT NULL DEFAULT '',
  `added` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `hidden` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name`(`name`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_assistants_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `assistant_id` int(11) NOT NULL DEFAULT 0,
  `added` int(11) NOT NULL DEFAULT 0,
  `ai_file_id` varchar(64) NOT NULL DEFAULT '',
  `ai_file_size` int(11) NOT NULL DEFAULT 0,
  `ai_file_status` varchar(64) NOT NULL DEFAULT 'in_progress',
  `locked` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_assistants_chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `type` tinyint(4) NOT NULL DEFAULT 1,
  `assistant_id` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `message_id` int(11) NOT NULL DEFAULT 0,
  `messages` int(11) NOT NULL DEFAULT 0,
  `added` int(11) NOT NULL DEFAULT 0,
  `ai_thread_id` varchar(64) NOT NULL DEFAULT '',
  `ai_file_id` varchar(64) NOT NULL DEFAULT '',
  `stored` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_agents_assistants_chats_messages` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  `cmt_cf` int(11) NOT NULL default '1',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

-- ------------------ 14.0.0.RC2

-- Options

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'account');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_account_remember_me', '_adm_stg_cpt_option_sys_account_remember_me', 'on', 'checkbox', '', '', '', 70);

-- Pages

DELETE FROM `sys_pages_blocks` WHERE `module` = 'system' AND `title_system` = '_sys_page_block_title_system_cmts_view_content';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `active_api`, `order`) VALUES
('sys_cmts_view', 1, 'system', '_sys_page_block_title_system_cmts_view_content', '_cmt_page_view_title_content', 11, 0, 0, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:17:"get_block_content";s:6:"params";a:3:{i:0;s:8:"{system}";i:1;s:11:"{object_id}";i:2;s:12:"{comment_id}";}s:5:"class";s:17:"TemplCmtsServices";}', 0, 0, 1, 1, 0);

-- ------------------ 14.0.0.RC3

-- PUSH objects

CREATE TABLE IF NOT EXISTS `sys_objects_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
);

INSERT IGNORE INTO `sys_objects_push` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_onesignal', 'OneSignal', 'BxTemplPushOneSignal', '');

-- SMS objects

CREATE TABLE IF NOT EXISTS `sys_objects_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
);

INSERT IGNORE INTO `sys_objects_sms` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_twilio', 'Twilio', 'BxDolSmsTwilio', '');


-- Options

UPDATE `sys_options_types` SET `icon` = 'mi-cog.svg' WHERE `name` = 'system';


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_x_frame_options', '_adm_stg_cpt_option_sys_x_frame_options', 'SAMEORIGIN', 'select', 'Off,SAMEORIGIN,DENY', '', '', '', 151);

UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_std_show_header_left';
UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_std_show_header_left_search';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_std_show_header_right_search';
UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_std_show_header_right_site';


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_enable_post_to_context_for_privacy', '_adm_stg_cpt_option_sys_enable_post_to_context_for_privacy', '', '', 'checkbox', '', '', '', 50),
(@iCategoryId, 'sys_check_fan_in_parent_context', '_adm_stg_cpt_option_sys_check_fan_in_parent_context', '', 'on', 'checkbox', '', '', '', 55);


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'notifications_push');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_push_default', '_adm_stg_cpt_option_sys_push_default', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:24:"get_options_push_default";s:5:"class";s:13:"TemplServices";}', '', '', 1);

UPDATE `sys_options` SET `name` = 'sys_push_onesignal_app_id', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_app_id', `order` = 21 WHERE `name` = 'sys_push_app_id';
UPDATE `sys_options` SET `name` = 'sys_push_onesignal_rest_api', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_rest_api', `order` = 22 WHERE `name` = 'sys_push_rest_api';
UPDATE `sys_options` SET `name` = 'sys_push_onesignal_short_name', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_short_name', `order` = 23 WHERE `name` = 'sys_push_short_name';
UPDATE `sys_options` SET `name` = 'sys_push_onesignal_safari_id', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_safari_id', `order` = 24 WHERE `name` = 'sys_push_safari_id';


UPDATE `sys_options_categories` SET `name` = 'sms', `caption` = '_adm_stg_cpt_category_sms' WHERE `name` = 'twilio_gate';
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'sms');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_sms_default', '_adm_stg_cpt_option_sys_sms_default', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:23:"get_options_sms_default";s:5:"class";s:13:"TemplServices";}', '', '', 1);

UPDATE `sys_options` SET `name` = 'sys_sms_twilio_sid', `caption` = '_adm_stg_cpt_option_sys_sms_twilio_sid', `order` = 11 WHERE `name` = 'sys_twilio_gate_sid';
UPDATE `sys_options` SET `name` = 'sys_sms_twilio_token', `caption` = '_adm_stg_cpt_option_sys_sms_twilio_token', `order` = 12 WHERE `name` = 'sys_twilio_gate_token';
UPDATE `sys_options` SET `name` = 'sys_sms_twilio_from_number', `caption` = '_adm_stg_cpt_option_sys_sms_twilio_from_number', `order` = 13 WHERE `name` = 'sys_twilio_gate_from_number';


-- BG jobs

CREATE TABLE IF NOT EXISTS `sys_background_jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `priority` tinyint(4) unsigned NOT NULL default '0',
  `service_call` text NOT NULL default '', 
  `status` varchar(16) NOT NULL default 'awaiting',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

-- Cron

DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_background_jobs';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_background_jobs', '* * * * *', 'BxDolCronBackgroundJobs', 'inc/classes/BxDolCronBackgroundJobs.php', '');

-- Menu

UPDATE `sys_menu_items` SET `active` = 1 WHERE `set_name` = 'sys_studio_account_popup' AND `name` = 'site';

-- Pages

ALTER TABLE `sys_pages_blocks` CHANGE `content` `content` MEDIUMTEXT NOT NULL;

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_create_account' AND `active_api` = 1 AND `content` = 'a:4:{s:6:"module";s:6:"system";s:6:"method"s:19:"create_account_form";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_login' AND `active_api` = 1 AND `content` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:10:"login_form";s:5:"class";s:17:"TemplServiceLogin";}';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_forgot_password' AND `active_api` = 1 AND `content` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"forgot_password";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}';

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `active_api`, `order`) VALUES
('sys_home', 1, 'system', '', '_sys_page_block_title_create_account', 11, 0, 0, 1, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method"s:19:"create_account_form";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 0, 0, 1, 0),
('sys_home', 1, 'system', '_sys_page_block_system_title_login', '_sys_page_block_title_login', 11, 0, 0, 1, 'service', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:10:"login_form";s:5:"class";s:17:"TemplServiceLogin";}', 0, 0, 0, 1, 0),
('sys_home', 1, 'system', '', '_sys_page_block_title_forgot_password', 13, 0, 0, 1, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"forgot_password";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 0, 0, 1, 0);

-- Logs

UPDATE `sys_objects_logs` SET `object` = 'sys_sms', `title` = '_sys_log_sms' WHERE `object` = 'sys_twilio';

DELETE FROM `sys_objects_logs` WHERE `object` = 'sys_background_jobs';
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('sys_background_jobs', 'system', 'Auto', '_sys_log_background_jobs', 1, '', '');

-- Preloader 

UPDATE `sys_preloader` SET `content` = 'moment-with-locales.min.js' WHERE `content` = 'moment-with-locales.js';

-- ------------------ 14.0.0.RC4

CREATE TABLE IF NOT EXISTS `sys_profiles_track` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL DEFAULT '0',
  `action` varchar(32) NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_track` (`profile_id`,`action`)
);

-- Menu

UPDATE `sys_menu_items` SET `link` = 'page.php?i=logout' WHERE `set_name` = 'sys_account_notifications' AND `name` = 'logout';

-- Grid

ALTER TABLE `sys_objects_grid` CHANGE `show_total_count` `show_total_count` TINYINT(4) NOT NULL DEFAULT '0';

UPDATE `sys_objects_grid` SET `show_total_count` = '0';

UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'sys_studio_agents_helpers';
UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'sys_studio_agents_assistants';
UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'sys_studio_agents_assistants_chats';
UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'sys_studio_agents_assistants_files';

UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'bx_payment_grid_carts';
UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'bx_payment_grid_cart';
UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'bx_payment_grid_commissions';
UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'bx_payment_grid_invoices';

UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'bx_ads_offers';
UPDATE `sys_objects_grid` SET `show_total_count` = '1' WHERE `object` = 'bx_accounts_administration';

-- ------------------ 14.0.0.RC5

-- Forms

DELETE FROM `sys_objects_form` WHERE `object` = 'sys_agents_comment';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_agents_comment', 'system', '_sys_form_agents_comment', 'cmts.php', 'a:3:{s:2:"id";s:20:"cmt-%s-form-%s-%d-%d";s:4:"name";s:20:"cmt-%s-form-%s-%d-%d";s:5:"class";s:14:"cmt-post-reply";}', 'cmt_submit', '', 'cmt_id', '', '', '', 0, 1, 'BxTemplCmtsForm', '');

DELETE FROM `sys_form_displays` WHERE `display_name` = 'sys_agents_comment_post';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_agents_comment_post', 'system', 'sys_agents_comment', '_sys_form_display_agents_comment_post', 0);

DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_agents_comment';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_agents_comment', 'system', 'sys', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_agents_comment', 'system', 'id', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_agents_comment', 'system', 'action', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_agents_comment', 'system', 'cmt_parent_id', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_cmt_parent_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_agents_comment', 'system', 'cmt_text', '', '', 0, 'textarea', '_sys_form_agents_comment_input_caption_system_cmt_text', '', '', 0, 0, 3, 'a:1:{s:12:"autocomplete";s:3:"off";}', '', '', '', '', '', 'XssHtml', '', 1, 0),
('sys_agents_comment', 'system', 'cmt_image', 'a:1:{i:0;s:14:"sys_cmts_html5";}', 'a:1:{s:14:"sys_cmts_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_sys_form_agents_comment_input_caption_system_cmt_image', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_agents_comment', 'system', 'cmt_submit', '_sys_form_agents_comment_input_caption_cmt_submit', '', 0, 'submit', '_sys_form_agents_comment_input_caption_system_cmt_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_agents_comment_post';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_agents_comment_post', 'sys', 2147483647, 1, 1),
('sys_agents_comment_post', 'id', 2147483647, 1, 2),
('sys_agents_comment_post', 'action', 2147483647, 1, 3),
('sys_agents_comment_post', 'cmt_parent_id', 2147483647, 1, 4),
('sys_agents_comment_post', 'cmt_text', 2147483647, 1, 5),
('sys_agents_comment_post', 'cmt_submit', 2147483647, 1, 6),
('sys_agents_comment_post', 'cmt_image', 2147483647, 1, 7);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0' WHERE `version` = '13.1.0' AND `name` = 'system';

