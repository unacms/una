SET @sName = 'bx_charts';

-- SETTINGS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_options_types` WHERE `group` = 'modules');

INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) 
VALUES('modules', @sName, '_bx_charts_adm_stg_cpt_type', 'bx_charts@modules/boonex/charts/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));

SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_charts_chart1', '_bx_charts_adm_stg_cpt_category_chart_top_contents_by_likes', 0, 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_charts_chart_top_contents_by_likes_modules_disabled', '_bx_charts_adm_stg_cpt_chart_top_contents_by_likes_modules_enabled', '', 'rlist', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:16:"get_text_modules";}', '', '', '', 1),
(@iCategoryId, 'bx_charts_chart_top_contents_by_likes_count', '_bx_charts_adm_stg_cpt_chart_top_contents_by_likes_count', '4', 'digit', '', '', '', '', 2),
(@iCategoryId, 'bx_charts_chart_top_contents_by_likes_interval_day', '_bx_charts_adm_stg_cpt_chart_top_contents_by_likes_interval_day', '7', 'digit', '', '', '', '', 3);

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_charts_chart_most_active_profiles', '_bx_charts_adm_stg_cpt_category_chart_most_active_profiles', 0, 2);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_charts_chart_most_active_profiles_modules_disabled', '_bx_charts_adm_stg_cpt_chart_most_active_profiles_modules_enabled', '', 'rlist', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:19:"get_profile_modules";}', '', '', '', 1),
(@iCategoryId, 'bx_charts_chart_most_active_profiles_posts_for_module_disabled', '_bx_charts_adm_stg_cpt_chart_most_active_profiles_posts_for_module', '', 'rlist', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:16:"get_text_modules";}', '', '', '', 2),
(@iCategoryId, 'bx_charts_chart_most_active_profiles_count', '_bx_charts_adm_stg_cpt_chart_most_active_profiles_count', '4', 'digit', '', '', '', '', 3),
(@iCategoryId, 'bx_charts_chart_most_active_profiles_interval_day', '_bx_charts_adm_stg_cpt_chart_most_active_profiles_interval_day', '7', 'digit', '', '', '', '', 4);

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_charts_chart_most_followed_profiles', '_bx_charts_adm_stg_cpt_category_chart_most_followed_profiles', 0, 3);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_charts_chart_most_followed_profiles_modules_disabled', '_bx_charts_adm_stg_cpt_chart_most_followed_profiles_modules_enabled', '', 'rlist', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:19:"get_profile_modules";}', '', '', '', 1),
(@iCategoryId, 'bx_charts_chart_most_followed_profiles_count', '_bx_charts_adm_stg_cpt_chart_most_followed_profiles_count', '4', 'digit', '', '', '', '', 2),
(@iCategoryId, 'bx_charts_chart_most_followed_profiles_interval_day', '_bx_charts_adm_stg_cpt_chart_most_followed_profiles_interval_day', '7', 'digit', '', '', '', '', 3);

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_charts_chart_chart_growth', '_bx_charts_adm_stg_cpt_category_chart_growth', 0, 4);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_charts_chart_growth_modules', '_bx_charts_adm_stg_cpt_chart_growth_modules_enabled', '', 'rlist', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:11:"get_modules";}', '', '', '', 1),
(@iCategoryId, 'bx_charts_chart_growth_interval_day', '_bx_charts_adm_stg_cpt_chart_growth_interval_day', '30', 'digit', '', '', '', '', 2),
(@iCategoryId, 'bx_charts_chart_growth_group_by', '_bx_charts_adm_stg_cpt_chart_growth_group_by', 'date', 'select', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:19:"get_growth_group_by";}', '', '', '', 3);

-- PAGES: add page block on dashboard
SET @iPBCellDashboard = 2;
SET @iPBOrderDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `cell_id` = @iPBCellDashboard LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard,  @sName, '_bx_charts_page_block_title_system_chart_top_contents_by_likes', '_bx_charts_page_block_title_chart_top_contents_by_likes', 11, 192, 'service', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:31:"get_chart_top_contents_by_likes";}', 0, 1, 1, @iPBOrderDashboard),
('sys_dashboard', @iPBCellDashboard,  @sName, '_bx_charts_page_block_title_system_chart_most_active_profiles', '_bx_charts_page_block_title_chart_most_active_profiles', 11, 192, 'service', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:30:"get_chart_most_active_profiles";}', 0, 1, 1, @iPBOrderDashboard + 1),
('sys_dashboard', @iPBCellDashboard,  @sName, '_bx_charts_page_block_title_system_chart_most_followed_profiles', '_bx_charts_page_block_title_chart_most_followed_profiles', 11, 192, 'service', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:32:"get_chart_most_followed_profiles";}', 0, 1, 1, @iPBOrderDashboard + 2),
('sys_dashboard', @iPBCellDashboard,  @sName, '_bx_charts_page_block_title_system_chart_growth_by_modules', '_bx_charts_page_block_title_chart_growth_by_modules', 11, 192, 'service', 'a:2:{s:6:"module";s:9:"bx_charts";s:6:"method";s:27:"get_chart_growth_by_modules";}', 0, 1, 1, @iPBOrderDashboard + 3);

-- CRON
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_charts_cron', '0 1 * * *', 'BxChartsCron', 'modules/boonex/charts/classes/BxChartsCron.php', '');

-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxChartsAlertsResponse', 'modules/boonex/charts/classes/BxChartsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);

