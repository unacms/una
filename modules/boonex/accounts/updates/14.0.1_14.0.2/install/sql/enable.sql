SET @sName = 'bx_accounts';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `ta`.*, `tp`.`status` AS `status`, `ta`.`active`, `ts`.`date` AS `last_active` FROM `sys_accounts` AS `ta` LEFT JOIN `sys_sessions` AS `ts` ON `ta`.`id`=`ts`.`user_id` LEFT JOIN `sys_profiles` AS `tp` ON `ta`.`id`=`tp`.`account_id` AND `tp`.`type`=''system'' WHERE 1 ', `sorting_fields`='email_confirmed,logged,last_active,added' WHERE `object`='bx_accounts_administration';
