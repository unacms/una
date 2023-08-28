-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_snipcart_cover';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_snipcart' WHERE `Name` IN ('bx_snipcart', 'bx_snipcart_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_snipcart' WHERE `name`='bx_snipcart';
