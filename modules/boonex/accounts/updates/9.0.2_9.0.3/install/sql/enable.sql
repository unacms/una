SET @sName = 'bx_accounts';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name` IN ('edit-email', 'reset-password');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_accounts_menu_manage_tools', @sName, 'edit-email', '_bx_accnt_menu_item_title_system_edit_email', '_bx_accnt_menu_item_title_edit_email', 'javascript:void(0)', 'javascript:{js_object}.onClickEditEmail({content_id}, this);', '_self', 'at', '', 192, 1, 0, 1),
('bx_accounts_menu_manage_tools', @sName, 'reset-password', '_bx_accnt_menu_item_title_system_reset_password', '_bx_accnt_menu_item_title_reset_password', 'javascript:void(0)', 'javascript:{js_object}.onClickResetPassword({content_id}, this);', '_self', 'eraser', '', 192, 1, 0, 3);


-- GRIDS: administration
UPDATE `sys_objects_grid` SET `sorting_fields`='email_confirmed,logged' WHERE `object`='bx_accounts_administration';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_administration' AND `name` IN ('edit_email', 'reset_password');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'single', 'edit_email', '_bx_accnt_grid_action_title_adm_edit_email', '', 0, 0, 0),
('bx_accounts_administration', 'single', 'reset_password', '_bx_accnt_grid_action_title_adm_reset_password', '', 0, 0, 0);