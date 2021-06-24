
-- Settings

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_nexus', '_bx_nexus_adm_stg_cpt_type', 'bx_nexus@modules/boonex/nexus/|std-icon.svg', IF(NOT ISNULL(@iTypeOrder), @iTypeOrder + 1, 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_nexus_general', '_bx_nexus_adm_stg_cpt_category_general', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_nexus_option_styles', '', @iCategId, '_bx_nexus_option_styles', 'text', '', '', '', 10),
('bx_nexus_option_guest_pages', 'forgot-password,create-account,terms,privacy,contact,about,home', @iCategId, '_bx_nexus_option_guest_pages', 'text', '', '', '', 20),
('bx_nexus_option_custom_homepage', 'on', @iCategId, '_bx_nexus_option_custom_homepage', 'checkbox', '', '', '', 30),
('bx_nexus_option_main_menu', 'default', @iCategId, '_bx_nexus_option_main_menu', 'select', 'a:3:{s:6:"module";s:8:"bx_nexus";s:6:"method";s:14:"get_menus_list";s:6:"params";a:0:{}}', '', '', 40);

-- Injections

INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('bx_nexus', 0, 'injection_head_begin', 'service', 'a:2:{s:6:"module";s:8:"bx_nexus";s:6:"method";s:20:"injection_head_begin";}', 0, 1),
('bx_nexus', 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:8:"bx_nexus";s:6:"method";s:14:"injection_head";}', 0, 1);

-- Alerts

INSERT INTO `sys_alerts_handlers` SET `name` = 'bx_nexus', `class` = 'BxNexusAlerts', `file` = 'modules/boonex/nexus/classes/BxNexusAlerts.php';

SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'page_output', @iHandlerId);

