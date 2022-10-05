-- PAGES 
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_fans' AND `title`='_bx_orgs_page_block_title_fans_invites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_fans', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_invites', '_bx_orgs_page_block_title_fans_invites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:13:"invites_table";}', 0, 0, 1, 2);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_menu_manage_tools' AND `name`='delete';


-- ACL
SET @iIdActionProfileDeleteInvites = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_organizations' AND `Name`='delete invites' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionProfileDeleteInvites;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionProfileDeleteInvites;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'delete invites', NULL, '_bx_orgs_acl_action_delete_invites', '', 1, 3);
SET @iIdActionProfileDeleteInvites = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionProfileDeleteInvites),
(@iAdministrator, @iIdActionProfileDeleteInvites);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_organizations_invites';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_invites', 'Sql', 'SELECT `i`.`id`, `i`.`invited_profile_id`, `i`.`added`, `i`.`author_profile_id` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON `a`.`id` = `p`.`account_id` INNER JOIN `bx_organizations_invites` AS `i` ON `i`.`invited_profile_id` = `p`.`id` ', 'bx_organizations_invites', 'id', 'i`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 0, 'BxOrgsGridInvites', 'modules/boonex/organizations/classes/BxOrgsGridInvites.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_organizations_invites';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_organizations_invites', 'name', '_sys_name', '33%', '', 10),
('bx_organizations_invites', 'added', '_sys_added', '33%', '', 20),
('bx_organizations_invites', 'actions', '', '34%', '', 30);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_invites';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_organizations_invites', 'single', 'delete', '', 'remove', 1, 10);


DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_administration' AND `type`='bulk' AND `name`='delete';
DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_common' AND `type`='bulk' AND `name`='delete';
