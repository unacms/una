-- GRID
UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_oauth' AND `name`='client_secret';
UPDATE `sys_grid_fields` SET `width`='25%' WHERE `object`='bx_oauth' AND `name`='redirect_uri';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_oauth' AND `name` IN ('actions');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_oauth', 'actions', '', '18%', '', 60);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_oauth' AND `name` IN ('edit');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_oauth', 'single', 'edit', '_Edit', 'pencil', 1, 0, 1);