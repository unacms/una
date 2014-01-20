
INSERT INTO `sys_accounts` (`name`, `email`, `email_confirmed`, `receive_updates`, `receive_news`, `password`, `salt`, `role`, `added`) VALUES 
('{admin_username}', '{admin_email}', 1, 1, 1, '{admin_pwd_hash}', '{admin_pwd_salt}', 3, '{current_timestamp}');

SET @iAccountId = LAST_INSERT_ID();

INSERT INTO `sys_profiles` (`account_id`, `type`, `content_id`, `status`) VALUES
(@iAccountId, 'system', @iAccountId, 'active');

UPDATE `sys_options` SET `VALUE` = '{admin_email}' WHERE `Name` = 'site_email';
UPDATE `sys_options` SET `VALUE` = '{site_title}' WHERE `Name` = 'site_title';
UPDATE `sys_options` SET `VALUE` = '{site_email}' WHERE `Name` = 'site_email_notify';
UPDATE `sys_options` SET `VALUE` = '{admin_email}' WHERE `Name` = 'site_email_bug_report';
UPDATE `sys_options` SET `VALUE` = '{site_desc}' WHERE `Name` = 'MetaDescription';
UPDATE `sys_options` SET `VALUE` = '{version}' WHERE `Name` = 'sys_version';
UPDATE `sys_options` SET `VALUE` = '{time}' WHERE `Name` = 'sys_install_time';

