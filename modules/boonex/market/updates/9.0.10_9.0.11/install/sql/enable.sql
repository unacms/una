-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='12' WHERE `object`='bx_market_view_entry' AND `layout_id`='7';

UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_breadcrumb' AND `order`='0';
UPDATE `sys_pages_blocks` SET `designbox_id`='13', `order`='1' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_text' AND `designbox_id`='11' AND `order`='0';
UPDATE `sys_pages_blocks` SET `active`='1', `order`='2' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_all_actions' AND  `active`='0' AND `order`='1';
UPDATE `sys_pages_blocks` SET `cell_id`='3', `order`='6' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_author_entries' AND  `cell_id`='2' AND `order`='4';
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_social_sharing' AND `active`='1';
UPDATE `sys_pages_blocks` SET `order`='4' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_rating' AND `order`='3';
UPDATE `sys_pages_blocks` SET `order`='3' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_author' AND `order`='4';
UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_context' AND `order`='5';
UPDATE `sys_pages_blocks` SET `order`='5' WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_location' AND `order`='7';
UPDATE `sys_pages_blocks` SET `order`='0' WHERE `object`='bx_market_view_entry' AND `title` IN ('_bx_market_page_block_title_entry_actions', '_bx_market_page_block_title_entry_location', '_bx_market_page_block_title_entry_attachments') AND `active`='0';

DELETE FROM `sys_objects_page` WHERE `object`='bx_market_licenses_administration';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_licenses_administration', '_bx_market_page_title_sys_licenses_administration', '_bx_market_page_title_licenses_administration', 'bx_market', 5, 192, 1, 'products-licenses-administration', '', '', '', '', 0, 1, 0, 'BxMarketPageLicenses', 'modules/boonex/market/classes/BxMarketPageLicenses.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_licenses_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_licenses_administration', 1, 'bx_market', '', '_bx_market_page_block_title_licenses_administration', 11, 192, 'service', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:29:"block_licenses_administration";}', 0, 0, 1, 0);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:14:\"browse_popular\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}' WHERE `module`='bx_market' AND `title`='_bx_market_page_block_title_popular_entries_view_showcase';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='play-circle' WHERE `set_name`='bx_market_view' AND `name`='unhide-product' AND `icon`='eye';
UPDATE `sys_menu_items` SET `icon`='stop-circle' WHERE `set_name`='bx_market_view_more' AND `name`='hide-product' AND `icon`='eye-slash';

