SET @sName = 'bx_markerio';


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_markerio', 'bx_markerio@modules/boonex/markerio/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_markerio', 10);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_markerio_code', '', @iCategId, '_bx_markerio_option_code', 'text', '', '', '', 10);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'use', NULL, '_bx_markerio_acl_action_use', '', 1, 0);
SET @iIdActionUse = LAST_INSERT_ID();

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

-- entry view
(@iUnauthenticated, @iIdActionUse),
(@iAccount, @iIdActionUse),
(@iStandard, @iIdActionUse),
(@iUnconfirmed, @iIdActionUse),
(@iPending, @iIdActionUse),
(@iModerator, @iIdActionUse),
(@iAdministrator, @iIdActionUse),
(@iPremium, @iIdActionUse);


-- INJECTIONS
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:11:"bx_markerio";s:6:"method";s:12:"include_code";}', 0, 1);
