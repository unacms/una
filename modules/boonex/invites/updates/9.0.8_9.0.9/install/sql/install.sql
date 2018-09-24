SET @sName = 'bx_invites';


-- FORMS
UPDATE `sys_objects_form` SET `submit_name`='ifi_do_submit' WHERE `object`='bx_invites_invite';
UPDATE `sys_objects_form` SET `submit_name`='ifr_do_submit' WHERE `object`='bx_invites_request';

UPDATE `sys_form_inputs` SET `name`='ifi_do_submit' WHERE `object`='bx_invites_invite' AND `name`='do_submit';
UPDATE `sys_form_inputs` SET `name`='ifr_do_submit' WHERE `object`='bx_invites_request' AND `name`='do_submit';

UPDATE `sys_form_display_inputs` SET `input_name`='ifi_do_submit' WHERE `display_name` IN ('bx_invites_invite_send') AND `input_name`='do_submit';
UPDATE `sys_form_display_inputs` SET `input_name`='ifr_do_submit' WHERE `display_name` IN ('bx_invites_request_send') AND `input_name`='do_submit';
