-- TABLES
ALTER TABLE `bx_tasks_tasks` MODIFY `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active';


-- FORMS
UPDATE `sys_form_inputs` SET `name`='allow_comments' AND `checked`='1', `caption_system`='_bx_tasks_form_entry_input_sys_allow_comments', `caption`='_bx_tasks_form_entry_input_allow_comments' WHERE `object`='bx_tasks' AND `name`='disable_comments';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldComments`='' WHERE `Name`='bx_tasks_notes';