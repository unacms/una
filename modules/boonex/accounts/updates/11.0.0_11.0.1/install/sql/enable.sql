SET @sName = 'bx_accounts';


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_administration' AND `name`='add';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'independent', 'add', '_bx_accnt_grid_action_title_adm_more_add', 'plus', 0, 0, 0);
