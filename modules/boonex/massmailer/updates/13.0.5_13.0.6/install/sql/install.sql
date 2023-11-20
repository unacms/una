SET @sName = 'bx_massmailer';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`=@sName AND `name`='controls';
UPDATE `sys_form_inputs` SET `name`='controls' WHERE `object`=@sName AND `name`='controls2';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_massmailer_campaign_add', 'bx_massmailer_campaign_edit') AND `input_name` IN ('controls', 'cancel');

DELETE FROM `sys_form_display_inputs` WHERE `input_name`='controls' AND `display_name` IN ('bx_massmailer_campaign_send_test', 'bx_massmailer_campaign_send_all');
UPDATE `sys_form_display_inputs` SET `input_name`='controls' WHERE `display_name` IN ('bx_massmailer_campaign_send_test', 'bx_massmailer_campaign_send_all') AND `input_name`='controls2';
