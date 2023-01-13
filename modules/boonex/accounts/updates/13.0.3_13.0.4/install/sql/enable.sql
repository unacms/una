SET @sName = 'bx_accounts';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `ta`.*, `tp`.`status` AS `status`, MAX(IFNULL(`ts`.`date`, `ta`.`active`)) AS `last_active` FROM `sys_accounts` AS `ta` LEFT JOIN `sys_profiles` AS `tp` ON `ta`.`id`=`tp`.`account_id` AND `tp`.`type`=''system'' LEFT JOIN `sys_sessions` AS `ts` ON `ts`.`user_id` = `ta`.`id` WHERE 1 ' WHERE `object`='bx_accounts_administration';
