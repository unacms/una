-- SETTINGS
UPDATE `sys_options` SET `value`='6' WHERE `name`='bx_market_per_page_profile';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_market' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_market_per_page_browse_showcase';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_market_per_page_browse_showcase', '32', @iCategId, '_sys_option_per_page_browse_showcase', 'digit', '', '', '', 23);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_market' AND `title` IN ('_bx_market_page_block_title_recent_entries_view_showcase', '_bx_market_page_block_title_popular_entries_view_showcase', '_bx_market_page_block_title_featured_entries_view_showcase');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('', 0, 'bx_market', '_bx_market_page_block_title_sys_recent_entries_view_showcase', '_bx_market_page_block_title_recent_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"browse_public\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 1),
('', 0, 'bx_market', '_bx_market_page_block_title_sys_popular_entries_view_showcase', '_bx_market_page_block_title_popular_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"browse_popular\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 2),
('', 0, 'bx_market', '_bx_market_page_block_title_sys_featured_entries_view_showcase', '_bx_market_page_block_title_featured_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:15:\"browse_featured\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 3);


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_market_view_popup';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_view' AND `name` IN ('edit-product', 'delete-product', 'unhide-product', 'product-more');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_market_view', 'bx_market', 'unhide-product', '_bx_market_menu_item_title_system_unhide_entry', '_bx_market_menu_item_title_unhide_entry', 'javascript:void(0);', 'javascript:{js_object}.perform(this, ''unhide-product'', {content_id});', '', 'eye', '', 0, 2147483647, 1, 0, 40),
('bx_market_view', 'bx_market', 'product-more', '_bx_market_menu_item_title_system_product_more', '_bx_market_menu_item_title_product_more', 'javascript:void(0)', 'bx_menu_popup(''bx_market_view_more'', this, {}, {id:{content_id}});', '', 'cog', 'bx_market_view_more', 1, 2147483647, 1, 0, 9999);

-- MENU: actions more menu for view entry
DELETE FROM `sys_objects_menu` WHERE `object`='bx_market_view_more';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view_more', '_bx_market_menu_title_view_entry_more', 'bx_market_view_more', 'bx_market', 6, 0, 1, 'BxMarketMenuView', 'modules/boonex/market/classes/BxMarketMenuView.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_market_view_more';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_view_more', 'bx_market', '_bx_market_menu_set_title_view_entry_more', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_view_more';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_view_more', 'bx_market', 'hide-product', '_bx_market_menu_item_title_system_hide_entry', '_bx_market_menu_item_title_hide_entry', 'javascript:void(0);', 'javascript:{js_object}.perform(this, ''hide-product'', {content_id});', '', 'eye-slash', '', 2147483647, 1, 0, 10),
('bx_market_view_more', 'bx_market', 'edit-product', '_bx_market_menu_item_title_system_edit_entry', '_bx_market_menu_item_title_edit_entry', 'page.php?i=edit-product&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 20),
('bx_market_view_more', 'bx_market', 'delete-product', '_bx_market_menu_item_title_system_delete_entry', '_bx_market_menu_item_title_delete_entry', 'page.php?i=delete-product&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 30);

-- MENU: actions more menu for snippet actions menu
DELETE FROM `sys_objects_menu` WHERE `object`='bx_market_snippet_more';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_market_snippet_more', '_bx_market_menu_title_snippet_more', 'bx_market_snippet_more', 'bx_market', 4, 0, 1, 'BxMarketMenuView', 'modules/boonex/market/classes/BxMarketMenuView.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_market_snippet_more';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES
('bx_market_snippet_more', 'bx_market', '_bx_market_menu_set_title_snippet_more', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_snippet_more';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_market_snippet_more', 'bx_market', 'download', '_bx_market_menu_item_title_system_download', '_bx_market_menu_item_title_download', 'page.php?i=download-product&id={content_id}', '', '', 'download', '', 0, 2147483647, 1, 0, 10),
('bx_market_snippet_more', 'bx_market', 'add-to-cart', '_bx_market_menu_item_title_system_add_to_cart', '{add_to_cart_title}', 'javascript:void(0);', 'javascript:{add_to_cart_onclick}', '', 'cart-plus', '', 0, 2147483647, 1, 0, 20),
('bx_market_snippet_more', 'bx_market', 'subscribe', '_bx_market_menu_item_title_system_subscribe', '{subscribe_title}', 'javascript:void(0);', 'javascript:{subscribe_onclick}', '', 'credit-card', '', 0, 2147483647, 1, 0, 30);


-- METATAGS
UPDATE `sys_objects_metatags` SET `table_mentions`='bx_market_meta_mentions' WHERE `object`='bx_market';
