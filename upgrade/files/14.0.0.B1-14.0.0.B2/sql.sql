
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

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


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-B2' WHERE (`version` = '14.0.0.B1' OR `version` = '14.0.0-B1') AND `name` = 'system';

