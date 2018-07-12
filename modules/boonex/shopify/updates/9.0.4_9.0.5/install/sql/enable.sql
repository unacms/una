-- GRIDS
UPDATE `sys_objects_grid` SET `sorting_fields`='reports' WHERE `object`='bx_shopify_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_shopify_administration' AND `name`='reports';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_shopify_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 3);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_shopify_administration' AND `name`='author';
