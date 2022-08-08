SET @sName = 'bx_attendant';

-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_attendant_on_profile_creation' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_attendant_redirect_to_entered_page', 'bx_attendant_suggest_entered_page');
INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_attendant_redirect_to_entered_page', '_bx_attendant_adm_stg_enable_redirect_to_entered_page', '', 'checkbox', '', '', '', '', 5),
(@iCategoryId, 'bx_attendant_suggest_entered_page', '_bx_attendant_adm_stg_enable_suggest_entered_page', '', 'checkbox', '', '', '', '', 6);

-- MENUS
UPDATE `sys_menu_items` SET `onclick`='oBxAttendant.showPopup(1)' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-attendant';
