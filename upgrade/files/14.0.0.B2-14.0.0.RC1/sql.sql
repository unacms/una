
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Cmts

DELETE FROM `sys_objects_cmts` WHERE `Name` = 'sys_agents_assistants_chats';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('sys_agents_assistants_chats', 'system', 'sys_agents_assistants_chats_messages', 1, 5000, 1000, 0, 9999, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'agents.php?page=assistants&aid={assistant_id}', '', 'sys_agents_assistants_chats', 'id', '', '', 'messages', 'BxDolStudioAgentsAsstChatsCmts', '');


-- Options

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


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'storage');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_storage_s3_amz_iam_role', '_adm_stg_cpt_option_sys_storage_s3_amz_iam_role', '', 'checkbox', '', '', '', 10),
(@iCategoryId, 'sys_storage_s3_acl_enable', '_adm_stg_cpt_option_sys_storage_s3_acl_enable', 'on', 'checkbox', '', '', '', 12),
(@iCategoryId, 'sys_storage_s3_force_auth_urls', '_adm_stg_cpt_option_sys_storage_s3_force_auth_urls', '', 'digit', '', '', '', 14);


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


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-RC1' WHERE (`version` = '14.0.0.B2' OR `version` = '14.0.0-B2') AND `name` = 'system';

