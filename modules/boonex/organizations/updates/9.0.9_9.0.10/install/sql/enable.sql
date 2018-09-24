-- SETTINGS
UPDATE `sys_options` SET `extra`='a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_memberships";s:6:"params";a:4:{s:11:"purchasable";b:0;s:6:"active";b:1;s:9:"translate";b:1;s:22:"filter_out_auto_levels";b:1;}s:5:"class";s:16:"TemplAclServices";}' WHERE `name`='bx_organizations_default_acl_level';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_organizations' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_organizations_labels';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_organizations_labels', '', @iCategId, '_sys_option_labels', 'text', '', '', '', 40);


-- PAGES
UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_profile_info' AND `order`='0';
UPDATE `sys_pages_blocks` SET `active`='0', `order`='0' WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_profile_all_actions';

-- MENUS
UPDATE `sys_menu_items` SET `icon`='sign-in-alt' WHERE `set_name`='bx_organizations_view_actions' AND `name`='profile-fan-add' AND `icon`='user-plus';
UPDATE `sys_menu_items` SET `icon`='sign-out-alt' WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='profile-fan-remove' AND `icon`='user-times';
UPDATE `sys_menu_items` SET `icon`='user-minus' WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='profile-friend-remove' AND `icon`='user-times';
UPDATE `sys_menu_items` SET `icon`='edit' WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='edit-organization-cover' AND `icon`='pencil-alt';
UPDATE `sys_menu_items` SET `icon`='user-friends' WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='invite-to-organization' AND `icon`='user-plus';
UPDATE `sys_menu_items` SET `icon`='user-times' WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='delete-organization-account' AND `icon`='remove';
UPDATE `sys_menu_items` SET `icon`='trash' WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='delete-organization-account-content' AND `icon`='remove';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_organizations_view_actions_all';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_actions_all', '_sys_menu_title_view_actions', 'bx_organizations_view_actions_all', 'bx_organizations', 15, 0, 1, 'BxOrgsMenuViewActionsAll', 'modules/boonex/organizations/classes/BxOrgsMenuViewActionsAll.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_organizations_view_actions_all';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', '_sys_menu_set_title_view_actions', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-fan-add', '_bx_organizations_menu_item_title_system_become_fan', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 10),
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-fan-remove', '_bx_organizations_menu_item_title_system_leave_group', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 20),
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-friend-add', '_bx_orgs_menu_item_title_system_befriend', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 30),
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-friend-remove', '_bx_orgs_menu_item_title_system_unfriend', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 40),
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-subscribe-add', '_bx_organizations_menu_item_title_system_subscribe', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 50),
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-subscribe-remove', '_bx_organizations_menu_item_title_system_unsubscribe', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 60),
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-set-acl-level', '_sys_menu_item_title_system_set_acl_level', '', '', '', '', '', '', '', 0, 192, 1, 0, 70),
('bx_organizations_view_actions_all', 'bx_organizations', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 200),
('bx_organizations_view_actions_all', 'bx_organizations', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_organizations_view_actions_all', 'bx_organizations', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 220),
('bx_organizations_view_actions_all', 'bx_organizations', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_organizations_view_actions_all', 'bx_organizations', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_organizations_view_actions_all', 'bx_organizations', 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_organizations_view_actions_all', 'bx_organizations', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260),
('bx_organizations_view_actions_all', 'bx_organizations', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 300),
('bx_organizations_view_actions_all', 'bx_organizations', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 310),
('bx_organizations_view_actions_all', 'bx_organizations', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 320),
('bx_organizations_view_actions_all', 'bx_organizations', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 330),
('bx_organizations_view_actions_all', 'bx_organizations', 'edit-organization-cover', '_bx_organizations_menu_item_title_system_edit_cover', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 400),
('bx_organizations_view_actions_all', 'bx_organizations', 'edit-organization-profile', '_bx_organizations_menu_item_title_system_edit_profile', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 410),
('bx_organizations_view_actions_all', 'bx_organizations', 'invite-to-organization', '_bx_organizations_menu_item_title_system_invite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 420),
('bx_organizations_view_actions_all', 'bx_organizations', 'delete-organization-profile', '_bx_organizations_menu_item_title_system_delete_profile', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 430),
('bx_organizations_view_actions_all', 'bx_organizations', 'delete-organization-account', '_bx_orgs_menu_item_title_system_delete_account', '', '', '', '', '', '', '', 0, 128, 1, 0, 440),
('bx_organizations_view_actions_all', 'bx_organizations', 'delete-organization-account-content', '_bx_orgs_menu_item_title_system_delete_account_content', '', '', '', '', '', '', '', 0, 128, 1, 0, 450),
('bx_organizations_view_actions_all', 'bx_organizations', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='membership';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_organizations_snippet_meta', 'bx_organizations', 'membership', '_sys_menu_item_title_system_sm_membership', '_sys_menu_item_title_sm_membership', '', '', '', '', '', 2147483647, 0, 0, 1, 14);
