SET @sName = 'bx_accounts';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='far envelope' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='resend-cemail';
UPDATE `sys_menu_items` SET `icon`='far trash-alt' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='delete';
UPDATE `sys_menu_items` SET `icon`='far trash-alt' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='delete-with-content';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name`='unlock-account';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_accounts_menu_manage_tools', @sName, 'unlock-account', '_bx_accnt_menu_item_title_system_unlock_account', '_bx_accnt_menu_item_title_unlock_account', 'javascript:void(0)', 'javascript:{js_object}.onClickUnlockAccount({content_id}, this);', '_self', 'unlock', '', 192, 1, 0, 4);


-- GRIDS:
DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_administration' AND `name`='unlock_account';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'single', 'unlock_account', '_bx_accnt_grid_action_title_adm_unlock_account', '', 0, 0, 0);
