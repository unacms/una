-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' AND `name`='dashboard-massmailer';
SET @iMoAccountDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_dashboard', @sName, 'dashboard-massmailer', '_bx_massmailer_menu_item_title_system_admt_mailer', '_bx_massmailer_menu_item_title_admt_mailer', 'page.php?i=massmailer-campaigns', '', '', 'envelope col-red', '', '', 128, 1, 0, 1, @iMoAccountDashboard + 1);


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object`='bx_massmailer_campaigns';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_massmailer_campaigns', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_massmailer_campaigns', 'title', '_bx_massmailer_grid_column_title_adm_title', '26%', 0, '22', '', 2),
('bx_massmailer_campaigns', 'author', '_bx_massmailer_grid_column_title_adm_author', '8%', 0, '22', '', 3),
('bx_massmailer_campaigns', 'segments', '_bx_massmailer_grid_column_title_adm_segment', '10%', 0, '22', '', 4),
('bx_massmailer_campaigns', 'is_one_per_account', '_bx_massmailer_grid_column_title_adm_is_one_per_account', '10%', 0, '0', '', 5),
('bx_massmailer_campaigns', 'added', '_bx_massmailer_grid_column_title_adm_date_created', '10%', 0, '15', '', 6),
('bx_massmailer_campaigns', 'date_sent', '_bx_massmailer_grid_column_title_adm_date_sent', '10%', 0, '22', '', 7),
('bx_massmailer_campaigns', 'actions', '', '24%', 0, '', '', 8);
