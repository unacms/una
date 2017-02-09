-- TABLE
ALTER TABLE `bx_events_pics` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_events_pics_resized` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_event_invite';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_event', 'bx_event_invite', 'bx_events', 0, '_bx_events_form_profile_display_invite');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_event' AND `name` IN ('reminder');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_event', 'bx_events', 'reminder', '', '#!bx_events_reminder', 0, 'select', '_bx_events_form_profile_input_sys_reminder', '_bx_events_form_profile_input_reminder', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 1);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_event_add', 'bx_event_invite', 'bx_event_edit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_event_add', 'time', 2147483647, 0, 1),
('bx_event_add', 'delete_confirm', 2147483647, 0, 2),
('bx_event_add', 'cover', 2147483647, 0, 3),
('bx_event_add', 'initial_members', 2147483647, 1, 4),
('bx_event_add', 'picture', 2147483647, 1, 5),
('bx_event_add', 'event_name', 2147483647, 1, 6),
('bx_event_add', 'event_cat', 2147483647, 1, 7),
('bx_event_add', 'event_desc', 2147483647, 1, 8),
('bx_event_add', 'location', 2147483647, 1, 9),
('bx_event_add', 'date_start', 2147483647, 1, 10),
('bx_event_add', 'date_end', 2147483647, 1, 11),
('bx_event_add', 'timezone', 2147483647, 1, 12),
('bx_event_add', 'reoccurring', 2147483647, 1, 13),
('bx_event_add', 'join_confirmation', 2147483647, 1, 14),
('bx_event_add', 'reminder', 2147483647, 1, 15),
('bx_event_add', 'allow_view_to', 2147483647, 1, 16),
('bx_event_add', 'do_submit', 2147483647, 1, 17),

('bx_event_invite', 'initial_members', 2147483647, 1, 1),
('bx_event_invite', 'do_submit', 2147483647, 1, 2),

('bx_event_edit', 'time', 2147483647, 0, 1),
('bx_event_edit', 'initial_members', 2147483647, 0, 2),
('bx_event_edit', 'delete_confirm', 2147483647, 0, 3),
('bx_event_edit', 'cover', 2147483647, 0, 4),
('bx_event_edit', 'picture', 2147483647, 1, 5),
('bx_event_edit', 'event_name', 2147483647, 1, 6),
('bx_event_edit', 'event_cat', 2147483647, 1, 7),
('bx_event_edit', 'event_desc', 2147483647, 1, 8),
('bx_event_edit', 'location', 2147483647, 1, 9),
('bx_event_edit', 'date_start', 2147483647, 1, 10),
('bx_event_edit', 'date_end', 2147483647, 1, 11),
('bx_event_edit', 'timezone', 2147483647, 1, 12),
('bx_event_edit', 'reoccurring', 2147483647, 1, 13),
('bx_event_edit', 'join_confirmation', 2147483647, 1, 14),
('bx_event_edit', 'reminder', 2147483647, 1, 15),
('bx_event_edit', 'allow_view_to', 2147483647, 1, 16),
('bx_event_edit', 'do_submit', 2147483647, 1, 17);



-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `key`='bx_events_reminder';
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_reminder', '_bx_events_pre_lists_reminder', 'bx_events', '0');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_events_reminder';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_reminder', '0', 0, '_bx_events_reminder_none', ''),
('bx_events_reminder', '1', 1, '_bx_events_reminder_1h', ''),
('bx_events_reminder', '2', 2, '_bx_events_reminder_2h', ''),
('bx_events_reminder', '3', 3, '_bx_events_reminder_3h', ''),
('bx_events_reminder', '6', 4, '_bx_events_reminder_6h', ''),
('bx_events_reminder', '12', 5, '_bx_events_reminder_12h', ''),
('bx_events_reminder', '24', 6, '_bx_events_reminder_24h', '');

DELETE FROM `sys_form_pre_values` WHERE `Key`='bx_events_cats';
INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_cats', '', 0, '_sys_please_select', ''),
('bx_events_cats', '1', 1, '_bx_events_cat_Conference', ''),
('bx_events_cats', '2', 2, '_bx_events_cat_Festival', ''),
('bx_events_cats', '3', 3, '_bx_events_cat_Fundraiser', ''),
('bx_events_cats', '4', 4, '_bx_events_cat_Lecture', ''),
('bx_events_cats', '5', 5, '_bx_events_cat_Market', ''),
('bx_events_cats', '6', 6, '_bx_events_cat_Meal', ''),
('bx_events_cats', '7', 7, '_bx_events_cat_Social_Mixer', ''),
('bx_events_cats', '8', 8, '_bx_events_cat_Tour', ''),
('bx_events_cats', '9', 9, '_bx_events_cat_Volunteering', ''),
('bx_events_cats', '10', 10, '_bx_events_cat_Workshop', ''),
('bx_events_cats', '11', 11, '_bx_events_cat_Other', '');

UPDATE `bx_events_data` SET `event_cat`='11';