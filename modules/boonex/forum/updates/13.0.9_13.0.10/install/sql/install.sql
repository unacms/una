SET @sName = 'bx_forum';


-- FORMS
UPDATE `sys_form_display_inputs` SET `order`='1' WHERE `display_name`='bx_forum_entry_view' AND `input_name`='cat';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_forum_entry_view' AND `input_name` IN ('title', 'text', 'added', 'changed');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_entry_view', 'added', 2147483647, 1, 2),
('bx_forum_entry_view', 'changed', 2147483647, 1, 3);
