-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_posts_cover';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:4:"1200";s:1:"h";s:4:"1200";}' WHERE `transcoder_object`='bx_posts_view_photos';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_posts' WHERE `Name` IN ('bx_posts', 'bx_posts_reactions', 'bx_posts_poll_answers');


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_posts' WHERE `name`='bx_posts';
