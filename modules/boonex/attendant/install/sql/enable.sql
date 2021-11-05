SET @sName = 'bx_attendant';

-- SETTINGS
SET @iTypeOrder = (SELECT IFNULL(MAX(`order`), 0) + 1 FROM `sys_options_types` WHERE `group` = 'modules');

INSERT INTO `sys_options_types` (`group`, `name`, `caption`, `icon`, `order`) 
VALUES('modules', @sName, '_bx_attendant_adm_stg_cpt_type', 'bx_attendant@modules/boonex/attendant/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));

SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `hidden`, `order`) VALUES(@iTypeId,  'bx_attendant_on_profile_creation', '_bx_attendant_adm_stg_cpt_category_on_profile_creation', 0, 2);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_attendant_on_profile_creation_modules', '_bx_attendant_adm_stg_on_profile_creation_modules_list', '', 'list', 'a:2:{s:6:"module";s:12:"bx_attendant";s:6:"method";s:19:"get_profile_modules";}', '', '', '', 1),
(@iCategoryId, 'bx_attendant_on_profile_event_list', '_bx_attendant_adm_stg_on_profile_creation_event_list', 'confirmation', 'select', 'a:2:{s:6:"module";s:12:"bx_attendant";s:6:"method";s:40:"get_popup_with_recommended_on_event_show";}', '', '', '', 2),
(@iCategoryId, 'bx_attendant_on_profile_after_action_url', '_bx_attendant_adm_stg_on_profile_after_action_url', '', 'digit', '', '', '', '', 3);

-- INJECTION
INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_footer', 'service', 'a:2:{s:6:"module";s:12:"bx_attendant";s:6:"method";s:18:"handle_action_view";}', '0', '1');

-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxAttendantAlertsResponse', 'modules/boonex/attendant/classes/BxAttendantAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'add', @iHandler);

-- MENU: notifications
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `order` < 9999);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', @sName, 'notifications-attendant', '_bx_attendant_menu_item_title_system_attendant', '_bx_attendant_menu_item_title_attendant', 'javascript:', 'oBxAttendant.showPopupWithRecommended()', '', '', '', '', 2147483646, 1, 0, @iMIOrder + 1);
