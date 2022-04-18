-- MENUS
UPDATE `sys_menu_items` SET `link`='page.php?i=credits-history-common' WHERE `set_name`='sys_account_dashboard' AND `name`='credits-manage';


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_credits_history_common' AND `name`='send';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_credits_history_common', 'independent', 'send', '_bx_credits_grid_action_title_htr_send', '', 0, 0, 1);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_credits_received';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_credits', '_bx_credits_et_txt_name_received', 'bx_credits_received', '_bx_credits_et_txt_subject_received', '_bx_credits_et_txt_body_received');
