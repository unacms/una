SET @sName = 'bx_accounts';


-- GRIDS
UPDATE `sys_grid_actions` SET `title`='_bx_accnt_grid_action_title_adm_export_all' WHERE `object`='bx_accounts_administration' AND `type`='independent' AND `name`='export';
