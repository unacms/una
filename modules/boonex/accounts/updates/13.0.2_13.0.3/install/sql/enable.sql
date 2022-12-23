SET @sName = 'bx_accounts';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `ta`.*, `tp`.`status` AS `status`, IFNULL(`ts`.`date`, `ta`.`active`) AS `last_active` FROM `sys_accounts` AS `ta` LEFT JOIN `sys_profiles` AS `tp` ON `ta`.`id`=`tp`.`account_id` AND `tp`.`type`=''system'' LEFT JOIN `sys_sessions` AS `ts` ON `ts`.`user_id` = `ta`.`id` WHERE 1 ', `sorting_fields`='email_confirmed,logged,added,last_active' WHERE `object`='bx_accounts_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_accounts_administration' AND `name`='last_active';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_accounts_administration', 'last_active', '_bx_accnt_grid_column_title_adm_last_active', '10%', 0, '15', '', 8);

UPDATE `sys_grid_fields` SET `width`='12%' WHERE `object`='bx_accounts_administration' AND `name`='name';
UPDATE `sys_grid_fields` SET `width`='16%' WHERE `object`='bx_accounts_administration' AND `name`='email';
UPDATE `sys_grid_fields` SET `width`='4%' WHERE `object`='bx_accounts_administration' AND `name`='is_confirmed';
UPDATE `sys_grid_fields` SET `width`='24%' WHERE `object`='bx_accounts_administration' AND `name`='profiles';
UPDATE `sys_grid_fields` SET `order`='9' WHERE `object`='bx_accounts_administration' AND `name`='added';
UPDATE `sys_grid_fields` SET `order`='10' WHERE `object`='bx_accounts_administration' AND `name`='actions';
