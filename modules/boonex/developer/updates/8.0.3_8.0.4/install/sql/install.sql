SET @sFrom = 'mod_dev_';
SET @sTo = 'bx_developer_';

UPDATE `sys_objects_form` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_displays` SET `display_name`=REPLACE(`display_name`, @sFrom, @sTo), `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `display_name` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_inputs` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_form_display_inputs` SET `display_name`=REPLACE(`display_name`, @sFrom, @sTo) WHERE `display_name` LIKE CONCAT(@sFrom, '%');

UPDATE `sys_objects_grid` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_grid_fields` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');
UPDATE `sys_grid_actions` SET `object`=REPLACE(`object`, @sFrom, @sTo) WHERE `object` LIKE CONCAT(@sFrom, '%');