
-- Email template

INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_azrb2c', '_bx_azrb2c_et_password_generated', 'bx_azrb2c_password_generated', '_bx_azrb2c_et_password_generated_subject', '_bx_azrb2c_et_password_generated_body');

-- Auth objects

INSERT INTO `sys_objects_auths` (`Name`, `Title`, `Link`, `Icon`, `Style`) VALUES
('bx_azrb2c', '_bx_azrb2c_auth_title', 'modules/?r=azrb2c/start', 'fab microsoft', 'a:1:{s:7:".bx-btn";a:1:{s:10:"background";s:18:"#0078d4 !important";}}');

-- Alerts

INSERT INTO `sys_alerts_handlers` SET `name` = 'bx_azrb2c', `class` = 'BxAzrB2CAlerts', `file` = 'modules/boonex/azure_b2c_con/classes/BxAzrB2CAlerts.php';

SET @iHandlerId := (SELECT `id` FROM `sys_alerts_handlers`  WHERE `name` = 'bx_azrb2c');

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'logout', @iHandlerId),
('profile', 'delete', @iHandlerId),
('profile', 'add', @iHandlerId);

-- Options

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_azrb2c', '_bx_azrb2c_adm_stg_cpt_type', 'bx_azrb2c@modules/boonex/azure_b2c_con/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_azrb2c_general', '_sys_connect_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_azrb2c_domain', '', @iCategId, '_bx_azrb2c_option_domain', 'digit', '', '', 10, ''),
('bx_azrb2c_tenant', '', @iCategId, '_bx_azrb2c_option_tenant', 'digit', '', '', 12, ''),
('bx_azrb2c_policy', '', @iCategId, '_bx_azrb2c_option_policy', 'digit', '', '', 14, ''),
('bx_azrb2c_tenant_id', '', @iCategId, '_bx_azrb2c_option_tenant_id', 'digit', '', '', 20, ''),
('bx_azrb2c_client_id', '', @iCategId, '_bx_azrb2c_option_client_id', 'digit', '', '', 22, ''),
('bx_azrb2c_secret', '', @iCategId, '_bx_azrb2c_option_secret', 'digit', '', '', 24, ''),
('bx_azrb2c_redirect_page', 'index', @iCategId, '_sys_connect_option_redirect', 'select', '', '', 40, 'join,settings,dashboard,index'),
('bx_azrb2c_module', 'bx_persons', @iCategId, '_sys_connect_option_module', 'select', '', '', 50, 'a:2:{s:6:"module";s:9:"bx_azrb2c";s:6:"method";s:20:"get_profiles_modules";}'),
('bx_azrb2c_privacy', '3', @iCategId, '_sys_connect_option_privacy', 'select', '', '', 54, 'a:2:{s:6:"module";s:12:"bx_azrb2c";s:6:"method";s:18:"get_privacy_groups";}'),
('bx_azrb2c_confirm_email', 'on', @iCategId, '_sys_connect_option_confirm_email', 'checkbox', '', '', 70, ''),
('bx_azrb2c_approve', '', @iCategId, '_sys_connect_option_approve', 'checkbox', '', '', 80, '');

-- Pages

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_azrb2c_error', 'azrb2c-error', '_bx_azrb2c_error', '_bx_azrb2c_error', 'bx_azrb2c', 5, 2147483647, 0, '', '', '', '', 0, 1, 0, 'BxAzrB2CPage', 'modules/boonex/azure_b2c_con/classes/BxAzrB2CPage.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_azrb2c_error', 1, 'bx_azrb2c', '_bx_azrb2c_error', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_azrb2c\";s:6:\"method\";s:10:\"last_error\";}', 0, 0, 1, 1);
