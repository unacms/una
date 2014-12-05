SET @sFrom = 'mod_cnt_';
SET @sTo = 'bx_contact_';
UPDATE `sys_objects_form` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_displays` SET `display_name`=REPLACE(`display_name`, @sFrom, @sTo), `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `display_name` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_inputs` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_display_inputs` SET `display_name`=REPLACE(`display_name`, @sFrom, @sTo) WHERE `display_name` LIKE CONCAT(@sFrom, '%');