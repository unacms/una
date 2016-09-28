-- GRIDS
UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_oauth' AND `name`='title';
UPDATE `sys_grid_fields` SET `width`='15%' WHERE `object`='bx_oauth' AND `name`='client_id';
UPDATE `sys_grid_fields` SET `width`='30%' WHERE `object`='bx_oauth' AND `name`='client_secret';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_oauth' AND `name`='redirect_uri';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_oauth', 'redirect_uri', '_bx_oauth_url', '33%', '', 50);