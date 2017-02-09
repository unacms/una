SET @sName = 'bx_acl';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_acl_price' AND `name` IN ('trial');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_acl_price', @sName, 'trial', '', '', 0, 'text', '_bx_acl_form_price_input_sys_trial', '_bx_acl_form_price_input_trial', '_bx_acl_form_price_input_inf_trial', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_acl_price_add', 'bx_acl_price_edit');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_acl_price_add', 'id', 2147483647, 0, 1),
('bx_acl_price_add', 'level_id', 2147483647, 1, 2),
('bx_acl_price_add', 'price', 2147483647, 1, 3),
('bx_acl_price_add', 'period', 2147483647, 1, 4),
('bx_acl_price_add', 'period_unit', 2147483647, 1, 5),
('bx_acl_price_add', 'trial', 2147483647, 1, 6),
('bx_acl_price_add', 'controls', 2147483647, 1, 7),
('bx_acl_price_add', 'do_submit', 2147483647, 1, 8),
('bx_acl_price_add', 'do_cancel', 2147483647, 1, 9),

('bx_acl_price_edit', 'id', 2147483647, 1, 1),
('bx_acl_price_edit', 'level_id', 2147483647, 1, 2),
('bx_acl_price_edit', 'price', 2147483647, 1, 3),
('bx_acl_price_edit', 'period', 2147483647, 1, 4),
('bx_acl_price_edit', 'trial', 2147483647, 1, 5),
('bx_acl_price_edit', 'controls', 2147483647, 1, 6),
('bx_acl_price_edit', 'do_submit', 2147483647, 1, 7),
('bx_acl_price_edit', 'do_cancel', 2147483647, 1, 8);


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_acl_administration', 'bx_acl_view');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_acl_administration', 'checkbox', '_sys_select', '1%', 0, '', '', 1),
('bx_acl_administration', 'order', '', '1%', 0, '', '', 2),
('bx_acl_administration', 'name', '_bx_acl_grid_column_name', '33%', 0, 32, '', 3),
('bx_acl_administration', 'price', '_bx_acl_grid_column_price', '15%', 0, 16, '', 4),
('bx_acl_administration', 'period', '_bx_acl_grid_column_period', '15%', 0, 16, '', 5),
('bx_acl_administration', 'trial', '_bx_acl_grid_column_trial', '15%', 0, 16, '', 6),
('bx_acl_administration', 'actions', '', '20%', 0, '', '', 7),

('bx_acl_view', 'level_name', '_bx_acl_grid_column_level_name', '25%', 1, 32, '', 1),
('bx_acl_view', 'price', '_bx_acl_grid_column_price', '15%', 0, 16, '', 2),
('bx_acl_view', 'period', '_bx_acl_grid_column_period', '15%', 0, 16, '', 3),
('bx_acl_view', 'trial', '_bx_acl_grid_column_trial', '15%', 0, 16, '', 4),
('bx_acl_view', 'actions', '', '30%', 0, '', '', 5);

UPDATE `sys_grid_actions` SET `icon_only`='0' WHERE `object`='bx_acl_view' AND `name`='buy';
UPDATE `sys_grid_actions` SET `icon`='credit-card', `icon_only`='0' WHERE `object`='bx_acl_view' AND `name`='subscribe';