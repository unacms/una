
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

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

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-B1' WHERE (`version` = '14.0.0.A3' OR `version` = '14.0.0-A3') AND `name` = 'system';

