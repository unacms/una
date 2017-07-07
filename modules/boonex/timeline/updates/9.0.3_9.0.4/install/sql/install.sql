SET @sName = 'bx_timeline';


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_timeline_post_add_public';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_timeline_post_add_public', @sName, 'bx_timeline_post', '_bx_timeline_form_post_display_add_public', 0);

UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_timeline_post' AND `name` IN ('text', 'location', 'link', 'photo', 'video', 'attachments');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_post' AND `name`='object_privacy_view';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_post', @sName, 'object_privacy_view', '', '', 0, 'custom', '_bx_timeline_form_post_input_sys_object_privacy_view', '_bx_timeline_form_post_input_object_privacy_view', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_timeline_post_add', 'bx_timeline_post_add_public');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_add', 'type', 2147483647, 1, 1),
('bx_timeline_post_add', 'action', 2147483647, 1, 2),
('bx_timeline_post_add', 'owner_id', 2147483647, 1, 3),
('bx_timeline_post_add', 'text', 2147483647, 1, 4),
('bx_timeline_post_add', 'object_privacy_view', 2147483647, 1, 5),
('bx_timeline_post_add', 'location', 2147483647, 1, 6),
('bx_timeline_post_add', 'link', 2147483647, 1, 7),
('bx_timeline_post_add', 'photo', 2147483647, 1, 8),
('bx_timeline_post_add', 'video', 2147483647, 1, 9),
('bx_timeline_post_add', 'attachments', 2147483647, 1, 10),
('bx_timeline_post_add', 'do_submit', 2147483647, 1, 11),

('bx_timeline_post_add_public', 'type', 2147483647, 1, 1),
('bx_timeline_post_add_public', 'action', 2147483647, 1, 2),
('bx_timeline_post_add_public', 'owner_id', 2147483647, 1, 3),
('bx_timeline_post_add_public', 'text', 2147483647, 1, 4),
('bx_timeline_post_add_public', 'object_privacy_view', 2147483647, 1, 5),
('bx_timeline_post_add_public', 'location', 2147483647, 1, 6),
('bx_timeline_post_add_public', 'link', 2147483647, 1, 7),
('bx_timeline_post_add_public', 'photo', 2147483647, 1, 8),
('bx_timeline_post_add_public', 'video', 2147483647, 1, 9),
('bx_timeline_post_add_public', 'attachments', 2147483647, 1, 10),
('bx_timeline_post_add_public', 'do_submit', 2147483647, 1, 11);
