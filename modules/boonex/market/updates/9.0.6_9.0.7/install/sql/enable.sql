-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_view_entry' AND `title`='_bx_market_page_block_title_entry_breadcrumb';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_market_view_entry', 1, 'bx_market', '', '_bx_market_page_block_title_entry_breadcrumb', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:17:"entity_breadcrumb";}', 0, 0, 1, 0);

UPDATE `sys_pages_blocks` SET `content`='a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"categories_list";s:6:"params";a:2:{i:0;s:14:"bx_market_cats";i:1;a:1:{s:10:"show_empty";b:1;}}s:5:"class";s:20:"TemplServiceCategory";}' WHERE `object`='bx_market_categories' AND `title`='_bx_market_page_block_title_cats';

DELETE FROM `sys_objects_page` WHERE `object`='bx_market_latest';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_latest', '_bx_market_page_title_sys_entries_latest', '_bx_market_page_title_entries_latest', 'bx_market', 5, 2147483647, 1, 'products-latest', 'page.php?i=products-latest', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_latest';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_latest', 1, 'bx_market', '_bx_market_page_block_title_latest_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_public";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 0, 1);


DELETE FROM `sys_objects_page` WHERE `object`='bx_market_featured';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_featured', '_bx_market_page_title_sys_entries_featured', '_bx_market_page_title_entries_featured', 'bx_market', 5, 2147483647, 1, 'products-featured', 'page.php?i=products-featured', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_featured';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_featured', 1, 'bx_market', '_bx_market_page_block_title_featured_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:15:"browse_featured";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 0, 1);


UPDATE `sys_pages_blocks` SET `copyable`='0' WHERE `object`='bx_market_popular' AND `title`='_bx_market_page_block_title_popular_entries';
UPDATE `sys_pages_blocks` SET `copyable`='0' WHERE `object`='bx_market_updated' AND `title`='_bx_market_page_block_title_updated_entries';


DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_home' AND `title` IN ('_bx_market_page_block_title_latest_entries', '_bx_market_page_block_title_recent_entries_view_extended', '_bx_market_page_block_title_updated_entries');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_home', 2, 'bx_market', '', '_bx_market_page_block_title_latest_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 1, 1),
('bx_market_home', 2, 'bx_market', '', '_bx_market_page_block_title_updated_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_updated";s:6:"params";a:2:{i:0;s:7:"gallery";i:1;b:0;}}', 0, 1, 0, 3);

UPDATE `sys_pages_blocks` SET `title`='_bx_market_page_block_title_featured_entries' WHERE `object`='bx_market_home' AND `title`='_bx_market_page_block_title_featured_entries_view_extended'; 
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_popular";s:6:"params";a:2:{i:0;s:7:"gallery";i:1;b:0;}}' WHERE `object`='bx_market_home' AND `title`='_bx_market_page_block_title_popular_entries'; 


UPDATE `sys_pages_blocks` SET `title`='_bx_market_page_block_title_latest_entries', `content`='a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_public";s:6:"params";a:2:{i:0;b:0;i:1;b:0;}}' WHERE `object`='sys_home' AND `title`='_bx_market_page_block_title_recent_entries';

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_author";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:2:{s:8:"per_page";s:26:"bx_market_per_page_profile";s:13:"empty_message";b:0;}}}' WHERE `module`='bx_market' AND `title`='_bx_market_page_block_title_view_profile';


DELETE FROM `sys_pages_blocks` WHERE `module`='bx_market' AND `title` IN ('_bx_market_page_block_title_recent_entries', '_bx_market_page_block_title_recent_entries_view_full', '_bx_market_page_block_title_featured_entries_view_extended', '_bx_market_page_block_title_featured_entries_view_full', '_bx_market_page_block_title_updated_entries_view_extended', '_bx_market_page_block_title_updated_entries_view_full');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('', 0, 'bx_market', '_bx_market_page_block_title_latest_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{s:9:"unit_view";s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 1),
('', 0, 'bx_market', '_bx_market_page_block_title_latest_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 2),
('', 0, 'bx_market', '_bx_market_page_block_title_featured_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{s:9:"unit_view";s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 3),
('', 0, 'bx_market', '_bx_market_page_block_title_featured_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 4),
('', 0, 'bx_market', '_bx_market_page_block_title_updated_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_updated";s:6:"params";a:1:{s:9:"unit_view";s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 5),
('', 0, 'bx_market', '_bx_market_page_block_title_updated_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_updated";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 6);


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_market_snippet_meta';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_snippet_meta', '_sys_menu_title_snippet_meta', 'bx_market_snippet_meta', 'bx_market', 15, 0, 1, 'BxMarketMenuSnippetMeta', 'modules/boonex/market/classes/BxMarketMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_market_snippet_meta';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_snippet_meta', 'bx_market', '_sys_menu_set_title_snippet_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_snippet_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_market_snippet_meta', 'bx_market', 'author', '_sys_menu_item_title_system_sm_author', '_sys_menu_item_title_sm_author', '', '', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_market_snippet_meta', 'bx_market', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', 2147483647, 0, 0, 1, 2),
('bx_market_snippet_meta', 'bx_market', 'category', '_sys_menu_item_title_system_sm_category', '_sys_menu_item_title_sm_category', '', '', '', '', '', 2147483647, 0, 0, 1, 3),
('bx_market_snippet_meta', 'bx_market', 'tags', '_sys_menu_item_title_system_sm_tags', '_sys_menu_item_title_sm_tags', '', '', '', '', '', 2147483647, 0, 0, 1, 4),
('bx_market_snippet_meta', 'bx_market', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', 2147483647, 0, 0, 1, 5),
('bx_market_snippet_meta', 'bx_market', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', 2147483647, 0, 0, 1, 6);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_submenu' AND `name` IN ('products-latest', 'products-featured', 'products-updated');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_submenu', 'bx_market', 'products-latest', '_bx_market_menu_item_title_system_entries_latest', '_bx_market_menu_item_title_entries_latest', 'page.php?i=products-latest', '', '', '', '', 2147483647, 1, 1, 2),
('bx_market_submenu', 'bx_market', 'products-featured', '_bx_market_menu_item_title_system_entries_featured', '_bx_market_menu_item_title_entries_featured', 'page.php?i=products-featured', '', '', '', '', 2147483647, 1, 1, 3),
('bx_market_submenu', 'bx_market', 'products-updated', '_bx_market_menu_item_title_system_entries_updated', '_bx_market_menu_item_title_entries_updated', 'page.php?i=products-updated', '', '', '', '', 2147483647, 1, 1, 5);

UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_market_submenu' AND `name`='products-popular';
UPDATE `sys_menu_items` SET `order`='6' WHERE `set_name`='bx_market_submenu' AND `name`='products-categories';
UPDATE `sys_menu_items` SET `order`='7' WHERE `set_name`='bx_market_submenu' AND `name`='products-search';
UPDATE `sys_menu_items` SET `order`='8' WHERE `set_name`='bx_market_submenu' AND `name`='products-manage';
