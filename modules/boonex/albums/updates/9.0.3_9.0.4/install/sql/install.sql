-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_albums' AND `name` IN ('allow_view_to', 'location');


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Module`='bx_albums' WHERE `Name` IN ('bx_albums', 'bx_albums_media');


-- VOTES
UPDATE `sys_objects_vote` SET `TriggerFieldAuthor`='author' WHERE `Name`='bx_albums_media';
