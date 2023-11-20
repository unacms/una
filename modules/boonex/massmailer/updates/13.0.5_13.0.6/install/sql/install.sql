SET @sName = 'bx_massmailer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`=@sName AND `name` IN ('controls', 'controls2');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `unique`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'controls', '', 'do_send,cancel', 0, 'input_set', '', '', '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_massmailer_campaign_add', 'bx_massmailer_campaign_edit') AND `input_name` IN ('controls', 'cancel');

DELETE FROM `sys_form_display_inputs` WHERE `input_name` IN ('controls', 'controls2') AND `display_name` IN ('bx_massmailer_campaign_send_test', 'bx_massmailer_campaign_send_all');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_massmailer_campaign_send_test', 'controls', 2147483647, 1, 2),
('bx_massmailer_campaign_send_all', 'controls', 2147483647, 1, 2);
