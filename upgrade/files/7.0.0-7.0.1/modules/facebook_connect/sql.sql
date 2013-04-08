
DELETE FROM `sys_alerts` WHERE `unit` = 'profile' AND `action` = 'send_mail';

ALTER TABLE `Profiles` CHANGE `FacebookProfile` `FacebookProfile` VARCHAR( 32 ) NOT NULL;

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'facebook_connect' AND `version` = '1.0.0';

