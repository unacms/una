
DELETE FROM `sys_options` WHERE `Name` = '[db_prefix]_activation';

UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'videos' AND `version` = '1.0.3';

