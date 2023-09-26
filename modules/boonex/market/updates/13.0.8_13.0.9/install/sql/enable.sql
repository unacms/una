-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_market_category';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_category', 'products-category', '_bx_market_page_title_sys_entries_by_category', '_bx_market_page_title_entries_by_category', 'bx_market', 1, 2147483647, 1, 'page.php?i=products-category', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_category';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_category', 1, 'bx_market', '_bx_market_page_block_title_cats', '_bx_market_page_block_title_cats', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"categories_list";s:6:"params";a:2:{i:0;s:14:"bx_market_cats";i:1;a:1:{s:10:"show_empty";b:1;}}s:5:"class";s:20:"TemplServiceCategory";}', 0, 0, 1, 1),
('bx_market_category', 2, 'bx_market', '_bx_market_page_block_title_sys_entries_by_category', '_bx_market_page_block_title_entries_by_category', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:15:"browse_category";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 0, 1, 1);
