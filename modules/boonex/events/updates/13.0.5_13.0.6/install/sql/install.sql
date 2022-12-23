-- FORMS
UPDATE `sys_form_inputs` SET `db_pass`='DateTimeUtc' WHERE `object`='bx_event' AND `name`='date_end';
UPDATE `sys_form_inputs` SET `db_pass`='DateTimeUtc' WHERE `object`='bx_event' AND `name`='date_start';
UPDATE `sys_form_inputs` SET `db_pass`='DateTimeTs' WHERE `object`='bx_event' AND `name`='published';
