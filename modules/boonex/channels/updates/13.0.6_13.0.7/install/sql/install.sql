SET @sName = 'bx_channels';


-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:1:{s:1:"w";s:4:"1200";}' WHERE `transcoder_object`='bx_channels_cover';


-- VIEWS
UPDATE `sys_objects_view` SET `module`=@sName WHERE `name`='bx_channels';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`=@sName WHERE `Name`='bx_channels';
