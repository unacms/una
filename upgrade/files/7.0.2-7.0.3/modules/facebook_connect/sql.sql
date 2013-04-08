
ALTER TABLE `Profiles` ADD INDEX (`FacebookProfile`);

UPDATE `sys_options` SET `desc` = 'Facebook api ID' WHERE `Name` = 'bx_facebook_connect_api_key' AND `desc`  = 'Facebook api key';

UPDATE `sys_options` SET `Name` = 'bx_facebook_connect_secret' WHERE `Name` = 'bx_facebook_connect_secret_key';
UPDATE `sys_options` SET `desc` = 'Facebook secret' WHERE `Name` = 'bx_facebook_connect_secret' AND `desc`  = 'Facebook secret key';

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'facebook_connect' AND `version` = '1.0.2';

