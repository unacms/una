SET @sName = 'bx_accounts';


-- GRIDS:
UPDATE `sys_grid_fields` SET `width`='16%', `chars_limit`='16' WHERE `object`='bx_accounts_administration' AND `name`='name';
UPDATE `sys_grid_fields` SET `chars_limit`='16' WHERE `object`='bx_accounts_administration' AND `name`='email';
UPDATE `sys_grid_fields` SET `width`='23%', `chars_limit`='0' WHERE `object`='bx_accounts_administration' AND `name`='profiles';
