-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_market_cover';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_market' WHERE `Name` IN ('bx_market', 'bx_market_reactions');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_market' WHERE `name`='bx_market';
