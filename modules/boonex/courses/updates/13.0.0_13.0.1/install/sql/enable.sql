-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_courses' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_courses_enable_auto_approve';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_courses_enable_auto_approve', 'on', @iCategId, '_bx_courses_option_enable_auto_approve', 'checkbox', '', '', '', 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_courses_view_actions_more' AND `name`='approve-course-profile';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_courses_view_actions_more', 'bx_courses', 'approve-course-profile', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this, ''{module_uri}'', {content_id});', '', 'check', '', 2147483647, '', 1, 0, 60);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_courses_view_actions_all' AND `name` IN ('social-sharing', 'approve-course-profile', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_courses_view_actions_all', 'bx_courses', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300),
('bx_courses_view_actions_all', 'bx_courses', 'approve-course-profile', '_sys_menu_item_title_system_va_approve', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 440);

UPDATE `sys_menu_items` SET `name`='profile-stats-my-courses', `link`='page.php?i=joined-courses&profile_id={member_id}' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-courses';

UPDATE `sys_menu_items` SET `collapsed`='0' WHERE `set_name`='sys_profile_followings' AND `name`='courses';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_courses' WHERE `object`='bx_courses';


-- CATEGORY
UPDATE `sys_objects_category` SET `module`='bx_courses' WHERE `object`='bx_courses_cats';


-- CONNECTIONS
UPDATE `sys_objects_connection` SET `profile_initiator`='1', `profile_content`='1' WHERE `object`='bx_courses_fans';


-- GRIDS
UPDATE `sys_objects_grid` SET `field_active`='status_admin' WHERE `object`='bx_courses_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_courses_common' AND `name`='switcher';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_courses_common', 'switcher', '_bx_courses_grid_column_title_adm_active', '8%', 0, '', '', 1);

UPDATE `sys_grid_fields` SET `width`='40%' WHERE `object`='bx_courses_common' AND `name`='name';
