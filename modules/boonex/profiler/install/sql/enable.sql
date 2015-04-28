-- settings
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_profiler', 'Profiler', 'bx_profiler@modules/boonex/profiler/|std-mi.png', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();


INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_profiler', 'Debug Panel', 1);
SET @iCategoryId = LAST_INSERT_ID();
INSERT INTO `sys_options` (`category_id`, `name`, `value`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_profiler_show_debug_panel', 'none', 'Show debug panel below the page for', 'select', 'none,admins,all', '', '', 1);


INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_profiler_sql_querues', 'SQL Queries', 2);
SET @iCategoryId = LAST_INSERT_ID();
INSERT INTO `sys_options` (`category_id`, `name`, `value`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_profiler_long_sql_queries_log', 'on', 'Log long sql queries', 'checkbox', '', '', '', 1),
(@iCategoryId, 'bx_profiler_long_sql_queries_time', '2', 'Time in seconds of long sql query', 'digit', '', '', '', 2),
(@iCategoryId, 'bx_profiler_long_sql_queries_debug', '', 'Log additionad debug info with each long sql query', 'checkbox', '', '', '', 3);


INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_profiler_modules_queries', 'Modules Queries', 3);
SET @iCategoryId = LAST_INSERT_ID();
INSERT INTO `sys_options` (`category_id`, `name`, `value`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_profiler_long_module_query_log', 'on', 'Log long modules queries', 'checkbox', '', '', '', 1),
(@iCategoryId, 'bx_profiler_long_module_query_time', '3', 'Time in seconds of long module query', 'digit', '', '', '', 2),
(@iCategoryId, 'bx_profiler_long_module_query_debug', '', 'Log additionad debug info with each long module query', 'checkbox', '', '', '', 3);


INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_profiler_page_opens', 'Pages opens', 4);
SET @iCategoryId = LAST_INSERT_ID();
INSERT INTO `sys_options` (`category_id`, `name`, `value`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_profiler_long_page_log', 'on', 'Log long page opens', 'checkbox', '', '', '', 1),
(@iCategoryId, 'bx_profiler_long_page_time', '5', 'Time in seconds of long page open', 'digit', '', '', '', 2),
(@iCategoryId, 'bx_profiler_long_page_debug', '', 'Log additionad debug info with each long page open', 'checkbox', '', '', '', 3);

-- ALERTS

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_profiler', 'BxProfilerAlertsResponse', 'modules/boonex/profiler/classes/BxProfilerAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'begin', @iHandler);

