
-- Email template

INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_linkedin', '_bx_linkedin_et_password_generated', 'bx_linkedin_password_generated', '_bx_linkedin_et_password_generated_subject', '_bx_linkedin_et_password_generated_body');

-- Auth objects

INSERT INTO `sys_objects_auths` (`Name`, `Title`, `Link`, `Icon`) VALUES
('bx_linkedin', '_bx_linkedin_auth_title', 'modules/?r=linkedin/start', 'linkedin');

-- Alerts

INSERT INTO `sys_alerts_handlers` SET `name` = 'bx_linkedin', `class` = 'BxLinkedinAlerts', `file` = 'modules/boonex/dolphin_connect/classes/BxLinkedinAlerts.php';

SET @iHandlerId := (SELECT `id` FROM `sys_alerts_handlers`  WHERE `name` = 'bx_linkedin');

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'logout', @iHandlerId),
('profile', 'delete', @iHandlerId),
('profile', 'add', @iHandlerId);

-- Options

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_linkedin', '_bx_linkedin_adm_stg_cpt_type', 'bx_linkedin@modules/boonex/linkedin/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_linkedin_general', '_sys_connect_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_linkedin_api_key', '', @iCategId, '_bx_linkedin_option_app_id', 'digit', '', '', 10, ''),
('bx_linkedin_secret', '', @iCategId, '_bx_linkedin_option_app_secret', 'digit', '', '', 20, ''),
('bx_linkedin_redirect_page', 'dashboard', @iCategId, '_sys_connect_option_redirect', 'select', '', '', 40, 'join,settings,dashboard,index'),
('bx_linkedin_module', 'bx_persons', @iCategId, '_sys_connect_option_module', 'select', '', '', 50, 'a:2:{s:6:"module";s:11:"bx_linkedin";s:6:"method";s:20:"get_profiles_modules";}'),
('bx_linkedin_confirm_email', 'on', @iCategId, '_sys_connect_option_confirm_email', 'checkbox', '', '', 70, ''),
('bx_linkedin_approve', '', @iCategId, '_sys_connect_option_approve', 'checkbox', '', '', 80, '');

-- Pages

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_linkedin_error', 'linkedin-error', '_bx_linkedin_error', '_bx_linkedin_error', 'bx_linkedin', 5, 2147483647, 0, '', '', '', '', 0, 1, 0, 'BxLinkedinPage', 'modules/boonex/linkedin/classes/BxLinkedinPage.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_linkedin_error', 1, 'bx_linkedin', '_bx_linkedin_error', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_linkedin\";s:6:\"method\";s:10:\"last_error\";}', 0, 0, 1, 1);
