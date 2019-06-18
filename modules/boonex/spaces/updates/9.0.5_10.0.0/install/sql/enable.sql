-- MENUS
UPDATE `sys_objects_menu` SET `override_class_name`='BxSpacesMenuViewActions', `override_class_file`='modules/boonex/spaces/classes/BxSpacesMenuViewActions.php' WHERE `object`='bx_spaces_view_actions';
UPDATE `sys_objects_menu` SET `override_class_name`='BxSpacesMenuViewActions', `override_class_file`='modules/boonex/spaces/classes/BxSpacesMenuViewActions.php' WHERE `object`='bx_spaces_view_actions_more';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_spaces_view_actions_all' AND `name`='social-sharing-googleplus';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_spaces_view_submenu' AND `name`='more-auto';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_spaces_view_submenu', 'bx_spaces', 'more-auto', '_bx_spaces_menu_item_title_system_view_profile_more_auto', '_bx_spaces_menu_item_title_view_profile_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, '', 1, 0, 9999);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='nl';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_spaces_snippet_meta', 'bx_spaces', 'nl', '_sys_menu_item_title_system_sm_nl', '_sys_menu_item_title_sm_nl', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 7);

UPDATE `sys_menu_items` SET `order`='1' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='date';
UPDATE `sys_menu_items` SET `order`='2' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='tags';
UPDATE `sys_menu_items` SET `order`='3' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='views';
UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='comments';
UPDATE `sys_menu_items` SET `order`='5' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='members';
UPDATE `sys_menu_items` SET `order`='6' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='subscribers';
UPDATE `sys_menu_items` SET `order`='8' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='join';
UPDATE `sys_menu_items` SET `order`='9' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='leave';
UPDATE `sys_menu_items` SET `order`='10' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='subscribe';
UPDATE `sys_menu_items` SET `order`='11' WHERE `set_name`='bx_spaces_snippet_meta' AND `name`='unsubscribe';

UPDATE `sys_menu_items` SET `icon`='object-group' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='spaces-administration' AND `icon`='';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_spaces' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_spaces' AND `action` IN ('timeline_score', 'timeline_pin', 'timeline_promote') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_spaces', 'timeline_score', @iHandler),
('bx_spaces', 'timeline_pin', @iHandler),
('bx_spaces', 'timeline_promote', @iHandler);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_spaces_allow_post_to';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_spaces_allow_post_to', 'bx_spaces', 'post', '_bx_spaces_form_profile_input_allow_post_to', '3', '', 'bx_spaces_data', 'id', 'author', 'BxSpacesPrivacyPost', 'modules/boonex/spaces/classes/BxSpacesPrivacyPost.php');
