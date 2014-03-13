
-- TABLE: entries

DROP TABLE IF EXISTS `bx_notes_posts`;

-- TABLE: storages & transcoders

DROP TABLE IF EXISTS `bx_notes_photos`; 
DROP TABLE IF EXISTS `bx_notes_photos_resized`;

-- TABLE: comments

DROP TABLE IF EXISTS `bx_notes_cmts`;

-- TABLE: votes

DROP TABLE IF EXISTS `bx_notes_votes`;
DROP TABLE IF EXISTS `bx_notes_votes_track`;

-- TABLE: views

DROP TABLE IF EXISTS `bx_notes_views_track`;

-- FORMS

DELETE FROM `sys_objects_form` WHERE `module` = 'bx_notes';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_notes';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_notes';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_notes_entry_add', 'bx_notes_entry_edit', 'bx_notes_entry_view', 'bx_notes_entry_delete');

-- STUDIO: page & widget

DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_notes';

