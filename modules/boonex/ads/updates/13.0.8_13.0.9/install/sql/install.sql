-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_ads_cover';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:4:"1200";s:1:"h";s:4:"1200";}' WHERE `transcoder_object`='bx_ads_view_photos';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_ads' WHERE `Name` IN ('bx_ads', 'bx_ads_reactions', 'bx_ads_poll_answers');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_ads' WHERE `name`='bx_ads';
