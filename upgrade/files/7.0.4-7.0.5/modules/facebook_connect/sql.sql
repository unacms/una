
CREATE TABLE IF NOT EXISTS `[db_prefix]accounts` (
  `id_profile` int(10) unsigned NOT NULL,
  `fb_profile` bigint(20) NOT NULL,
  PRIMARY KEY (`id_profile`),
  KEY `fb_profile` (`fb_profile`)
) ENGINE=MyISAM;

DELETE FROM `sys_email_templates` WHERE `Name` = 't_fb_connect_password_generated';
INSERT IGNORE INTO `sys_email_templates` (`Name`, `Subject`, `Body`, `Desc`, `LangID`) VALUES 
('t_fb_connect_password_generated', 'You have been generated a new password', '<html><head></head><body style="font: 12px Verdana; color:#000000">\r\n<p><b>Hello <NickName></b>, You have been generated a new password - <b><NewPassword></b></p>\r\n\r\n<p></p>\r\n\r\n<p>---</p>\r\nBest regards,  <SiteName> \r\n<p style="font: bold 10px Verdana; color:red">!!!Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Facebook connect password generated', 0);

SET @iHandlerId := (SELECT `id` FROM `sys_alerts_handlers`  WHERE `name`  =  'bx_facebook_connect');

INSERT IGNORE INTO
	`sys_alerts`
SET
	`unit`       = 'profile',
	`action`     = 'join',
	`handler_id` = @iHandlerId;

INSERT IGNORE INTO
	`sys_alerts`
SET
	`unit`       = 'profile',
	`action`     = 'delete',
	`handler_id` = @iHandlerId;

UPDATE `sys_options` SET `desc` = 'Facebook API Key' WHERE `Name` = 'bx_facebook_connect_api_key' AND `desc` = 'Facebook api ID';
UPDATE `sys_options` SET `desc`  = 'Facebook App Secret' WHERE `Name` = 'bx_facebook_connect_secret' AND `desc`  = 'Facebook secret';

SET @iKategId = (SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Facebook connect');

INSERT IGNORE INTO 
	`sys_options` 
SET
	`Name` = 'bx_facebook_connect_redirect_page',
	`kateg` = @iKategId,
	`desc`  = 'Redirect page after first sign in',
	`Type`  = 'select',
	`VALUE` = 'join',
	`AvailableValues` = 'join,pedit,avatar,member,index',
	`order_in_kateg` = 3;

INSERT IGNORE INTO 
	`sys_options` 
SET
	`Name` = 'bx_facebook_connect_auto_friends',
	`kateg` = @iKategId,
	`desc`  = 'Auto-friend members if they are already friends on Facebook',
	`Type`  = 'checkbox',
	`VALUE` = 'on',
	`order_in_kateg` = 4;

UPDATE `sys_modules` SET `version` = '1.0.5' WHERE `uri` = 'facebook_connect' AND `version` = '1.0.4';

