SET @sName = 'bx_accounts';


-- GRIDS
UPDATE `sys_objects_grid` SET `sorting_fields`='email_confirmed,logged,added' WHERE `object`='bx_accounts_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_accounts_administration' AND `name`='added';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_accounts_administration', 'added', '_bx_accnt_grid_column_title_adm_added', '10%', 0, '15', '', 8);

UPDATE `sys_grid_fields` SET `width`='17%', `order`='6' WHERE `object`='bx_accounts_administration' AND `name`='profiles';
UPDATE `sys_grid_fields` SET `width`='10%' WHERE `object`='bx_accounts_administration' AND `name`='logged';
UPDATE `sys_grid_fields` SET `order`='9' WHERE `object`='bx_accounts_administration' AND `name`='actions';
