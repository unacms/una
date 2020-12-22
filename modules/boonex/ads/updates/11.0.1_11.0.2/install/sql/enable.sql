-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_ads' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_ads_internal_interested_notification';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_ads_internal_interested_notification', '', @iCategId, '_bx_ads_option_internal_interested_notification', 'checkbox', '', '', '', '', 50);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_view_actions' AND `name`='review';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_ads_view_actions', 'bx_ads', 'review', '_bx_ads_menu_item_title_system_review_entry', '', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 190);
