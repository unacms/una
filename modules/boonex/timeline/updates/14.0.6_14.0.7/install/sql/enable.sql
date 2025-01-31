SET @sName = 'bx_timeline';


-- SETTINGS
UPDATE `sys_options` SET `value`='on' WHERE `name`='bx_timeline_enable_show_all';

UPDATE `sys_options` SET `value`='public,feed,channels,hot,recom_friends,recom_subscriptions' WHERE `name`='bx_timeline_for_you_sources' AND `value`='feed,channels,hot,recom_friends,recom_subscriptions';
