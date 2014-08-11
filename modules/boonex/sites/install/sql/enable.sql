-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_sites', '_bx_sites', 'bx_sites@modules/boonex/sites/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_sites', '_bx_sites', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_sites_payment_trial_max_number', '0', @iCategId, '_bx_sites_option_payment_trial_max_number', 'digit', '', '', '', 1),
('bx_sites_payment_trial_price', '5', @iCategId, '_bx_sites_option_payment_trial_price', 'digit', '', '', '', 2),
('bx_sites_payment_trial_period', 'Day', @iCategId, '_bx_sites_option_payment_trial_period', 'select', '', '', 'Day,Week,SemiMonth,Month,Year', 3),
('bx_sites_payment_trial_frequency', '1', @iCategId, '_bx_sites_option_payment_trial_frequency', 'digit', '', '', '', 4),
('bx_sites_payment_regular_price', '69', @iCategId, '_bx_sites_option_payment_regular_price', 'digit', '', '', '', 5),
('bx_sites_payment_regular_period', 'Day', @iCategId, '_bx_sites_option_payment_regular_period', 'select', '', '', 'Day,Week,SemiMonth,Month,Year', 6),
('bx_sites_payment_regular_frequency', '1', @iCategId, '_bx_sites_option_payment_regular_frequency', 'digit', '', '', '', 7),
('bx_sites_payment_email_business', 'uno@boonex.com', @iCategId, '_bx_sites_option_payment_email_business', 'digit', '', '', '', 8),
('bx_sites_payment_email_sandbox', 'vlnotna_business@gmail.com', @iCategId, '_bx_sites_option_payment_email_sandbox', 'digit', '', '', '', 9),
('bx_sites_payment_demo_mode', 'on', @iCategId, '_bx_sites_option_payment_demo_mode', 'checkbox', '', '', '', 10),
('bx_sites_payment_currency_code', 'USD', @iCategId, '_bx_sites_option_payment_currency_code', 'select', '', '', 'AUD,CAD,EUR,GBP,USD,YEN', 11),
('bx_sites_payment_currency_sign', '&#36;', @iCategId, '_bx_sites_option_payment_currency_sign', 'select', '', '', '&#8364;,&#163;,&#36;,&#165;', 12);


