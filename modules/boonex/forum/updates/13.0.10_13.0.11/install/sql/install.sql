SET @sName = 'bx_forum';


-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_forum_cover';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:4:"1200";s:1:"h";s:4:"1200";}' WHERE `transcoder_object`='bx_forum_view_photos';


-- VIEWS
UPDATE `sys_objects_view` SET `module`=@sName WHERE `name`=@sName;


-- VOTES
UPDATE `sys_objects_vote` SET `Module`=@sName WHERE `Name` IN (@sName, 'bx_forum_reactions', 'bx_forum_poll_answers');
