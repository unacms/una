SET @sName = 'bx_accounts';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name` IN ('resend-remail', 'set-operator-role');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_accounts_menu_manage_tools', @sName, 'resend-remail', '_bx_accnt_menu_item_title_system_resend_remail', '_bx_accnt_menu_item_title_resend_remail', 'javascript:void(0)', 'javascript:{js_object}.onClickResendRemail({content_id}, this);', '_self', 'eraser', '', '', '', 192, '', 1, 0, 5),
('bx_accounts_menu_manage_tools', @sName, 'set-operator-role', '_bx_accnt_menu_item_title_system_set_operator_role', '_bx_accnt_menu_item_title_set_operator_role', 'javascript:void(0)', 'javascript:{js_object}.onClickSetOperatorRole({content_id}, this);', '_self', 'wrench', '', '', '', 192, '', 1, 0, 11);

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name` IN ('make-operator', 'unmake-operator');


-- GRIDS
UPDATE `sys_objects_grid` SET `show_total_count`='1' WHERE `object`='bx_accounts_administration';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_administration' AND `type` IN ('bulk', 'single') AND `name`='resend_remail';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'bulk', 'resend_remail', '_bx_accnt_grid_action_title_adm_resend_remail', '', 0, 0, 4),
('bx_accounts_administration', 'single', 'resend_remail', '_bx_accnt_grid_action_title_adm_resend_remail', '', 0, 0, 0);
