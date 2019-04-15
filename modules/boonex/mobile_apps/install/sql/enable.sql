
-- Settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_mobileapps', '_bx_mobileapps_adm_stg_cpt_type', 'bx_mobileapps@modules/boonex/mobile_apps/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_mobileapps_general', '_bx_mobileapps_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_mobileapps_option_qwerty', '123', @iCategId, '_bx_mobileapps_option_qwerty', 'digit', '', '', '', 10);

-- Injections

INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('bx_mobileapps', 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:13:"bx_mobileapps";s:6:"method";s:9:"injection";}', 0, 1);

-- Alerts

INSERT INTO `sys_alerts_handlers` SET `name` = 'bx_mobileapps', `class` = 'BxMobileAppsAlerts', `file` = 'modules/boonex/mobile_apps/classes/BxMobileAppsAlerts.php';

SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'page_output', @iHandlerId);

