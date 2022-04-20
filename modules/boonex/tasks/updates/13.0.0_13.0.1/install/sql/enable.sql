-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_tasks_view_actions' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_view_actions', 'bx_tasks', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300);


-- CONNECTIONS
UPDATE `sys_objects_connection` SET `profile_initiator`='0', `profile_content`='1' WHERE `object`='bx_tasks_assignments';


-- CATEGORY
UPDATE `sys_objects_category` SET `module`='bx_tasks' WHERE `object`='bx_tasks_cats';
