
UPDATE `sys_objects_actions` SET `Script` = 'showPopupAnyHtml (\'{BaseUri}share_popup/{PollId}\');' WHERE `Script` = 'return launchTellFriendProfile({ID});' AND `Type` = 'bx_poll';

UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'poll' AND `version` = '1.0.3';

