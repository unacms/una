-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_ads' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_ads_enable_auto_approve', 'bx_ads_enable_auction');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_ads_enable_auto_approve', 'on', @iCategId, '_bx_ads_option_enable_auto_approve', 'checkbox', '', '', '', '', 0),
('bx_ads_enable_auction', '', @iCategId, '_bx_ads_option_enable_auction', 'checkbox', '', '', '', '', 2);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_view_entry' AND `title` IN ('_bx_ads_page_block_title_entry_offer_accepted', '_bx_ads_page_block_title_entry_reports');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_ads_view_entry', 3, 'bx_ads', '', '_bx_ads_page_block_title_entry_offer_accepted', 13, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:21:"entity_offer_accepted";s:6:"params";a:1:{i:0;s:4:"{id}";}}', '', 0, '', 0, 0, 1, 10),
('bx_ads_view_entry', 2, 'bx_ads', '', '_bx_ads_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:6:\"bx_ads\";s:6:\"method\";s:14:\"entity_reports\";}', '', 0, '', 0, 0, 1, 6);

DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_licenses';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `inj_head`, `inj_footer`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_licenses', '_bx_ads_page_title_sys_licenses', '_bx_ads_page_title_licenses', 'bx_ads', 5, 2147483647, 1, 'ads-licenses', '', '', '', '', 0, 1, '', '', 0, 'BxAdsPageLicenses', 'modules/boonex/ads/classes/BxAdsPageLicenses.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_licenses';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_ads_licenses', 1, 'bx_ads', '', '_bx_ads_page_block_title_licenses_note', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:19:"block_licenses_note";}', '', 0, '', 0, 0, 1, 0),
('bx_ads_licenses', 1, 'bx_ads', '', '_bx_ads_page_block_title_licenses', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:14:"block_licenses";}', '', 0, '', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_licenses_administration';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `inj_head`, `inj_footer`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_licenses_administration', '_bx_ads_page_title_sys_licenses_administration', '_bx_ads_page_title_licenses_administration', 'bx_ads', 5, 192, 1, 'ads-licenses-administration', '', '', '', '', 0, 1, '', '', 0, 'BxAdsPageLicenses', 'modules/boonex/ads/classes/BxAdsPageLicenses.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_licenses_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_ads_licenses_administration', 1, 'bx_ads', '', '_bx_ads_page_block_title_licenses_administration', 11, 192, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:29:"block_licenses_administration";}', '', 0, '', 0, 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_offers';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `inj_head`, `inj_footer`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_offers', '_bx_ads_page_title_sys_offers', '_bx_ads_page_title_offers', 'bx_ads', 5, 2147483647, 1, 'view-ad-offers', '', '', '', '', 0, 1, '', '', 0, 'BxAdsPageOffers', 'modules/boonex/ads/classes/BxAdsPageOffers.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_offers';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_ads_offers', 1, 'bx_ads', '', '_bx_ads_page_block_title_entry_breadcrumb', 13, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:17:"entity_breadcrumb";s:6:"params";a:1:{i:0;s:4:"{id}";}}', '', 0, '', 0, 0, 1, 1),
('bx_ads_offers', 1, 'bx_ads', '', '_bx_ads_page_block_title_offers', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:13:"entity_offers";s:6:"params";a:1:{i:0;s:4:"{id}";}}', '', 0, '', 0, 0, 1, 2);

DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_offers_all';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `inj_head`, `inj_footer`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_offers_all', '_bx_ads_page_title_sys_offers_all', '_bx_ads_page_title_offers_all', 'bx_ads', 5, 2147483647, 1, 'ads-offers', '', '', '', '', 0, 1, '', '', 0, '', '');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_offers_all';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_ads_offers_all', 1, 'bx_ads', '', '_bx_ads_page_block_title_offers_all', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:6:"offers";}', '', 0, '', 0, 0, 1, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_view' AND `name` IN ('add-to-cart', 'make-offer', 'view-offers', 'approve', 'shipped', 'received');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_ads_view', 'bx_ads', 'add-to-cart', '_bx_ads_menu_item_title_system_add_to_cart', '{add_to_cart_title}', 'javascript:void(0);', 'javascript:{add_to_cart_onclick}', '', 'cart-plus', '', '', '', 2147483647, '', 1, 0, 15),
('bx_ads_view', 'bx_ads', 'make-offer', '_bx_ads_menu_item_title_system_make_offer', '_bx_ads_menu_item_title_make_offer', 'javascript:void(0);', 'javascript:{js_object}.makeOffer(this, {content_id})', '', 'hand-holding-usd', '', '', '', 2147483647, '', 1, 0, 16),
('bx_ads_view', 'bx_ads', 'view-offers', '_bx_ads_menu_item_title_system_view_offers', '_bx_ads_menu_item_title_view_offers', 'page.php?i=view-ad-offers&id={content_id}', '', '', '', '', '', '', 2147483647, '', 1, 0, 17),
('bx_ads_view', 'bx_ads', 'approve', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this,  ''{module_uri}'', {content_id});', '', 'check', '', '', '', 2147483647, '', 1, 0, 40),
('bx_ads_view', 'bx_ads', 'shipped', '_bx_ads_menu_item_title_system_mark_shipped', '_bx_ads_menu_item_title_mark_shipped', 'javascript:void(0)', 'javascript:{js_object}.shipped(this, {content_id})', '', 'truck', '', '', '', 2147483647, '', 1, 0, 50),
('bx_ads_view', 'bx_ads', 'received', '_bx_ads_menu_item_title_system_mark_received', '_bx_ads_menu_item_title_mark_received', 'javascript:void(0)', 'javascript:{js_object}.received(this, {content_id})', '', 'clipboard-check', '', '', '', 2147483647, '', 1, 0, 55);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_view_actions' AND `name` IN ('add-to-cart', 'make-offer', 'view-offers', 'approve', 'shipped', 'received', 'notes', 'audit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_ads_view_actions', 'bx_ads', 'add-to-cart', '_bx_ads_menu_item_title_system_add_to_cart', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 15),
('bx_ads_view_actions', 'bx_ads', 'make-offer', '_bx_ads_menu_item_title_system_make_offer', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 16),
('bx_ads_view_actions', 'bx_ads', 'view-offers', '_bx_ads_menu_item_title_system_view_offers', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 17),
('bx_ads_view_actions', 'bx_ads', 'approve', '_sys_menu_item_title_system_va_approve', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 40),
('bx_ads_view_actions', 'bx_ads', 'shipped', '_bx_ads_menu_item_title_system_mark_shipped', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 50),
('bx_ads_view_actions', 'bx_ads', 'received', '_bx_ads_menu_item_title_system_mark_received', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 55),
('bx_ads_view_actions', 'bx_ads', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280),
('bx_ads_view_actions', 'bx_ads', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_courses&content_id={content_id}', '', '', 'history', '', '', '', 0, 192, '', 1, 0, 290);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_ads_licenses_submenu';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_licenses_submenu', '_bx_ads_menu_title_licenses_submenu', 'bx_ads_licenses_submenu', 'bx_ads', 6, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_ads_licenses_submenu';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_ads_licenses_submenu', 'bx_ads', '_bx_ads_menu_set_title_licenses_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_licenses_submenu';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_ads_licenses_submenu', 'bx_ads', 'ads-licenses-administration', '_bx_ads_menu_item_title_system_ads_licenses_administration', '_bx_ads_menu_item_title_ads_licenses_administration', 'page.php?i=ads-licenses-administration', '', '_self', '', '', '', '', 192, '', 1, 0, 1, 1),
('bx_ads_licenses_submenu', 'bx_ads', 'ads-licenses', '_bx_ads_menu_item_title_system_ads_licenses', '_bx_ads_menu_item_title_ads_licenses', 'page.php?i=ads-licenses', '', '_self', '', '', '', '', 2147483646, '', 1, 0, 1, 2);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_notifications' AND `name`='notifications-ads-offers';
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `active` = 1 AND `order` < 9999 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'bx_ads', 'notifications-ads-offers', '_bx_ads_menu_item_title_system_offers_all', '_bx_ads_menu_item_title_offers_all', 'page.php?i=ads-offers&profile_id={member_id}', '', '', 'ad col-green2', 'a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:16:"get_offers_count";s:6:"params";a:1:{i:0;s:8:"awaiting";}}', '', '', 2147483646, '', 1, 0, @iNotifMenuOrder + 1);

UPDATE `sys_menu_items` SET `icon`='ad' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='ads-administration';

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' AND `name`='dashboard-ads-licenses';
SET @iDashboardMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_dashboard', 'bx_ads', 'dashboard-ads-licenses', '_bx_ads_menu_item_title_system_licenses', '_bx_ads_menu_item_title_licenses', 'page.php?i=ads-licenses', '', '', 'ad col-green2', '', '', '', 2147483646, '', 1, 0, 1, @iDashboardMenuOrder + 1);


