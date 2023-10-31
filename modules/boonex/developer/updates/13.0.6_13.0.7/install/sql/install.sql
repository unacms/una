SET @sName = 'bx_developer';


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_developer_forms' AND `type`='independent' AND `name` IN ('import_full', 'export_full');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_developer_forms', 'independent', 'import_full', '_bx_dev_btn_import_full', '', 0, 2),
('bx_developer_forms', 'independent', 'export_full', '_bx_dev_btn_export_full', '', 0, 3);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_developer_nav_menus' AND `type`='independent' AND `name` IN ('import_full', 'export_full');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_developer_nav_menus', 'independent', 'import_full', '_bx_dev_btn_import_full', '', 0, 2),
('bx_developer_nav_menus', 'independent', 'export_full', '_bx_dev_btn_export_full', '', 0, 3);
