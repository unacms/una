
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- Settings

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_session_auth', '_adm_stg_cpt_option_sys_session_auth', '', 'checkbox', '', '', '', '', 112);


SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name` = 'system');

DELETE FROM `sys_options_categories` WHERE `name` = 'api';
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `hidden`, `order`) VALUES (@iTypeId, 'api', '_adm_stg_cpt_category_api', 1, 2);

SET @iCategoryIdApi = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'api');

DELETE FROM `sys_options` WHERE `name` IN('sys_api_enable', 'sys_api_access_by_origin', 'sys_api_access_by_key', 'sys_api_access_unsafe_services', 'sys_api_cookie_path', 'sys_api_cookie_secure', 'sys_api_cookie_samesite', 'sys_api_comments_flat', '');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdApi, 'sys_api_enable', '_adm_stg_cpt_option_sys_api_enable', '', 'checkbox', '', '', '', 1),
(@iCategoryIdApi, 'sys_api_access_by_origin', '_adm_stg_cpt_option_sys_api_access_by_origin', '', 'checkbox', '', '', '', 10),
(@iCategoryIdApi, 'sys_api_access_by_key', '_adm_stg_cpt_option_sys_api_access_by_key', '', 'checkbox', '', '', '', 20),
(@iCategoryIdApi, 'sys_api_access_unsafe_services', '_adm_stg_cpt_option_sys_api_access_unsafe_services', '', 'checkbox', '', '', '', 30),
(@iCategoryIdApi, 'sys_api_cookie_path', '_adm_stg_cpt_option_sys_api_cookie_path', '/', 'digit', '', '', '', 40),
(@iCategoryIdApi, 'sys_api_cookie_secure', '_adm_stg_cpt_option_sys_api_cookie_secure', '', 'checkbox', '', '', '', 42),
(@iCategoryIdApi, 'sys_api_cookie_samesite', '_adm_stg_cpt_option_sys_api_cookie_samesite', 'Lax', 'select', 'None,Lax,Strict', '', '', 44),
(@iCategoryIdApi, 'sys_api_comments_flat', '_adm_stg_cpt_option_sys_api_comments_flat', '', 'checkbox', '', '', '', 100);


-- MENU

UPDATE `sys_menu_items` SET `link` = '' WHERE `link` = 'index.php' AND `name` = 'home' AND `set_name` IN('sys_site', 'sys_application', 'sys_homepage_submenu');

-- GRID: API Origins

INSERT IGNORE INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_api_origins', 'Sql', 'SELECT * FROM `sys_api_origins` WHERE 1 ', 'sys_api_origins', 'id', 'order', '', '', 20, NULL, 'start', '', 'url', '', 'like', '', '', 'BxDolStudioApiOrigins', '');

INSERT IGNORE INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_api_origins', 'order', '', '1%', 0, 0, '', '', 1),
('sys_studio_api_origins', 'url', '_sys_txt_url', '80%', 0, 0, '', '', 2),
('sys_studio_api_origins', 'actions', '', '19%', 0, 0, '', '', 3);

INSERT IGNORE INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_api_origins', 'single', 'delete', '_Delete', 'remove', 1, 1, 1),
('sys_studio_api_origins', 'independent', 'add', '_adm_form_btn_field_add', '', 0, 0, 1);

-- GRID: API Keys

INSERT IGNORE INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('sys_studio_api_keys', 'Sql', 'SELECT * FROM `sys_api_keys` WHERE 1 ', 'sys_api_keys', 'id', 'order', '', '', 20, NULL, 'start', '', 'key,title', '', 'like', '', '', 'BxDolStudioApiKeys', '');

INSERT IGNORE INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `hidden_on`, `order`) VALUES
('sys_studio_api_keys', 'order', '', '1%', 0, 0, '', '', 1),
('sys_studio_api_keys', 'title', '_Name', '40%', 0, 0, '', '', 2),
('sys_studio_api_keys', 'key', '_sys_txt_api_key', '40%', 0, 0, '', '', 3),
('sys_studio_api_keys', 'actions', '', '19%', 0, 0, '', '', 4);

INSERT IGNORE INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('sys_studio_api_keys', 'single', 'delete', '_Delete', 'remove', 1, 1, 1),
('sys_studio_api_keys', 'independent', 'add', '_adm_form_btn_field_add', '', 0, 0, 1);


-- PAGES

INSERT IGNORE INTO `sys_objects_page` (`object`, `uri`, `title_system`, `title`, `module`, `cover`, `layout_id`, `submenu`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `sticky_columns`) VALUES
('sys_logout', 'logout', '', '_sys_page_title_logout', 'system', 0, 18, '', 2147483647, 0, 'page.php?i=logout', '', '', '', 0, 0, 0, '', '', 0);

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_logout';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_logout', 1, 'system', '', '_sys_page_block_title_logout', 11, 0, 0, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:6:\"logout\";s:5:\"class\";s:17:\"TemplServiceLogin\";}', 0, 1, 1, 1);

-- NEW TABLES

CREATE TABLE IF NOT EXISTS `sys_api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `key` varchar(48) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_api_origins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- STUDIO

DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'api';

SET @iIdHome = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'api', '_adm_page_cpt_api', '_adm_page_cpt_api', 'wi-api.svg');
SET @iIdAPI = LAST_INSERT_ID();

INSERT INTO `sys_std_widgets`(`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iIdAPI, 'system', 'configuration', '{url_studio}api.php', '', 'wi-api.svg', '_adm_wgt_cpt_api', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');

INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES(@iIdHome, LAST_INSERT_ID(), 14);



-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-RC4' WHERE (`version` = '13.0.0.RC3' OR `version` = '13.0.0-RC3') AND `name` = 'system';

