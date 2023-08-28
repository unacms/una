-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_classes_cover';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_classes_gallery_photos';

-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_classes' WHERE `Name` IN ('bx_classes', 'bx_classes_reactions', 'bx_classes_poll_answers');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_classes' WHERE `name`='bx_classes';
