-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_glossary_cover';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_glossary' WHERE `Name` IN ('bx_glossary', 'bx_glossary_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_glossary' WHERE `name`='bx_glossary';
