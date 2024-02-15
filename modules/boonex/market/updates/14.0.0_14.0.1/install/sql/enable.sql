-- GRIDS
UPDATE `sys_grid_fields` SET `width`='15%' WHERE `object`='bx_market_licenses_administration' AND `name`='product';
UPDATE `sys_grid_fields` SET `width`='10%' WHERE `object`='bx_market_licenses_administration' AND `name`='domain';
UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_market_licenses_administration' AND `name`='actions';

UPDATE `sys_grid_actions` SET `order`=2 WHERE `object`='bx_market_licenses_administration' AND `name`='reset';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_market_licenses_administration' AND `name` IN ('edit', 'delete');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_market_licenses_administration', 'single', 'edit', '_bx_market_grid_action_title_lcs_edit', 'pencil-alt', 1, 0, 1),
('bx_market_licenses_administration', 'single', 'delete', '_bx_market_grid_action_title_lcs_delete', 'remove', 1, 1, 3);
