-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_polls_cover';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_polls_cover', 'bx_polls_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_polls_files";}', 'no', '1', '2592000', '0');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_polls_cover';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_polls_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0');


-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_polls' AND `name`='allow_view_to';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Module`='bx_polls' WHERE `Name`='bx_polls';
