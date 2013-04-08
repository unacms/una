
DELETE FROM `sys_menu_top` WHERE `Name` = 'Poll unit' AND `Link` = 'm/poll/&action=tag&tag=|modules/?r=poll/&action=tag&tag=';

DELETE FROM `sys_menu_top` WHERE `Name` = 'Poll unit' AND `Link` = 'm/poll/?action=category&category=|modules/?r=poll/&action=category&category=';

DELETE FROM `sys_menu_top` WHERE `Name` = 'Poll unit' AND `Link` = 'm/poll/tag/|modules/?r=poll/tag/';

DELETE FROM `sys_menu_top` WHERE `Name` = 'Poll unit' AND `Link` = 'm/poll/view_calendar/|modules/?r=poll/view_calendar/';

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'poll' AND `version` = '1.0.0';

