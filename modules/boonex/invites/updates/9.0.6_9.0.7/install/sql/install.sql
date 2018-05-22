-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `bx_inv_requests`.* FROM `bx_inv_requests` WHERE 1', `field_order`='status, date', `filter_fields`='bx_inv_requests`.`name, bx_inv_requests`.`email' WHERE `object`='bx_invites_requests';

UPDATE `sys_grid_fields` SET `width`='14%' WHERE `object`='bx_invites_requests' AND `name` IN ('name', 'email');
UPDATE `sys_grid_fields` SET `width`='10%' WHERE `object`='bx_invites_requests' AND `name` IN ('nip', 'date');
UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_invites_requests' AND `name`='actions';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_invites_requests' AND `name` IN ('joined_account', 'status');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_invites_requests', 'joined_account', '_bx_invites_grid_column_title_joined_account', '20%', 0, '20', '', 6),
('bx_invites_requests', 'status', '_bx_invites_grid_column_title_status', '10%', 0, '15', '', 7);

UPDATE `sys_grid_actions` SET `icon`='info-circle' WHERE `object`='bx_invites_requests' AND `name`='info';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_invites_requests' AND `name` IN ('invite_info', 'add');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_invites_requests', 'single', 'invite_info', '_bx_invites_grid_action_title_adm_invite_info', 'info-circle', 1, 0, 4),
('bx_invites_requests', 'independent', 'add', '_bx_invites_grid_action_title_adm_add', '', 0, 0, 5);
