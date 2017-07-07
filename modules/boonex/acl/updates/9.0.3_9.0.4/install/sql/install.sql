SET @sName = 'bx_acl';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_acl_price' AND `name` IN ('period', 'period_unit', 'price');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_acl_price', @sName, 'period', '', '', 0, 'text', '_bx_acl_form_price_input_sys_period', '_bx_acl_form_price_input_period', '_bx_acl_form_price_input_inf_period', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_acl_price', @sName, 'period_unit', '', '#!bx_acl_period_units', 0, 'select', '_bx_acl_form_price_input_sys_period_unit', '_bx_acl_form_price_input_period_unit', '_bx_acl_form_price_input_inf_period_unit', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_acl_price', @sName, 'price', '', '', 0, 'text', '_bx_acl_form_price_input_sys_price', '_bx_acl_form_price_input_price', '_bx_acl_form_price_input_inf_price', 1, 0, 0, '', '', '', '', '', '', 'Float', '', 1, 0);


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `tlp`.*, `tl`.`Name` AS `level_name`, `tl`.`Icon` AS `level_icon` FROM `bx_acl_level_prices` AS `tlp` LEFT JOIN `sys_acl_levels` AS `tl` ON `tlp`.`level_id`=`tl`.`ID` WHERE `tl`.`Active`=''yes'' AND `tl`.`Purchasable`=''yes'' ' WHERE `object`='bx_acl_view';

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_acl_view');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_acl_view', 'level_icon', '_bx_acl_grid_column_level_icon', '5%', 0, 0, '', 1),
('bx_acl_view', 'level_name', '_bx_acl_grid_column_level_name', '25%', 1, 32, '', 2),
('bx_acl_view', 'price', '_bx_acl_grid_column_price', '10%', 0, 16, '', 3),
('bx_acl_view', 'period', '_bx_acl_grid_column_period', '15%', 0, 16, '', 4),
('bx_acl_view', 'trial', '_bx_acl_grid_column_trial', '15%', 0, 16, '', 5),
('bx_acl_view', 'actions', '', '30%', 0, '', '', 6);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_acl_view' AND `name`='choose';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_acl_view', 'single', 'choose', '_bx_acl_grid_action_choose', 'check-square-o', 0, 0, 3);