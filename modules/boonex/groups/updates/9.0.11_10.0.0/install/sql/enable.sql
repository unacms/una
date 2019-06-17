-- MENUS
UPDATE `sys_objects_menu` SET `override_class_name`='BxGroupsMenuViewActions', `override_class_file`='modules/boonex/groups/classes/BxGroupsMenuViewActions.php' WHERE `object`='bx_groups_view_actions';
UPDATE `sys_objects_menu` SET `override_class_name`='BxGroupsMenuViewActions', `override_class_file`='modules/boonex/groups/classes/BxGroupsMenuViewActions.php' WHERE `object`='bx_groups_view_actions_more';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_all' AND `name`='social-sharing-googleplus';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_submenu' AND `name`='more-auto';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_submenu', 'bx_groups', 'more-auto', '_bx_groups_menu_item_title_system_view_profile_more_auto', '_bx_groups_menu_item_title_view_profile_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, '', 1, 0, 9999);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_snippet_meta' AND `name` IN ('privacy', 'nl');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_groups_snippet_meta', 'bx_groups', 'privacy', '_bx_groups_menu_item_title_system_sm_privacy', '_bx_groups_menu_item_title_sm_privacy', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 1),
('bx_groups_snippet_meta', 'bx_groups', 'nl', '_sys_menu_item_title_system_sm_nl', '_sys_menu_item_title_sm_nl', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 8);

UPDATE `sys_menu_items` SET `order`='2' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='date';
UPDATE `sys_menu_items` SET `order`='3' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='tags';
UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='views';
UPDATE `sys_menu_items` SET `order`='5' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='comments';
UPDATE `sys_menu_items` SET `order`='6' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='members';
UPDATE `sys_menu_items` SET `order`='7' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='subscribers';
UPDATE `sys_menu_items` SET `order`='9' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='join';
UPDATE `sys_menu_items` SET `order`='10' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='leave';
UPDATE `sys_menu_items` SET `order`='11' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='subscribe';
UPDATE `sys_menu_items` SET `order`='12' WHERE `set_name`='bx_groups_snippet_meta' AND `name`='unsubscribe';

UPDATE `sys_menu_items` SET `icon`='users' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='groups-administration' AND `icon`='';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_groups' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_groups' AND `action` IN ('timeline_score', 'timeline_pin', 'timeline_promote') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_groups', 'timeline_score', @iHandler),
('bx_groups', 'timeline_pin', @iHandler),
('bx_groups', 'timeline_promote', @iHandler);

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_groups_allow_post_to';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_allow_post_to', 'bx_groups', 'post', '_bx_groups_form_profile_input_allow_post_to', 'p', '', 'bx_groups_data', 'id', 'author', 'BxGroupsPrivacyPost', 'modules/boonex/groups/classes/BxGroupsPrivacyPost.php');