-- ACL
SET @iIdActionEntryDeleteAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_ads' AND `Name`='delete any entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionEntryDeleteAny;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEntryDeleteAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_ads', 'delete any entry', NULL, '_bx_ads_acl_action_delete_any_entry', '', 1, 3);
SET @iIdActionEntryDeleteAny = LAST_INSERT_ID();

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
(@iAdministrator, @iIdActionEntryDeleteAny);


-- GRIDS:
UPDATE `sys_grid_fields` SET `width`='15%', `translatable`='0' WHERE `object`='bx_ads_common' AND `name`='added';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_ads_common' AND `name`='status_admin';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_ads_common', 'status_admin', '_bx_ads_grid_column_title_adm_status_admin', '15%', 0, '16', '', 5);

DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_ads_licenses_administration', 'bx_ads_licenses');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_licenses_administration', 'Sql', 'SELECT `tl`.`id` AS `id`, `tl`.`profile_id` AS `profile_id`, `tl`.`entry_id` AS `entry_id`, `te`.`title` AS `entry`, `tl`.`count` AS `count`, `tl`.`order` AS `transaction`, `tl`.`license` AS `license`, `tl`.`added` AS `added` FROM `bx_ads_licenses` AS `tl` LEFT JOIN `bx_ads_entries` AS `te` ON `tl`.`entry_id`=`te`.`id` WHERE 1 ', 'bx_ads_licenses', 'id', 'added', '', '', 20, NULL, 'start', '', 'te`.`title,tl`.`order,tl`.`license', '', 'like', '', '', 192, 'BxAdsGridLicensesAdministration', 'modules/boonex/ads/classes/BxAdsGridLicensesAdministration.php'),
('bx_ads_licenses', 'Sql', 'SELECT `tl`.`id` AS `id`, `tl`.`profile_id` AS `profile_id`, `tl`.`entry_id` AS `entry_id`, `te`.`title` AS `entry`, `tl`.`count` AS `count`, `tl`.`order` AS `transaction`, `tl`.`license` AS `license`, `tl`.`added` AS `added` FROM `bx_ads_licenses` AS `tl` LEFT JOIN `bx_ads_entries` AS `te` ON `tl`.`entry_id`=`te`.`id` WHERE 1 ', 'bx_ads_licenses', 'id', 'added', '', '', 20, NULL, 'start', '', 'te`.`title,tl`.`order,tl`.`license', '', 'like', '', '', 2147483647, 'BxAdsGridLicenses', 'modules/boonex/ads/classes/BxAdsGridLicenses.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_ads_licenses_administration', 'bx_ads_licenses');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_ads_licenses_administration', 'profile_id', '_bx_ads_grid_column_title_lcs_profile_id', '20%', 0, '0', '', 1),
('bx_ads_licenses_administration', 'entry', '_bx_ads_grid_column_title_lcs_entry', '20%', 0, '0', '', 2),
('bx_ads_licenses_administration', 'count', '_bx_ads_grid_column_title_lcs_count', '5%', 0, '0', '', 3),
('bx_ads_licenses_administration', 'transaction', '_bx_ads_grid_column_title_lcs_transaction', '20%', 0, '32', '', 4),
('bx_ads_licenses_administration', 'license', '_bx_ads_grid_column_title_lcs_license', '15%', 0, '8', '', 5),
('bx_ads_licenses_administration', 'added', '_bx_ads_grid_column_title_lcs_added', '10%', 1, '25', '', 6),
('bx_ads_licenses_administration', 'actions', '', '10%', 0, '0', '', 7),

('bx_ads_licenses', 'entry', '_bx_ads_grid_column_title_lcs_entry', '25%', 0, '0', '', 1),
('bx_ads_licenses', 'count', '_bx_ads_grid_column_title_lcs_count', '5%', 0, '0', '', 2),
('bx_ads_licenses', 'transaction', '_bx_ads_grid_column_title_lcs_transaction', '25%', 0, '32', '', 3),
('bx_ads_licenses', 'license', '_bx_ads_grid_column_title_lcs_license', '15%', 0, '8', '', 4),
('bx_ads_licenses', 'added', '_bx_ads_grid_column_title_lcs_added', '20%', 1, '25', '', 5),
('bx_ads_licenses', 'actions', '', '10%', 0, '0', '', 6);

DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_ads_offers', 'bx_ads_offers_all');
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `show_total_count`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_offers', 'Sql', 'SELECT * FROM `bx_ads_offers` WHERE 1 ', 'bx_ads_offers', 'id', 'added', '', '', 20, NULL, 'start', '', 'message', '', 'like', '', '', 2147483647, 1, 'BxAdsGridOffers', 'modules/boonex/ads/classes/BxAdsGridOffers.php'),
('bx_ads_offers_all', 'Sql', 'SELECT `to`.*, SUM(IF(`to`.`status`=''awaiting'', 1, 0)) AS `offers_awating`, COUNT(`to`.`id`) AS `offers_total`, `te`.`title` AS `content_title` FROM `bx_ads_offers` AS `to` LEFT JOIN `bx_ads_entries` AS `te` ON `to`.`content_id`=`te`.`id` WHERE 1 ', 'bx_ads_offers', 'id', 'added', '', '', 20, NULL, 'start', '', '', '', 'like', '', '', 2147483647, 0, 'BxAdsGridOffersAll', 'modules/boonex/ads/classes/BxAdsGridOffersAll.php');

DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_ads_offers', 'bx_ads_offers_all');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_ads_offers', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_ads_offers', 'author_id', '_bx_ads_grid_column_title_ofr_author_id', '20%', 0, 0, '', 2),
('bx_ads_offers', 'amount', '_bx_ads_grid_column_title_ofr_amount', '14%', 0, 0, '', 3),
('bx_ads_offers', 'quantity', '_bx_ads_grid_column_title_ofr_quantity', '5%', 0, 0, '', 4),
('bx_ads_offers', 'message', '_bx_ads_grid_column_title_ofr_message', '24%', 0, '32', '', 5),
('bx_ads_offers', 'added', '_bx_ads_grid_column_title_ofr_added', '10%', 0, 0, '', 6),
('bx_ads_offers', 'status', '_bx_ads_grid_column_title_ofr_status', '5%', 0, 8, '', 7),
('bx_ads_offers', 'actions', '', '20%', 0, '', '', 8),

('bx_ads_offers_all', 'content_id', '_bx_ads_grid_column_title_ofrs_content_id', '60%', 0, 0, '', 1),
('bx_ads_offers_all', 'offers_awating', '_bx_ads_grid_column_title_ofrs_offers_awating', '10%', 0, 0, '', 2),
('bx_ads_offers_all', 'offers_total', '_bx_ads_grid_column_title_ofrs_offers_total', '10%', 0, 0, '', 3),
('bx_ads_offers_all', 'actions', '', '20%', 0, '', '', 4);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_ads_offers', 'bx_ads_offers_all');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_ads_offers', 'single', 'accept', '_bx_ads_grid_action_title_ofr_accept', 'check', 1, 1, 1),
('bx_ads_offers', 'single', 'decline', '_bx_ads_grid_action_title_ofr_decline', 'times', 1, 1, 2),

('bx_ads_offers_all', 'single', 'view', '_bx_ads_grid_action_title_ofr_view', 'share-square', 1, 0, 1);


-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name`='bx_ads';
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('bx_ads', 1, 'a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:16:"get_live_updates";s:6:"params";a:4:{i:0;s:8:"awaiting";i:1;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:2;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:24:"notifications-ads-offers";}i:3;s:7:"{count}";}}', 1);

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name` IN ('bx_ads_purchased', 'bx_ads_shipped', 'bx_ads_received', 'bx_ads_offer_added', 'bx_ads_offer_accepted', 'bx_ads_offer_declined', 'bx_ads_offer_canceled');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_ads', '_bx_ads_et_txt_name_purchased', 'bx_ads_purchased', '_bx_ads_et_txt_subject_purchased', '_bx_ads_et_txt_body_purchased'),
('bx_ads', '_bx_ads_et_txt_name_shipped', 'bx_ads_shipped', '_bx_ads_et_txt_subject_shipped', '_bx_ads_et_txt_body_shipped'),
('bx_ads', '_bx_ads_et_txt_name_received', 'bx_ads_received', '_bx_ads_et_txt_subject_received', '_bx_ads_et_txt_body_received'),
('bx_ads', '_bx_ads_et_txt_name_offer_added', 'bx_ads_offer_added', '_bx_ads_et_txt_subject_offer_added', '_bx_ads_et_txt_body_offer_added'),
('bx_ads', '_bx_ads_et_txt_name_offer_accepted', 'bx_ads_offer_accepted', '_bx_ads_et_txt_subject_offer_accepted', '_bx_ads_et_txt_body_offer_accepted'),
('bx_ads', '_bx_ads_et_txt_name_offer_declined', 'bx_ads_offer_declined', '_bx_ads_et_txt_subject_offer_declined', '_bx_ads_et_txt_body_offer_declined'),
('bx_ads', '_bx_ads_et_txt_name_offer_canceled', 'bx_ads_offer_canceled', '_bx_ads_et_txt_subject_offer_canceled', '_bx_ads_et_txt_body_offer_canceled');