-- PAGES
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_sites_home', '_bx_sites_page_title_sys_sites_home', '_bx_sites_page_title_sites_home', 'bx_sites', 5, 2147483647, 1, 'sites-home', 'page.php?i=sites-home', '', '', '', 0, 1, 0, '', ''),
('bx_sites_subscribe', '_bx_sites_page_title_sys_subscribe', '_bx_sites_page_title_subscribe', 'bx_sites', 5, 2147483647, 1, 'site-subscribe', 'page.php?i=site-subscribe', '', '', '', 0, 1, 0, '', ''),
('bx_sites_site_create', '_bx_sites_page_title_sys_create_site', '_bx_sites_page_title_create_site', 'bx_sites', 5, 2147483647, 1, 'site-create', 'page.php?i=site-create', '', '', '', 0, 1, 0, '', ''),
('bx_sites_site_view', '_bx_sites_page_title_sys_site_view', '_bx_sites_page_title_site_view', 'bx_sites', 1, 2147483647, 1, 'site-view', 'page.php?i=site-view', '', '', '', 0, 1, 0, '', ''),
('bx_sites_site_edit', '_bx_sites_page_title_sys_site_edit', '_bx_sites_page_title_site_edit', 'bx_sites', 1, 2147483647, 1, 'site-edit', 'page.php?i=site-edit', '', '', '', 0, 1, 0, '', ''),
('bx_sites_site_delete', '_bx_sites_page_title_sys_site_delete', '_bx_sites_page_title_site_delete', 'bx_sites', 1, 2147483647, 1, 'site-delete', 'page.php?i=site-delete', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_sites_home', 1, 'bx_sites', '_bx_sites_page_block_title_sites_home', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_sites\";s:6:\"method\";s:6:\"browse\";}', 0, 0, 0),
('bx_sites_site_create', 1, 'bx_sites', '_bx_sites_page_block_title_create_site', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_sites\";s:6:\"method\";s:11:\"site_create\";}', 0, 1, 1),
('bx_sites_subscribe', 1, 'bx_sites', '_bx_sites_page_block_title_subscribe', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_sites\";s:6:\"method\";s:14:\"site_subscribe\";}', 0, 1, 1),
('bx_sites_site_view', 1, 'bx_sites', '_bx_sites_page_block_title_site_menu', 13, 2147483647, 'menu', 'bx_sites_view', 0, 1, 0),
('bx_sites_site_view', 2, 'bx_sites', '_bx_sites_page_block_title_site_overview', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_sites\";s:6:\"method\";s:9:\"site_view\";}', 0, 1, 0),
('bx_sites_site_edit', 1, 'bx_sites', '_bx_sites_page_block_title_site_menu', 13, 2147483647, 'menu', 'bx_sites_view', 0, 1, 0),
('bx_sites_site_edit', 2, 'bx_sites', '_bx_sites_page_block_title_site_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_sites\";s:6:\"method\";s:9:\"site_edit\";}', 0, 1, 0),
('bx_sites_site_delete', 1, 'bx_sites', '_bx_sites_page_block_title_site_menu', 13, 2147483647, 'menu', 'bx_sites_view', 0, 1, 0),
('bx_sites_site_delete', 2, 'bx_sites', '_bx_sites_page_block_title_site_delete', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_sites\";s:6:\"method\";s:11:\"site_delete\";}', 0, 1, 0);


-- MENU
SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_sites', 'sites-home', '_bx_sites_menu_item_title_system_sites_home', '_bx_sites_menu_item_title_sites_home', 'page.php?i=sites-home', '', '', 'globe col-green1', '', 226, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_sites', 'site-create', '_bx_sites_menu_item_title_system_create_site', '_bx_sites_menu_item_title_create_site', 'page.php?i=site-create', '', '', 'globe col-green1', '', 226, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_sites_view', '_bx_sites_menu_title_view_site', 'bx_sites_view', 'bx_sites', 6, 0, 1, 'BxSitesMenuViewSite', 'modules/boonex/sites/classes/BxSitesMenuViewSite.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_sites_view', 'bx_sites', '_bx_sites_menu_set_title_view_site', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_sites_view', 'bx_sites', 'view-note', '_bx_sites_menu_item_title_system_view_site', '_bx_sites_menu_item_title_view_site', 'page.php?i=site-view&id={content_id}', '', '', 'eye-open', '', 2147483647, 1, 0, 0),
('bx_sites_view', 'bx_sites', 'edit-note', '_bx_sites_menu_item_title_system_edit_site', '_bx_sites_menu_item_title_edit_site', 'page.php?i=site-edit&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 1),
('bx_sites_view', 'bx_sites', 'delete-note', '_bx_sites_menu_item_title_system_delete_site', '_bx_sites_menu_item_title_delete_site', 'page.php?i=site-delete&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 2);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_sites', 'create site', NULL, '_bx_sites_acl_action_create_site', '', 1, 3);
SET @iIdActionSiteCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_sites', 'manage sites', NULL, '_bx_sites_acl_action_manage_sites', '', 1, 3);
SET @iIdActionManageSites = LAST_INSERT_ID();


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

-- site create
(@iStandard, @iIdActionSiteCreate),
(@iModerator, @iIdActionSiteCreate),
(@iAdministrator, @iIdActionSiteCreate),
(@iPremium, @iIdActionSiteCreate),

-- manage sites
(@iModerator, @iIdActionManageSites),
(@iAdministrator, @iIdActionManageSites);


-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_sites', '_bx_sites_et_site_created', 'bx_sites_site_created', '_bx_sites_et_site_created_subject', '_bx_sites_et_site_created_body'),
('bx_sites', '_bx_sites_et_site_created_and_paid', 'bx_sites_site_created_and_paid', '_bx_sites_et_site_created_and_paid_subject', '_bx_sites_et_site_created_and_paid_body'),
('bx_sites', '_bx_sites_et_payment_received', 'bx_sites_payment_received', '_bx_sites_et_payment_received_subject', '_bx_sites_et_payment_received_body'),
('bx_sites', '_bx_sites_et_site_canceled', 'bx_sites_site_canceled', '_bx_sites_et_site_canceled_subject', '_bx_sites_et_site_canceled_body');


-- GRIDS
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_sites_browse', 'Sql', 'SELECT * FROM `bx_sites_accounts` WHERE 1 ', 'bx_sites_accounts', 'id', '', '', '', 100, NULL, 'start', '', 'domain,title,status', '', 'auto', 'domain,title,created,status', '', 'BxSitesGridBrowse', 'modules/boonex/sites/classes/BxSitesGridBrowse.php'),
('bx_sites_overview', 'Array', '', '', 'id', '', '', '', 100, NULL, '', '', 'transaction', '', 'auto', '', '', 'BxSitesGridOverview', 'modules/boonex/sites/classes/BxSitesGridOverview.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_sites_browse', 'domain', '_bx_sites_grid_browse_lbl_domain', '50%', 0, '64', '', 1),
('bx_sites_browse', 'created', '_bx_sites_grid_browse_lbl_created', '15%', 0, '32', '', 2),
('bx_sites_browse', 'status', '_bx_sites_grid_browse_lbl_status', '15%', 1, '32', '', 3),
('bx_sites_browse', 'actions', '', '20%', 0, '', '', 4),

('bx_sites_overview', 'title', '', '20%', 1, '16', '', 1),
('bx_sites_overview', 'type', '_bx_sites_grid_overview_lbl_type', '10%', 1, '16', '', 2),
('bx_sites_overview', 'transaction', '_bx_sites_grid_overview_lbl_transaction', '30%', 0, '64', '', 3),
('bx_sites_overview', 'when', '_bx_sites_grid_overview_lbl_when', '20%', 0, '16', '', 4),
('bx_sites_overview', 'amount', '_bx_sites_grid_overview_lbl_amount', '20%', 0, '16', '', 5);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_sites_browse', 'independent', 'add', '_bx_sites_grid_browse_btn_add', '', 0, 1),
('bx_sites_browse', 'single', 'view', '', 'globe', 0, 1),
('bx_sites_overview', 'independent', 'unconfirmed', '_bx_sites_grid_overview_btn_unconfirmed', '', 0, 1),
('bx_sites_overview', 'independent', 'pending', '_bx_sites_grid_overview_btn_pending', '', 0, 2),
('bx_sites_overview', 'independent', 'active', '_bx_sites_grid_overview_btn_active', '', 0, 3),
('bx_sites_overview', 'independent', 'canceled', '_bx_sites_grid_overview_btn_canceled', '', 0, 4),
('bx_sites_overview', 'independent', 'suspended', '_bx_sites_grid_overview_btn_suspended', '', 0, 5);

-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_sites', 'BxSitesResponse', 'modules/boonex/sites/classes/BxSitesResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'login', @iHandler);
