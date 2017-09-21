
-- OPTIONS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_googletagman', '_bx_unacon_adm_stg_cpt_type', 'bx_googletagman@modules/boonex/google_tagmanager/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_googletagman_general', '_sys_connect_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_googletagman_container_id', '', @iCategId, '_bx_googletagman_option_container_id', 'digit', '', '', 10, '');


-- INJECTIONS
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('bx_googletagman_track_js', 0, 'injection_head_begin', 'service', 'a:3:{s:6:"module";s:15:"bx_googletagman";s:6:"method";s:9:"injection";s:6:"params";a:1:{i:0;s:20:"injection_head_begin";}}', 0, 1),
('bx_googletagman_track_no_js', 0, 'injection_header', 'service', 'a:3:{s:6:"module";s:15:"bx_googletagman";s:6:"method";s:9:"injection";s:6:"params";a:1:{i:0;s:16:"injection_header";}}', 0, 1);


-- PAGES
SET @iBlockOrderProfileSwitcher = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_account_profile_switcher' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
SET @iBlockOrderDownloadEntry = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'bx_market_download_entry' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_account_profile_switcher', 1, 'bx_googletagman', '', '_bx_googletagman', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:15:"bx_googletagman";s:6:"method";s:22:"tracking_code_register";}', 0, 0, 1, IFNULL(@iBlockOrderProfileSwitcher, 0) + 1),
('bx_market_download_entry', 1, 'bx_googletagman', '', '_bx_googletagman', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:15:"bx_googletagman";s:6:"method";s:37:"tracking_code_download_market_product";}', 0, 0, 1, IFNULL(@iBlockOrderDownloadEntry, 0) + 1);

-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_googletagman', 'BxGoogleTagManAlertsResponse', 'modules/boonex/google_tagmanager/classes/BxGoogleTagManAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_payment', 'finalize_checkout', @iHandler);
