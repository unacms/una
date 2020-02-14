SET @sName = 'bx_accounts';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='confirm';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_accounts_menu_manage_tools', @sName, 'confirm', '_bx_accnt_menu_item_title_system_confirm', '_bx_accnt_menu_item_title_confirm', 'javascript:void(0)', 'javascript:{js_object}.onClickConfirm({content_id}, this);', '_self', 'fas check', '', '', 192, '', 1, 0, 3);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_administration' AND `name`='confirm';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'bulk', 'confirm', '_bx_accnt_grid_action_title_adm_confirm', '', 0, 0, 4);
