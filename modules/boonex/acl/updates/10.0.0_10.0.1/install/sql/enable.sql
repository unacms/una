SET @sName = 'bx_acl';


-- SETTINGS
DELETE FROM `sys_options_types` WHERE `name`=@sName;
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_acl', 'bx_acl@modules/boonex/acl/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

DELETE FROM `sys_options_categories` WHERE `name`=@sName;
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_acl', 1);
SET @iCategId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name`='bx_acl_recurring_reserve';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_acl_recurring_reserve', '2', @iCategId, '_bx_acl_option_recurring_reserve', 'digit', '', '', '', '', 1);
