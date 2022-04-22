-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_groups' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_groups_enable_auto_approve', 'bx_groups_enable_subscribe_wo_join');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_groups_enable_auto_approve', 'on', @iCategId, '_bx_groups_option_enable_auto_approve', 'checkbox', '', '', '', 0),
('bx_groups_enable_subscribe_wo_join', '', @iCategId, '_bx_groups_option_enable_subscribe_wo_join', 'checkbox', '', '', '', 45);


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_more' AND `name`='approve-group-profile';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_more', 'bx_groups', 'approve-group-profile', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this, ''{module_uri}'', {content_id});', '', 'check', '', 2147483647, '', 1, 0, 60);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_all' AND `name` IN ('social-sharing', 'approve-group-profile', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_all', 'bx_groups', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300),
('bx_groups_view_actions_all', 'bx_groups', 'approve-group-profile', '_sys_menu_item_title_system_va_approve', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 440);

UPDATE `sys_menu_items` SET `name`='profile-stats-my-groups', `link`='page.php?i=joined-groups&profile_id={member_id}' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-groups';

UPDATE `sys_menu_items` SET `collapsed`='0' WHERE `set_name`='sys_profile_followings' AND `name`='groups';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_groups' WHERE `object`='bx_groups';


-- CATEGORY
UPDATE `sys_objects_category` SET `module`='bx_groups' WHERE `object`='bx_groups_cats';


-- CONNECTIONS
UPDATE `sys_objects_connection` SET `profile_initiator`='1', `profile_content`='1' WHERE `object`='bx_groups_fans';


-- GRIDS
UPDATE `sys_objects_grid` SET `field_active`='status_admin' WHERE `object`='bx_groups_administration';

UPDATE `sys_grid_fields` SET `width`='40%' WHERE `object`='bx_groups_common' AND `name`='name';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_groups_common' AND `name`='switcher';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_groups_common', 'switcher', '_bx_groups_grid_column_title_adm_active', '8%', 0, '', '', 2);
