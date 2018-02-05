SET @sName = 'bx_mapjoined';

-- SETTINGS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_options_types` WHERE `group` = 'modules');

INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) 
VALUES('modules', @sName, '_bx_mapjoined_adm_stg_cpt_type', 'bx_mapjoined@modules/boonex/mapjoined/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));

SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_mapjoined_general', '_bx_mapjoined_adm_stg_cpt_category_general', 0, 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_mapjoined_initial_timeframe_users_shown_in_hours', '_bx_mapjoined_initial_timeframe_users_shown_in_hours', '24', 'digit', '', '', '', '', 1),
(@iCategoryId, 'bx_mapjoined_interval_refresh_new_users_in_seconds', '_bx_mapjoined_interval_refresh_new_users_in_seconds', '30', 'digit', '', '', '', '', 2),
(@iCategoryId, 'bx_mapjoined_default_center_lat_coordinate', '_bx_mapjoined_default_center_lat_coordinate', '44.60240', 'digit', '', '', '', '', 3),
(@iCategoryId, 'bx_mapjoined_default_center_lon_coordinate', '_bx_mapjoined_default_center_lon_coordinate', '32.896372', 'digit', '', '', '', '', 4),
(@iCategoryId, 'bx_mapjoined_default_zoom', '_bx_mapjoined_default_zoom', '2.3', 'digit', '', '', '', '', 5);

-- PAGE: service blocks

SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system` , `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('', 0, @sName, '_bx_mapjoined_page_block_title_sys_map_with_joined_users', '_bx_mapjoined_page_block_title_map_with_joined_users', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:12:"bx_mapjoined";s:6:"method";s:7:"get_map";}', 0, 1, IFNULL(@iBlockOrder, 0) + 1);

-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxMapJoinedAlertsResponse', 'modules/boonex/mapjoined/classes/BxMapJoinedAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'added', @iHandler);

