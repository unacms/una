
-- Email template

INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_twitter', '_bx_twitter_et_password_generated', 'bx_twitter_password_generated', '_bx_twitter_et_password_generated_subject', '_bx_twitter_et_password_generated_body');

-- Auth objects

INSERT INTO `sys_objects_auths` (`Name`, `Title`, `Link`, `Icon`) VALUES
('bx_twitter', '_bx_twitter_auth_title', 'modules/?r=twitter/start', 'twitter');

-- Alerts

INSERT INTO `sys_alerts_handlers` SET `name` = 'bx_twitter', `class` = 'BxTwitterAlerts', `file` = 'modules/boonex/dolphin_connect/classes/BxTwitterAlerts.php';

SET @iHandlerId := (SELECT `id` FROM `sys_alerts_handlers`  WHERE `name` = 'bx_twitter');

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'logout', @iHandlerId),
('profile', 'delete', @iHandlerId),
('profile', 'add', @iHandlerId);

-- Options

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_twitter', '_bx_twitter_adm_stg_cpt_type', 'bx_twitter@modules/boonex/twitter/|std-mi.png', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_twitter_general', '_sys_connect_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_twitter_api_key', '', @iCategId, '_bx_twitter_option_app_id', 'digit', '', '', 10, ''),
('bx_twitter_secret', '', @iCategId, '_bx_twitter_option_app_secret', 'digit', '', '', 20, ''),
('bx_twitter_redirect_page', 'dashboard', @iCategId, '_sys_connect_option_redirect', 'select', '', '', 40, 'join,settings,dashboard,index'),
('bx_twitter_module', 'bx_persons', @iCategId, '_sys_connect_option_module', 'select', '', '', 50, 'a:2:{s:6:"module";s:10:"bx_twitter";s:6:"method";s:20:"get_profiles_modules";}'),
('bx_twitter_confirm_email', 'on', @iCategId, '_sys_connect_option_confirm_email', 'checkbox', '', '', 70, ''),
('bx_twitter_approve', '', @iCategId, '_sys_connect_option_approve', 'checkbox', '', '', 80, '');

-- Pages

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_twitter_error', 'twitter-error', '_bx_twitter_error', '_bx_twitter_error', 'bx_twitter', 5, 2147483647, 0, '', '', '', '', 0, 1, 0, 'BxTwitterPage', 'modules/boonex/twitter/classes/BxTwitterPage.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_twitter_error', 1, 'bx_twitter', '_bx_twitter_error', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_twitter\";s:6:\"method\";s:10:\"last_error\";}', 0, 0, 1, 1);
