-- TABLES
ALTER TABLE `bx_albums_albums` MODIFY `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active';


-- FORMS
UPDATE `sys_objects_form` SET `override_class_name`='BxAlbumsFormMedia', `override_class_file`='modules/boonex/albums/classes/BxAlbumsFormMedia.php' WHERE `object`='bx_albums_media';

DELETE FROM `sys_form_displays` WHERE `display_name`='bx_albums_media_move';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_albums_media', 'bx_albums_media_move', 'bx_albums', 0, '_bx_albums_form_media_display_move');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_albums_media' AND `name`='content_id';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `help`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_albums_media', 'bx_albums', 'content_id', '', '', 0, 'select', '_bx_albums_form_media_input_sys_content_id', '_bx_albums_form_media_input_content_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_albums_media_move';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_albums_media_move', 'content_id', 2147483647, 1, 1),
('bx_albums_media_move', 'controls', 2147483647, 1, 2),
('bx_albums_media_move', 'do_submit', 2147483647, 1, 3),
('bx_albums_media_move', 'do_cancel', 2147483647, 1, 4);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldComments`='' WHERE `Name`='bx_albums_notes';