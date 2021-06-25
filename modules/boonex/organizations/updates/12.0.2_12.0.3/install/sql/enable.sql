-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_join_profile';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_join_profile', 'join-organization-profile', '_bx_orgs_page_title_sys_join_profile', '_bx_orgs_page_title_join_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=join-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_join_profile';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_join_profile', 1, 'bx_organizations', '', '_bx_orgs_page_block_title_join_profile', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:11:"entity_join";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', '', 0, 0, 0, 0);

-- PAGE: manage profile pricing
DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_profile_pricing';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_pricing', 'edit-organization-pricing', '_bx_orgs_page_title_sys_profile_pricing', '_bx_orgs_page_title_profile_pricing', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=edit-organization-pricing', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_profile_pricing';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_profile_pricing', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_profile_pricing', '_bx_orgs_page_block_title_profile_pricing_link', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:14:"entity_pricing";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', '', 0, 0, 0, 1, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions' AND `name`='join-organization-profile';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', 'join-organization-profile', '_bx_orgs_menu_item_title_system_pay_and_join', '_bx_orgs_menu_item_title_pay_and_join', 'page.php?i=join-organization-profile&profile_id={profile_id}', '', '', 'sign-in-alt', '', '', '', 0, 2147483647, 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:22:"is_paid_join_avaliable";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 1, 0, 0, 4);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='edit-organization-pricing';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_organizations_view_actions_more', 'bx_organizations', 'edit-organization-pricing', '_bx_orgs_menu_item_title_system_edit_pricing', '_bx_orgs_menu_item_title_edit_pricing', 'page.php?i=edit-organization-pricing&profile_id={profile_id}', '', '', 'money-check-alt', '', '', '', 2147483647, 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:20:"is_pricing_avaliable";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 1, 0, 41);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name` IN ('join-organization-profile', 'edit-organization-pricing');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'join-organization-profile', '_bx_orgs_menu_item_title_system_pay_and_join', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 5),
('bx_organizations_view_actions_all', 'bx_organizations', 'edit-organization-pricing', '_bx_orgs_menu_item_title_system_edit_pricing', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 415);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='join-paid';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_organizations_snippet_meta', 'bx_organizations', 'join-paid', '_sys_menu_item_title_system_sm_join_paid', '_sys_menu_item_title_sm_join_paid', '', '', '', '', '', '', '', 2147483647, 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:33:"is_paid_join_avaliable_by_content";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}', 1, 0, 1, 10);

UPDATE `sys_menu_items` SET `visibility_custom`='a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:33:"is_free_join_avaliable_by_content";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='join';

UPDATE `sys_menu_items` SET `link`='page.php?i=organization-profile-subscriptions&profile_id={member_id}#subscribers' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-subscribed-me';


-- ACL
SET @iIdActionUsePaidJoin = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_organizations' AND `Name`='use paid join' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionUsePaidJoin;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionUsePaidJoin;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'use paid join', NULL, '_bx_orgs_acl_action_use_paid_join', '', 1, 1);
SET @iIdActionUsePaidJoin = LAST_INSERT_ID();

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
(@iStandard, @iIdActionUsePaidJoin),
(@iModerator, @iIdActionUsePaidJoin),
(@iAdministrator, @iIdActionUsePaidJoin),
(@iPremium, @iIdActionUsePaidJoin);


-- GRIDS
UPDATE `sys_objects_grid` SET `responsive`='1' WHERE `object`='bx_organizations_fans';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_organizations_fans' AND `name` IN ('role_added', 'role_expired');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_organizations_fans', 'role_added', '_bx_orgs_txt_role_added', '10%', '', 16),
('bx_organizations_fans', 'role_expired', '_bx_orgs_txt_role_expired', '10%', '', 17);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_organizations_fans' AND `name`='actions';

DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_organizations_prices_manage', 'bx_organizations_prices_view');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_prices_manage', 'Sql', 'SELECT * FROM `bx_organizations_prices` WHERE 1 ', 'bx_organizations_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'period,period_unit,price', '', 'like', '', '', 2147483647, 'BxOrgsGridPricesManage', 'modules/boonex/organizations/classes/BxOrgsGridPricesManage.php'),
('bx_organizations_prices_view', 'Sql', 'SELECT * FROM `bx_organizations_prices` WHERE 1 ', 'bx_organizations_prices', 'id', 'order', '', '', 100, NULL, 'start', '', 'period,period_unit,price', '', 'like', '', '', 2147483647, 'BxOrgsGridPricesView', 'modules/boonex/organizations/classes/BxOrgsGridPricesView.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_organizations_prices_manage', 'bx_organizations_prices_view');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_organizations_prices_manage', 'checkbox', '_sys_select', '1%', 0, 0, '', 1),
('bx_organizations_prices_manage', 'order', '', '1%', 0, 0, '', 2),
('bx_organizations_prices_manage', 'name', '_bx_orgs_grid_column_title_name', '38%', 0, 32, '', 3),
('bx_organizations_prices_manage', 'price', '_bx_orgs_grid_column_title_price', '20%', 0, 16, '', 4),
('bx_organizations_prices_manage', 'period', '_bx_orgs_grid_column_title_period', '20%', 0, 16, '', 5),
('bx_organizations_prices_manage', 'actions', '', '20%', 0, 0, '', 6),

('bx_organizations_prices_view', 'role_id', '_bx_orgs_grid_column_title_role_id', '40%', 0, 32, '', 1),
('bx_organizations_prices_view', 'price', '_bx_orgs_grid_column_title_price', '20%', 0, 16, '', 2),
('bx_organizations_prices_view', 'period', '_bx_orgs_grid_column_title_period', '20%', 0, 16, '', 3),
('bx_organizations_prices_view', 'actions', '', '20%', 0, 0, '', 4);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_organizations_prices_manage', 'bx_organizations_prices_view');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_organizations_prices_manage', 'independent', 'add', '_bx_orgs_grid_action_title_add', '', 0, 0, 1),
('bx_organizations_prices_manage', 'single', 'edit', '_bx_orgs_grid_action_title_edit', 'pencil-alt', 1, 0, 1),
('bx_organizations_prices_manage', 'single', 'delete', '_bx_orgs_grid_action_title_delete', 'remove', 1, 1, 2),
('bx_organizations_prices_manage', 'bulk', 'delete', '_bx_orgs_grid_action_title_delete', '', 0, 1, 1),

('bx_organizations_prices_view', 'single', 'buy', '_bx_orgs_grid_action_title_buy', 'cart-plus', 0, 0, 1),
('bx_organizations_prices_view', 'single', 'subscribe', '_bx_orgs_grid_action_title_subscribe', 'credit-card', 0, 0, 2),
('bx_organizations_prices_view', 'single', 'choose', '_bx_orgs_grid_action_title_choose', 'far check-square', 0, 0, 3);

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_organizations_pruning';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_organizations_pruning', '0 0 * * *', 'BxOrgsCronPruning', 'modules/boonex/organizations/classes/BxOrgsCronPruning.php', '');
