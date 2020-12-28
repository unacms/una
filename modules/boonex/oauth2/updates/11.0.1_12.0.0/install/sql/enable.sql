-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_oauth_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_oauth2_refresh_token_lifetime', 'bx_oauth2_always_issue_new_refresh_token');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `order`, `extra`) VALUES
('bx_oauth2_refresh_token_lifetime', '7776000', @iCategId, '_bx_oauth_adm_stg_opt_refresh_token_lifetime', 'digit', '', '',  '', '10', ''),
('bx_oauth2_always_issue_new_refresh_token', 'on', @iCategId, '_bx_oauth_adm_stg_opt_always_issue_new_refresh_token', 'checkbox', '', '',  '', '20', '');
