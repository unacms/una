SET @sName = 'bx_massmailer';

-- PAGE: create campaign
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_massmailer_create_campaign', '_bx_massmailer_page_title_sys_create_campaign', '_bx_massmailer_page_title_create_campaign', 'bx_massmailer', 5, 192, 1, 'create-campaign', 'page.php?i=create-campaign', '', '', '', 0, 1, 0, 'BxMassMailerPageBrowse', 'modules/boonex/massmailer/classes/BxMassMailerPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_massmailer_create_campaign', 1, 'bx_massmailer', '_bx_massmailer_page_block_title_create_campaign', 11, 192, 'service', 'a:2:{s:6:"module";s:13:"bx_massmailer";s:6:"method";s:13:"entity_create";}', 0, 1, 1);

-- PAGE: edit campaign
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_massmailer_edit_campaign', '_bx_massmailer_page_title_sys_edit_campaign', '_bx_massmailer_page_title_edit_campaign', 'bx_massmailer', 5, 192, 1, 'edit-campaign', '', '', '', '', 0, 1, 0, 'BxMassMailerPageBrowse', 'modules/boonex/massmailer/classes/BxMassMailerPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_massmailer_edit_campaign', 1, 'bx_massmailer', '_bx_massmailer_page_block_title_edit_campaign', 11, 192, 'service', 'a:2:{s:6:"module";s:13:"bx_massmailer";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);

-- PAGE: view campaign
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_massmailer_view_campaign', '_bx_massmailer_page_title_sys_view_campaign', '_bx_massmailer_page_title_view_campaign', 'bx_massmailer', 5, 192, 1, 'view-campaign', '', '', '', '', 0, 1, 0, 'BxMassMailerPageBrowse', 'modules/boonex/massmailer/classes/BxMassMailerPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_massmailer_view_campaign', 1, 'bx_massmailer', '_bx_massmailer_page_block_title_view_campaign', 11, 192, 'service', 'a:2:{s:6:"module";s:13:"bx_massmailer";s:6:"method";s:11:"entity_view";}', 0, 0, 0);

