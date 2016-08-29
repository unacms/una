SET @sName = 'bx_market_api';


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_market_api', 'bx_market_api@modules/boonex/market_api/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_market_api', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_market_api_plain_description_chars', '240', @iCategoryId, '_bx_market_api_option_plain_description_chars', 'digit', '', '', '', 10);


-- PAGE: Profile's keys & secrets
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_api_kands_manage', '_bx_market_api_page_title_sys_kands_manage', '_bx_market_api_page_title_kands_manage', @sName, 5, 2147483647, 1, 'kands-manage', '', '', '', '', 0, 1, 0, 'BxMarketApiPageKands', 'modules/boonex/market_api/classes/BxMarketApiPageKands.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_api_kands_manage', 1, @sName, '', '_bx_market_api_page_block_title_kands_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:13:\"bx_market_api\";s:6:\"method\";s:22:\"get_block_kands_manage\";}', 0, 0, 1, 0);


-- MENU: Notifications
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', @sName, 'kands-manage', '_bx_market_api_menu_item_title_system_kands_manage', '_bx_market_api_menu_item_title_kands_manage', 'page.php?i=kands-manage', '', '', 'key col-green2', '', '', 2147483646, 1, 0, @iMIOrder + 1);


-- GRID
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `override_class_name`, `override_class_file`) VALUES
('bx_market_api_kands', 'Array', '', '', 'id', '', '', 10, NULL, 'start', '', 'title,client_id,client_secret,redirect_uri', 'auto', 'title,client_id,client_secret,redirect_uri', 'BxMarketApiGridKands', 'modules/boonex/market_api/classes/BxMarketApiGridKands.php'),
('bx_market_api_kands_clients', 'Array', '', '', 'id', '', '', 10, NULL, 'start', '', 'title,client_id,client_secret,redirect_uri', 'auto', 'title,client_id,client_secret,redirect_uri', 'BxMarketApiGridKandsClients', 'modules/boonex/market_api/classes/BxMarketApiGridKandsClients.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_market_api_kands', 'checkbox', 'Select', '2%', '', 10),
('bx_market_api_kands', 'title', '_Title', '15%', '', 20),
('bx_market_api_kands', 'client_id', '_bx_market_api_client_id', '15%', '', 30),
('bx_market_api_kands', 'client_secret', '_bx_market_api_client_secret', '30%', '', 40),
('bx_market_api_kands', 'redirect_uri', '_bx_market_api_redirect_uri', '30%', '', 50),
('bx_market_api_kands', 'actions', '', '8%', '', 60),

('bx_market_api_kands_clients', 'checkbox', 'Select', '2%', '', 10),
('bx_market_api_kands_clients', 'user_id', '_bx_market_api_user_id', '13%', '', 20),
('bx_market_api_kands_clients', 'title', '_Title', '15%', '', 30),
('bx_market_api_kands_clients', 'client_id', '_bx_market_api_client_id', '20%', '', 40),
('bx_market_api_kands_clients', 'client_secret', '_bx_market_api_client_secret', '25%', '', 50),
('bx_market_api_kands_clients', 'redirect_uri', '_bx_market_api_redirect_uri', '25%', '', 60);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_market_api_kands', 'single', 'pass', '_bx_market_api_pass', 'share-square-o', 1, 0, 1),
('bx_market_api_kands', 'bulk', 'delete', '_Delete', '', 0, 1, 1),
('bx_market_api_kands', 'independent', 'add', '_bx_market_api_add', '', 0, 0, 1),

('bx_market_api_kands_clients', 'bulk', 'delete', '_Delete', '', 0, 1, 1),
('bx_market_api_kands_clients', 'independent', 'add', '_bx_market_api_add', '', 0, 0, 1);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market_api', 'pass', NULL, '_bx_market_api_acl_action_pass', '', 1, 3);
SET @iIdActionPass = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- pass
(@iModerator, @iIdActionPass),
(@iAdministrator, @iIdActionPass);


-- ALERTS
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxMarketApiResponse', 'modules/boonex/market_api/classes/BxMarketApiResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'login', @iHandler),
('system', 'before_register_payment', @iHandler),
('system', 'register_payment', @iHandler);