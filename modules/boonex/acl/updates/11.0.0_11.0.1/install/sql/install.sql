SET @sName = 'bx_acl';


-- FORMS
UPDATE `sys_objects_form` SET `override_class_name`='BxAclFormPrice', `override_class_file`='modules/boonex/acl/classes/BxAclFormPrice.php' WHERE `object`='bx_acl_price';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_acl_price' AND `name`='name';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_acl_price', @sName, 'name', '', '', 0, 'text', '_bx_acl_form_price_input_sys_name', '_bx_acl_form_price_input_name', '_bx_acl_form_price_input_inf_name', 1, 0, 0, '', '', '', 'Avail', '', '_bx_acl_form_price_input_err_name', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_acl_price_add', 'bx_acl_price_edit') AND `input_name`='name';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_acl_price_add', 'name', 2147483647, 1, 3),
('bx_acl_price_edit', 'name', 2147483647, 1, 3);
