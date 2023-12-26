-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_ads' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_ads_enable_sources', 'bx_ads_enable_promotion', 'bx_ads_promotion_cpm');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_ads_enable_sources', '', @iCategId, '_bx_ads_option_enable_sources', 'checkbox', '', '', '', 1),

('bx_ads_enable_promotion', '', @iCategId, '_bx_ads_option_enable_promotion', 'checkbox', '', '', '', 50),
('bx_ads_promotion_cpm', '1', @iCategId, '_bx_ads_option_promotion_cpm', 'digit', '', '', '', 51);


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_edit_entry_budget';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_edit_entry_budget', '_bx_ads_page_title_sys_edit_entry_budget', '_bx_ads_page_title_edit_entry_budget', 'bx_ads', 5, 2147483647, 1, 'edit-ad-budget', '', '', '', '', 0, 1, 0, 'BxAdsPageEntry', 'modules/boonex/ads/classes/BxAdsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_edit_entry_budget';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_ads_edit_entry_budget', 1, 'bx_ads', '_bx_ads_page_block_title_edit_entry_budget', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:18:"entity_edit_budget";}', 0, 0, 0);


DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_view_entry_promotion';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_view_entry_promotion', '_bx_ads_page_title_sys_view_entry_promotion', '_bx_ads_page_title_view_entry_promotion', 'bx_ads', 12, 2147483647, 1, 'view-ad-promotion', '', '', '', '', 0, 1, 0, 'BxAdsPageEntry', 'modules/boonex/ads/classes/BxAdsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_view_entry_promotion';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_ads_view_entry_promotion', 2, 'bx_ads', '_bx_ads_page_block_title_sys_entry_promotion_growth', '_bx_ads_page_block_title_entry_promotion_growth', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:23:"entity_promotion_growth";}', 0, 0, 1),
('bx_ads_view_entry_promotion', 3, 'bx_ads', '_bx_ads_page_block_title_sys_entry_promotion_summary', '_bx_ads_page_block_title_entry_promotion_summary', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:24:"entity_promotion_summary";}', 0, 0, 1),
('bx_ads_view_entry_promotion', 3, 'bx_ads', '_bx_ads_page_block_title_sys_entry_promotion_roi', '_bx_ads_page_block_title_entry_promotion_roi', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:20:"entity_promotion_roi";}', 0, 0, 2);


DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_sources';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_sources', '_bx_ads_page_title_sys_sources', '_bx_ads_page_title_sources', 'bx_ads', 5, 2147483646, 1, 'ads-sources', 'page.php?i=ads-sources', '', '', '', 0, 1, 0, 'BxAdsPageBrowse', 'modules/boonex/ads/classes/BxAdsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_sources';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_ads_sources', 1, 'bx_ads', '_bx_ads_page_block_title_system_sources_details', '_bx_ads_page_block_title_sources_details', 11, 2147483646, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:21:"block_sources_details";}}', 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_view' AND `name` IN ('edit-ad-budget', 'view-ad-promotion');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_ads_view', 'bx_ads', 'edit-ad-budget', '_bx_ads_menu_item_title_system_edit_entry_budget', '_bx_ads_menu_item_title_edit_entry_budget', 'page.php?i=edit-ad-budget&id={content_id}', '', '', 'pencil-ruler', '', 2147483647, 1, 0, 21),
('bx_ads_view', 'bx_ads', 'view-ad-promotion', '_bx_ads_menu_item_title_system_view_promotion', '_bx_ads_menu_item_title_view_promotion', 'page.php?i=view-ad-promotion&id={content_id}', '', '', 'chart-pie', '', 2147483647, 1, 0, 22);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_view_actions' AND `name` IN ('edit-ad-budget', 'view-ad-promotion');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_ads_view_actions', 'bx_ads', 'edit-ad-budget', '_bx_ads_menu_item_title_system_edit_entry_budget', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 21),
('bx_ads_view_actions', 'bx_ads', 'view-ad-promotion', '_bx_ads_menu_item_title_system_view_promotion', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 22);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_submenu' AND `name`='ads-sources';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_ads_submenu', 'bx_ads', 'ads-sources', '_bx_ads_menu_item_title_system_sources', '_bx_ads_menu_item_title_sources', 'page.php?i=ads-sources', '', '', '', '', 2147483646, 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:20:"is_sources_avaliable";}', 1, 1, 6);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_view_submenu' AND `name`='view-ad-promotion';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_ads_view_submenu', 'bx_ads', 'view-ad-promotion', '_bx_ads_menu_item_title_system_view_entry_promotion', '_bx_ads_menu_item_title_view_entry_submenu_promotion', 'page.php?i=view-ad-promotion&id={content_id}', '', '', '', '', 2147483647, 0, 0, 3);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_ads_promotion_growth', 'bx_ads_promotion_growth_speed');
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_ads_promotion_growth', '_bx_ads_chart_promotion_growth', 'bx_ads_promo_tracker', 'date', '', '', 'SELECT {field_date_formatted} AS `period`, SUM(`impressions`) AS {object} FROM {table} WHERE 1 AND `entry_id`=''{content_id}'' {where_inteval} GROUP BY `period` ORDER BY {field_date} ASC', 0, 0, 'BxAdsChartGrowth', 'modules/boonex/ads/classes/BxAdsChartGrowth.php'),
('bx_ads_promotion_growth_speed', '_bx_ads_chart_promotion_growth_speed', 'bx_ads_promo_tracker', 'date', '', '', 'SELECT {field_date_formatted} AS `period`, SUM(`impressions`) AS {object} FROM {table} WHERE 1 AND `entry_id`=''{content_id}'' {where_inteval} GROUP BY `period` ORDER BY {field_date} ASC', 0, 0, 'BxAdsChartGrowthSpeed', 'modules/boonex/ads/classes/BxAdsChartGrowthSpeed.php');


-- GRIDS
UPDATE `sys_grid_fields` SET `width`='10%' WHERE `object`='bx_ads_common' AND `name` IN ('added', 'status_admin');
UPDATE `sys_grid_fields` SET `width`='30%' WHERE `object`='bx_ads_common' AND `name`='actions';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_ads_common' AND `type`='single';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_ads_common', 'single', 'promotion', '_bx_ads_grid_action_title_adm_promotion', 'chart-pie', 1, 0, 1),
('bx_ads_common', 'single', 'edit_budget', '_bx_ads_grid_action_title_adm_edit_budget', 'pencil-ruler', 1, 0, 2),
('bx_ads_common', 'single', 'edit', '_bx_ads_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 3),
('bx_ads_common', 'single', 'delete', '_bx_ads_grid_action_title_adm_delete', 'remove', 1, 1, 4),
('bx_ads_common', 'single', 'settings', '_bx_ads_grid_action_title_adm_more_actions', 'cog', 1, 0, 5);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_ads' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_timeline' AND `action` IN ('get_view', 'get_external_post') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_timeline', 'get_view', @iHandler),
('bx_timeline', 'get_external_post', @iHandler);
