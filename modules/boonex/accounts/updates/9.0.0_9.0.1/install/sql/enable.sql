SET @sName = 'bx_accounts';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name` IN ('make-operator', 'unmake-operator');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_accounts_menu_manage_tools', @sName, 'make-operator', '_bx_accnt_menu_item_title_system_make_operator', '_bx_accnt_menu_item_title_make_operator', 'javascript:void(0)', 'javascript:{js_object}.onClickMakeOperator({content_id});', '_self', 'wrench', '', 192, 1, 0, 4),
('bx_accounts_menu_manage_tools', @sName, 'unmake-operator', '_bx_accnt_menu_item_title_system_unmake_operator', '_bx_accnt_menu_item_title_unmake_operator', 'javascript:void(0)', 'javascript:{js_object}.onClickUnmakeOperator({content_id});', '_self', 'wrench', '', 192, 1, 0, 5);


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='192' WHERE `object`='bx_accounts_administration';