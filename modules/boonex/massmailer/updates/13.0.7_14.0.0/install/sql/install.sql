SET @sName = 'bx_massmailer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`=@sName AND `name`='body_info';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `unique`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'body_info', '', '', 0, 'custom', '_bx_massmailer_form_campaign_input_sys_body_info', '_bx_massmailer_form_campaign_input_body_info', '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_massmailer_campaign_add', 'bx_massmailer_campaign_edit') AND `input_name`='body_info';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_massmailer_campaign_add', 'body_info', 2147483647, 1, 6),
('bx_massmailer_campaign_edit', 'body_info', 2147483647, 1, 6);
