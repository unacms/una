
UPDATE `sys_menu_top` SET `Link` = 'modules/?r=sounds/tags' WHERE `Name` = 'SoundsTags' AND `Link` = 'modules/?r=sounds/categories';

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'sounds' AND `version` = '1.0.2';

