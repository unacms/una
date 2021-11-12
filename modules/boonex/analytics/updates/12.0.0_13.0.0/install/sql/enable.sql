SET @sName = 'bx_analytics';


-- PAGES
UPDATE `sys_pages_blocks` SET `visible_for_levels`='2147483647' WHERE `object`='bx_analytics_page' AND `title`='_bx_analytics_page_block_title_canvas';


-- MENUS:
UPDATE `sys_menu_items` SET `visible_for_levels`='2147483647', `visibility_custom`='a:2:{s:6:"module";s:12:"bx_analytics";s:6:"method";s:12:"is_avaliable";}' WHERE `set_name`='sys_account_dashboard' AND `name`='dashboard-analytics';


-- ACL
SET @iIdActionUseAnalytics = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='use analytics' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionUseAnalytics;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionUseAnalytics;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'use analytics', NULL, '_bx_analytics_acl_action_use_analytics', '', 1, 3);
SET @iIdActionUseAnalytics = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionUseAnalytics),
(@iAdministrator, @iIdActionUseAnalytics);