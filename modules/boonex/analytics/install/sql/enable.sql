SET @sName = 'bx_analytics';

-- SETTINGS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_options_types` WHERE `group` = 'modules');

INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) 
VALUES('modules', @sName, '_bx_analytics_adm_stg_cpt_type', 'bx_analytics@modules/boonex/analytics/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));

SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_analytics_general', '_bx_analytics_adm_stg_cpt_category_general', 0, 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_analytics_modules_disabled', '_bx_analytics_adm_stg_cpt_modules_enabled', '', 'rlist', 'a:2:{s:6:"module";s:12:"bx_analytics";s:6:"method";s:11:"get_modules";}', '', '', '', 1),
(@iCategoryId, 'bx_analytics_items_count', '_bx_analytics_adm_stg_cpt_items_count', '20', 'digit', '', '', '', '', 2),
(@iCategoryId, 'bx_analytics_default_interval_day', '_bx_analytics_adm_stg_cpt_default_interval_day', '90', 'digit', '', '', '', '', 3);

-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_analytics_page', '_bx_analytics_page_title_sys_page', '_bx_analytics_page_title_page', @sName, 5, 2147483647, 1, 'analytics', '', '', '', '', 0, 1, 0, 'BxAnalyticsPage', 'modules/boonex/analytics/classes/BxAnalyticsPage.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_analytics_page', 1,  @sName, '_bx_analytics_page_block_title_system_canvas', '_bx_analytics_page_block_title_canvas', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:12:"bx_analytics";s:6:"method";s:10:"get_canvas";}', 0, 1, 1, 0);

-- MENU: account dashboard
SET @iMoAccountDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_dashboard', @sName, 'dashboard-analytics', '_bx_analytics_item_title_system_analytics', '_bx_analytics_item_title_analytics', 'page.php?i=analytics', '', '', 'certificate col-red', '', '', 2147483647, 'a:2:{s:6:"module";s:12:"bx_analytics";s:6:"method";s:12:"is_avaliable";}', 1, 0, 1, @iMoAccountDashboard + 1);

-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'use analytics', NULL, '_bx_analytics_acl_action_use_analytics', '', 1, 3);
SET @iIdActionUseAnalytics = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionUseAnalytics),
(@iAdministrator, @iIdActionUseAnalytics);