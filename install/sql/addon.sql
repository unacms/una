
-- default admin account

INSERT INTO `sys_accounts` (`name`, `email`, `email_confirmed`, `receive_updates`, `receive_news`, `password`, `salt`, `role`, `added`) VALUES 
({admin_username}, {admin_email}, 1, 1, 1, {admin_pwd_hash}, {admin_pwd_salt}, 3, {current_timestamp});

SET @iAccountId = LAST_INSERT_ID();

INSERT INTO `sys_profiles` (`account_id`, `type`, `content_id`, `status`) VALUES
(@iAccountId, 'system', @iAccountId, 'active');

INSERT INTO `sys_std_roles_members` (`account_id`, `role`) VALUES
(@iAccountId, 1);

-- default bot profile

INSERT INTO `sys_accounts` (`name`, `email`, `email_confirmed`, `receive_updates`, `receive_news`, `password`, `salt`, `role`, `added`) VALUES 
('Robot', '', 0, 0, 0, '', '', 3, {current_timestamp});

SET @iAccountIdBot = LAST_INSERT_ID();

INSERT INTO `sys_profiles` (`account_id`, `type`, `content_id`, `status`) VALUES
(@iAccountIdBot, 'system', @iAccountIdBot, 'active');

SET @iProfileIdBot = LAST_INSERT_ID();

-- install time

UPDATE `sys_modules` SET `date` = {time} WHERE `name` = 'system' AND `date` = 0;

-- site settings

UPDATE `sys_options` SET `VALUE` = {admin_email} WHERE `Name` = 'site_email';
UPDATE `sys_options` SET `VALUE` = {site_title} WHERE `Name` = 'site_title';
UPDATE `sys_options` SET `VALUE` = {site_email} WHERE `Name` = 'site_email_notify';
UPDATE `sys_options` SET `VALUE` = {language} WHERE `Name` = 'lang_default';
UPDATE `sys_options` SET `VALUE` = {time} WHERE `Name` = 'sys_install_time';
UPDATE `sys_options` SET `VALUE` = {oauth_key} WHERE `Name` = 'sys_oauth_key';
UPDATE `sys_options` SET `VALUE` = {oauth_secret} WHERE `Name` = 'sys_oauth_secret';
UPDATE `sys_options` SET `VALUE` = @iProfileIdBot WHERE `Name` = 'sys_profile_bot';

UPDATE `sys_modules` SET `version` = {version} WHERE `name` = 'system';
