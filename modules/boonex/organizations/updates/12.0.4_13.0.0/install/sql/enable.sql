-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_entry_reports';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:14:\"entity_reports\";}', '', 0, '', 0, 0, 1, 6);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_orgs_page_block_title_sys_favorites_entries' WHERE `object`='bx_organizations_favorites' AND `title`='_bx_orgs_page_block_title_favorites_entries';

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_organizations' AND `title`='_bx_orgs_page_block_title_cover_block';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_organizations', '_bx_orgs_page_block_title_sys_cover_block', '_bx_orgs_page_block_title_cover_block', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:12:\"entity_cover\";}', '', 0, '', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENUS
UPDATE `sys_menu_items` SET `visibility_custom`='a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:22:"is_free_join_avaliable";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}' WHERE `set_name`='bx_organizations_view_actions' AND `name`='profile-fan-add';

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='view';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name`='audit';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_organizations&content_id={content_id}', '', '', '', 'history', '', '', 0, 192, '', 1, 0, 290);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_organizations_view_meta';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_meta', '_bx_orgs_menu_title_view_profile_meta', 'bx_organizations_view_meta', 'bx_organizations', 15, 0, 1, 'BxOrgsMenuViewMeta', 'modules/boonex/organizations/classes/BxOrgsMenuViewMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_organizations_view_meta';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_meta', 'bx_organizations', '_bx_orgs_menu_set_title_view_profile_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_meta', 'bx_organizations', 'membership', '_sys_menu_item_title_system_vm_membership', '_sys_menu_item_title_vm_membership', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 10),
('bx_organizations_view_meta', 'bx_organizations', 'badges', '_sys_menu_item_title_system_vm_badges', '_sys_menu_item_title_vm_badges', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 20),
('bx_organizations_view_meta', 'bx_organizations', 'members', '_sys_menu_item_title_system_sm_members', '_sys_menu_item_title_sm_members', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 30),
('bx_organizations_view_meta', 'bx_organizations', 'subscribers', '_sys_menu_item_title_system_sm_subscribers', '_sys_menu_item_title_sm_subscribers', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 40),
('bx_organizations_view_meta', 'bx_organizations', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 50),
('bx_organizations_view_meta', 'bx_organizations', 'votes', '_sys_menu_item_title_system_sm_votes', '_sys_menu_item_title_sm_votes', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 60),
('bx_organizations_view_meta', 'bx_organizations', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 70);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_organizations_view_submenu_cover';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='category';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_organizations_snippet_meta', 'bx_organizations', 'category', '_sys_menu_item_title_system_sm_category', '_sys_menu_item_title_sm_category', '', '', '', '', '', '', '', 2147483647, '', 0, 0, 1, 5);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_followings' AND `name`='organizations';
SET @iFollowingsMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_profile_followings' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('sys_profile_followings', 'bx_organizations', 'organizations', '_bx_orgs_menu_item_title_system_followings', '_bx_orgs_menu_item_title_followings', 'javascript:void(0)', '', '_self', 'briefcase col-red2', '', '', '', 2147483647, '', 1, 0, @iFollowingsMenuOrder + 1);


-- ACL
SET @iIdActionProfileDeleteAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_organizations' AND `Name`='delete any entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionProfileDeleteAny;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionProfileDeleteAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'delete any entry', NULL, '_bx_orgs_acl_action_delete_any_profile', '', 1, 3);
SET @iIdActionProfileDeleteAny = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iAdministrator, @iIdActionProfileDeleteAny);