DELETE FROM  `sys_objects_menu` WHERE `object`='bx_market_view_actions';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view_actions', '_sys_menu_title_view_actions', 'bx_market_view_actions', 'bx_market', 15, 0, 1, 'BxMarketMenuViewActions', 'modules/boonex/market/classes/BxMarketMenuViewActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_market_view_actions';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_view_actions', 'bx_market', '_sys_menu_set_title_view_actions', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_view_actions';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_view_actions', 'bx_market', 'download', '_bx_market_menu_item_title_system_download', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 10),
('bx_market_view_actions', 'bx_market', 'add-to-cart', '_bx_market_menu_item_title_system_add_to_cart', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 20),
('bx_market_view_actions', 'bx_market', 'subscribe', '_bx_market_menu_item_title_system_subscribe', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 30),
('bx_market_view_actions', 'bx_market', 'unhide-product', '_bx_market_menu_item_title_system_unhide_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 40),
('bx_market_view_actions', 'bx_market', 'hide-product', '_bx_market_menu_item_title_system_hide_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 50),
('bx_market_view_actions', 'bx_market', 'edit-product', '_bx_market_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 60),
('bx_market_view_actions', 'bx_market', 'delete-product', '_bx_market_menu_item_title_system_delete_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 70),
('bx_market_view_actions', 'bx_market', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 200),
('bx_market_view_actions', 'bx_market', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_market_view_actions', 'bx_market', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 220),
('bx_market_view_actions', 'bx_market', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_market_view_actions', 'bx_market', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_market_view_actions', 'bx_market', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_market_view_actions', 'bx_market', 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260),
('bx_market_view_actions', 'bx_market', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 270),
('bx_market_view_actions', 'bx_market', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 300),
('bx_market_view_actions', 'bx_market', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 310),
('bx_market_view_actions', 'bx_market', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 320),
('bx_market_view_actions', 'bx_market', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 330),
('bx_market_view_actions', 'bx_market', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);

DELETE FROM  `sys_objects_menu` WHERE `object`='bx_market_licenses_submenu';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_licenses_submenu', '_bx_market_menu_title_licenses_submenu', 'bx_market_licenses_submenu', 'bx_market', 6, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_market_licenses_submenu';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_licenses_submenu', 'bx_market', '_bx_market_menu_set_title_licenses_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_licenses_submenu';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_market_licenses_submenu', 'bx_market', 'products-licenses-administration', '_bx_market_menu_item_title_system_products_licenses_administration', '_bx_market_menu_item_title_products_licenses_administration', 'page.php?i=products-licenses-administration', '', '_self', '', '', '', 192, 1, 0, 1, 1),
('bx_market_licenses_submenu', 'bx_market', 'products-licenses', '_bx_market_menu_item_title_system_products_licenses', '_bx_market_menu_item_title_products_licenses', 'page.php?i=products-licenses', '', '_self', '', '', '', 2147483646, 1, 0, 1, 2);


-- GRIDS:
DELETE FROM `sys_objects_grid` WHERE `object`='bx_market_licenses_administration';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_market_licenses_administration', 'Sql', 'SELECT `tl`.`id` AS `id`, `tl`.`profile_id` AS `profile_id`, `tl`.`product_id` AS `product_id`, `tp`.`title` AS `product`, `tl`.`order` AS `transaction`, `tl`.`license` AS `license`, `tl`.`type` AS `type`, `tl`.`domain` AS `domain`, `tl`.`added` AS `added`, `tl`.`expired` AS `expired` FROM `bx_market_licenses` AS `tl` LEFT JOIN `bx_market_products` AS `tp` ON `tl`.`product_id`=`tp`.`id` WHERE 1 ', 'bx_market_licenses', 'id', 'added', '', '', 20, NULL, 'start', '', 'tp`.`title,tl`.`order,tl`.`license,tl`.`type,tl`.`domain', '', 'like', '', '', 192, 'BxMarketGridLicensesAdministration', 'modules/boonex/market/classes/BxMarketGridLicensesAdministration.php');

UPDATE `sys_objects_grid` SET `source`='SELECT `tl`.`id` AS `id`, `tl`.`profile_id` AS `profile_id`, `tl`.`product_id` AS `product_id`, `tp`.`title` AS `product`, `tl`.`order` AS `transaction`, `tl`.`license` AS `license`, `tl`.`type` AS `type`, `tl`.`domain` AS `domain`, `tl`.`added` AS `added`, `tl`.`expired` AS `expired` FROM `bx_market_licenses` AS `tl` LEFT JOIN `bx_market_products` AS `tp` ON `tl`.`product_id`=`tp`.`id` WHERE 1 ', `filter_fields`='tp`.`title,tl`.`order,tl`.`license,tl`.`type,tl`.`domain' WHERE `object`='bx_market_licenses';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_market_licenses_administration';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_market_licenses_administration', 'profile_id', '_bx_market_grid_column_title_lcs_profile_id', '10%', 0, '28', '', 1),
('bx_market_licenses_administration', 'product', '_bx_market_grid_column_title_lcs_product', '20%', 0, '28', '', 2),
('bx_market_licenses_administration', 'transaction', '_bx_market_grid_column_title_lcs_transaction', '10%', 0, '32', '', 3),
('bx_market_licenses_administration', 'license', '_bx_market_grid_column_title_lcs_license', '10%', 0, '8', '', 4),
('bx_market_licenses_administration', 'type', '_bx_market_grid_column_title_lcs_type', '5%', 1, '12', '', 5),
('bx_market_licenses_administration', 'domain', '_bx_market_grid_column_title_lcs_domain', '15%', 0, '18', '', 6),
('bx_market_licenses_administration', 'added', '_bx_market_grid_column_title_lcs_added', '10%', 1, '25', '', 7),
('bx_market_licenses_administration', 'expired', '_bx_market_grid_column_title_lcs_expired', '10%', 1, '25', '', 8),
('bx_market_licenses_administration', 'actions', '', '10%', 0, '', '', 9);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_market_licenses' AND `name`='product';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_market_licenses' AND `name`='transaction';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_market_licenses', 'transaction', '_bx_market_grid_column_title_lcs_transaction', '10%', 0, '32', '', 2);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_market_licenses_administration';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_market_licenses_administration', 'single', 'reset', '_bx_market_grid_action_title_lcs_reset', 'eraser', 1, 1, 1);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_market_purchased';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_market', '_bx_market_et_txt_name_purchased', 'bx_market_purchased', '_bx_market_et_txt_subject_purchased', '_bx_market_et_txt_body_purchased');
