-- GRIDS:
UPDATE `sys_objects_grid` SET `filter_fields`='name,email,ip,phone' WHERE `object`='bx_accounts_administration';

UPDATE `sys_grid_fields` SET `name`='is_confirmed', `title`='_bx_accnt_grid_column_title_adm_is_confirmed' WHERE `object`='bx_accounts_administration' AND `name`='email_confirmed';
