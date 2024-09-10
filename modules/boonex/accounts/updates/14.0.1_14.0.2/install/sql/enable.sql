SET @sName = 'bx_accounts';


-- OPTIONS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_accounts_last_active_sorting';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_accounts_last_active_sorting', '', @iCategId, '_bx_accounts_option_last_active_sorting', 'checkbox', '', '', '', 1);


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `ta`.*, `tp`.`status` AS `status`, `ta`.`active` {select} FROM `sys_accounts` AS `ta` LEFT JOIN `sys_profiles` AS `tp` ON `ta`.`id`=`tp`.`account_id` AND `tp`.`type`=''system'' {join} WHERE 1 ' WHERE `object`='bx_accounts_administration';
