
-- TABLE: entries

DROP TABLE IF EXISTS `bx_messages_msg`, `bx_messages_conversations`, `bx_messages_folders`, `bx_messages_conv2folder`;

-- TABLE: storages & transcoders

DROP TABLE IF EXISTS `bx_messages_photos`, `bx_messages_photos_resized`;

-- TABLE: comments

DROP TABLE IF EXISTS `bx_messages_cmts`;

-- TABLE: views

DROP TABLE IF EXISTS `bx_messages_views_track`;

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_messages';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_messages';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_messages';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_messages_entry_add', 'bx_messages_entry_view', 'bx_messages_entry_delete');

-- STUDIO: page & widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_messages';