-- PAGE: campaigns administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_massmailer_campaigns', '_bx_massmailer_page_title_sys_manage_campaigns', '_bx_massmailer_page_title_manage_campaigns', @sName, 5, 192, 1, 'massmailer-campaigns', 'page.php?i=massmailer-campaigns', '', '', '', 0, 1, 0, 'BxMassMailerPageBrowse', 'modules/boonex/massmailer/classes/BxMassMailerPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_massmailer_campaigns', 1, @sName, '_bx_massmailer_page_block_title_system_manage_campaigns', '_bx_massmailer_page_block_title_manage_campaigns', 11, 192, 'service', 'a:3:{s:6:\"module\";s:13:\"bx_massmailer\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:9:\"campaigns\";}}', 0, 1, 0);

-- PAGE: segments administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_massmailer_segments', '_bx_massmailer_page_title_sys_manage_segments', '_bx_massmailer_page_title_manage_segments', @sName, 5, 192, 1, 'massmailer-segments', 'page.php?i=massmailer-segments', '', '', '', 0, 1, 0, 'BxMassMailerPageBrowse', 'modules/boonex/massmailer/classes/BxMassMailerPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_massmailer_segments', 1, @sName, '_bx_massmailer_page_block_title_system_manage_segments', '_bx_massmailer_page_block_title_manage_segments', 11, 192, 'service', 'a:3:{s:6:\"module\";s:13:\"bx_massmailer\";s:6:\"method\";s:12:\"manage_tool1\";s:6:\"params\";a:1:{i:0;s:8:\"segments\";}}', 0, 1, 0);


-- MENU: module sub-menu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_massmailer_submenu', '_bx_massmailer_menu_title_submenu', 'bx_massmailer_submenu', 'bx_massmailer', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_massmailer_submenu', 'bx_massmailer', '_bx_massmailer_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_massmailer_submenu', 'bx_massmailer', 'massmailer-campaigns', '_bx_massmailer_menu_item_title_system_campaigns', '_bx_massmailer_menu_item_title_campaigns', 'page.php?i=massmailer-campaigns', '', '', '', '', 192, 1, 1, 1),
('bx_massmailer_submenu', 'bx_massmailer', 'massmailer-segments', '_bx_massmailer_menu_item_title_system_segments', '_bx_massmailer_menu_item_title_segments', 'page.php?i=massmailer-segments', '', '', '', '', 192, 1, 1, 3);

-- MENU: add to "add content" menu
SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_massmailer', 'create-campaign', '_bx_massmailer_menu_item_title_system_create_entry', '_bx_massmailer_menu_item_title_create_entry', 'page.php?i=create-campaign', '', '', 'envelope col-red2', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', @sName, 'massmailer-campaigns', '_bx_massmailer_menu_item_title_system_admt_mailer', '_bx_massmailer_menu_item_title_admt_mailer', 'page.php?i=massmailer-campaigns', '', '_self', '', 'a:2:{s:6:"module";s:13:"bx_massmailer";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);

-- GRIDS: campaigns administration
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_massmailer_campaigns', 'Sql', 'SELECT * FROM `bx_massmailer_campaigns` WHERE 1 ', 'bx_massmailer_campaigns', 'id', 'date_created', '', '', 20, NULL, 'start', '', 'title', '', 'like', '', '', 192, 'BxMassMailerGridCampaigns', 'modules/boonex/massmailer/classes/BxMassMailerGridCampaigns.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_massmailer_campaigns', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_massmailer_campaigns', 'title', '_bx_massmailer_grid_column_title_adm_title', '33%', 0, '22', '', 2),
('bx_massmailer_campaigns', 'author', '_bx_massmailer_grid_column_title_adm_author', '20%', 0, '22', '', 3),
('bx_massmailer_campaigns', 'date_created', '_bx_massmailer_grid_column_title_adm_date_created', '10%', 0, '15', '', 4),
('bx_massmailer_campaigns', 'date_sent', '_bx_massmailer_grid_column_title_adm_date_sent', '10%', 0, '22', '', 5),
('bx_massmailer_campaigns', 'actions', '', '25%', 0, '', '', 6);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_massmailer_campaigns', 'bulk', 'delete', '_bx_massmailer_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_massmailer_campaigns', 'single', 'edit', '_bx_massmailer_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 1),
('bx_massmailer_campaigns', 'single', 'delete', '_bx_massmailer_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_massmailer_campaigns', 'single', 'copy', '_bx_massmailer_grid_action_title_adm_copy', 'copy', 1, 0, 4),
('bx_massmailer_campaigns', 'single', 'send_test', '_bx_massmailer_grid_action_title_adm_send_test', 'envelope-open', 1, 0, 5),
('bx_massmailer_campaigns', 'single', 'view', '_bx_massmailer_grid_action_title_adm_view', 'eye', 1, 0, 6),
('bx_massmailer_campaigns', 'single', 'send_all', '_bx_massmailer_grid_action_title_adm_send_all', 'envelope', 1, 0, 7),
('bx_massmailer_campaigns', 'independent', 'add', '_bx_massmailer_grid_action_title_adm_add', '', 0, 0, 1);

-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
(@sName, '_bx_massmailer_email_name', 'bx_massmailer_email', '_bx_massmailer_email_subject', '_bx_massmailer_email_body');

-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_massmailer', 'create entry', NULL, '_bx_massmailer_acl_action_create_entry', '', 1, 3);
SET @iIdActionEntryCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_massmailer', 'delete entry', NULL, '_bx_massmailer_acl_action_delete_entry', '', 1, 3);
SET @iIdActionEntryDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_massmailer', 'view entry', NULL, '_bx_massmailer_acl_action_view_entry', '', 1, 0);
SET @iIdActionEntryView = LAST_INSERT_ID();

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

-- entry create
(@iModerator, @iIdActionEntryCreate),
(@iAdministrator, @iIdActionEntryCreate),

-- entry delete
(@iModerator, @iIdActionEntryDelete),
(@iAdministrator, @iIdActionEntryDelete),

-- entry view
(@iModerator, @iIdActionEntryView),
(@iAdministrator, @iIdActionEntryView);