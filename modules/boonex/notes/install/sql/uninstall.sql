
-- TABLE: NOTES

DROP TABLE IF EXISTS `bx_notes_posts`;

-- TABLE: STORAGES & TRANSCODERS

-- TODO: delete photo files as well
DROP TABLE IF EXISTS `bx_notes_photos`; 
DROP TABLE IF EXISTS `bx_notes_photos_resized`;

-- TABLE: COMMENTS
DROP TABLE IF EXISTS `bx_notes_cmts`;

-- TABLE: VOTES
DROP TABLE IF EXISTS `bx_notes_votes`;
DROP TABLE IF EXISTS `bx_notes_votes_track`;

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_notes';

DELETE FROM `sys_form_displays` WHERE `module` = 'bx_notes';

DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_notes';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_notes_note_add';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_notes_note_edit';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_notes_note_view';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_notes_note_delete';

-- STUDIO PAGE & WIDGET

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_notes';

