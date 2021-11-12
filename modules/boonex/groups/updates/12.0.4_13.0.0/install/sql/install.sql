-- TABLES
ALTER TABLE `bx_groups_data` MODIFY `join_confirmation` tinyint(4) NOT NULL DEFAULT '0';

-- FORMS
UPDATE `sys_form_inputs` SET `checked`='0' WHERE `object`='bx_group' AND `name`='join_confirmation';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldComments`='' WHERE `Name`='bx_groups_notes';