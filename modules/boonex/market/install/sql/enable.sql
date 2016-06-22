
-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_market', '_bx_market', 'bx_market@modules/boonex/market/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_market', '_bx_market', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_market_enable_recurring', 'on', @iCategId, '_bx_market_option_enable_recurring', 'checkbox', '', '', '', 1),
('bx_market_recurring_reserve', '4', @iCategId, '_bx_market_option_recurring_reserve', 'digit', '', '', '', 2),
('bx_market_summary_chars', '700', @iCategId, '_bx_market_option_summary_chars', 'digit', '', '', '', 10),
('bx_market_plain_summary_chars', '240', @iCategId, '_bx_market_option_plain_summary_chars', 'digit', '', '', '', 11),
('bx_market_per_page_browse', '12', @iCategId, '_bx_market_option_per_page_browse', 'digit', '', '', '', 20),
('bx_market_per_page_profile', '3', @iCategId, '_bx_market_option_per_page_profile', 'digit', '', '', '', 21),
('bx_market_rss_num', '10', @iCategId, '_bx_market_option_rss_num', 'digit', '', '', '', 25);

-- PAGE: create entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_create_entry', '_bx_market_page_title_sys_create_entry', '_bx_market_page_title_create_entry', 'bx_market', 5, 2147483647, 1, 'create-product', 'page.php?i=create-product', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_market_create_entry', 1, 'bx_market', '_bx_market_page_block_title_create_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"entity_create";}', 0, 1, 1);


-- PAGE: edit entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_edit_entry', '_bx_market_page_title_sys_edit_entry', '_bx_market_page_title_edit_entry', 'bx_market', 5, 2147483647, 1, 'edit-product', '', '', '', '', 0, 1, 0, 'BxMarketPageEntry', 'modules/boonex/market/classes/BxMarketPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_market_edit_entry', 1, 'bx_market', '_bx_market_page_block_title_edit_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);


-- PAGE: delete entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_delete_entry', '_bx_market_page_title_sys_delete_entry', '_bx_market_page_title_delete_entry', 'bx_market', 5, 2147483647, 1, 'delete-product', '', '', '', '', 0, 1, 0, 'BxMarketPageEntry', 'modules/boonex/market/classes/BxMarketPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_market_delete_entry', 1, 'bx_market', '_bx_market_page_block_title_delete_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"entity_delete";}', 0, 0, 0);


-- PAGE: download entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_download_entry', '_bx_market_page_title_sys_download_entry', '_bx_market_page_title_download_entry', 'bx_market', 5, 2147483647, 1, 'download-product', '', '', '', '', 0, 1, 0, 'BxMarketPageEntry', 'modules/boonex/market/classes/BxMarketPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_market_download_entry', 1, 'bx_market', '_bx_market_page_block_title_download_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:15:"entity_download";}', 0, 0, 0);


-- PAGE: view entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view_entry', '_bx_market_page_title_sys_view_entry', '_bx_market_page_title_view_entry', 'bx_market', 7, 2147483647, 1, 'view-product', '', '', '', '', 0, 1, 0, 'BxMarketPageEntry', 'modules/boonex/market/classes/BxMarketPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_view_entry', 2, 'bx_market', '', '_bx_market_page_block_title_entry_text', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 0),
('bx_market_view_entry', 2, 'bx_market', '', '_bx_market_page_block_title_entry_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:18:\"entity_all_actions\";}', 0, 0, 0, 1),
('bx_market_view_entry', 2, 'bx_market', '', '_bx_market_page_block_title_entry_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0, 2),
('bx_market_view_entry', 2, 'bx_market', '', '_bx_market_page_block_title_entry_social_sharing', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 0, 3),
('bx_market_view_entry', 2, 'bx_market', '', '_bx_market_page_block_title_entry_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1, 4),
('bx_market_view_entry', 2, 'bx_market', '', '_bx_market_page_block_title_entry_author_entries', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:22:\"entity_author_entities\";}', 0, 0, 1, 5),
('bx_market_view_entry', 3, 'bx_market', '', '_bx_market_page_block_title_entry_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 0),
('bx_market_view_entry', 3, 'bx_market', '', '_bx_market_page_block_title_entry_rating', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"entity_rating\";}', 0, 0, 1, 1),
('bx_market_view_entry', 3, 'bx_market', '', '_bx_market_page_block_title_entry_author', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"entity_author\";}', 0, 0, 1, 2),
('bx_market_view_entry', 3, 'bx_market', '', '_bx_market_page_block_title_entry_location', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:15:\"entity_location\";}', 0, 0, 0, 4),
('bx_market_view_entry', 3, 'bx_market', '', '_bx_market_page_block_title_entry_location', 3, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:13:\"locations_map\";s:6:\"params\";a:2:{i:0;s:9:\"bx_market\";i:1;s:4:\"{id}\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 0, 1, 5),
('bx_market_view_entry', 3, 'bx_market', '', '_bx_market_page_block_title_entry_attachments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:18:\"entity_attachments\";}', 0, 0, 0, 6);



-- PAGE: view entry comments
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view_entry_comments', '_bx_market_page_title_sys_view_entry_comments', '_bx_market_page_title_view_entry_comments', 'bx_market', 5, 2147483647, 1, 'view-product-comments', '', '', '', '', 0, 1, 0, 'BxMarketPageEntry', 'modules/boonex/market/classes/BxMarketPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_view_entry_comments', 1, 'bx_market', '_bx_market_page_block_title_entry_comments', '_bx_market_page_block_title_entry_comments_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1);


-- PAGE: view entry info
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view_entry_info', '_bx_market_page_title_sys_view_entry_info', '_bx_market_page_title_view_entry_info', 'bx_market', 5, 2147483647, 1, 'view-product-info', '', '', '', '', 0, 1, 0, 'BxMarketPageEntry', 'modules/boonex/market/classes/BxMarketPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_view_entry_info', 1, 'bx_market', '_bx_market_page_block_title_entry_info', '_bx_market_page_block_title_entry_info_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:16:\"entity_info_full\";}', 0, 0, 1),
('bx_market_view_entry_info', 1, 'bx_market', '', '_bx_market_page_block_title_entry_text', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 2);


-- PAGE: categories
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_categories', '_bx_market_page_title_sys_entries_categories', '_bx_market_page_title_entries_categories', 'bx_market', 1, 2147483647, 1, 'products-categories', 'page.php?i=products-categories', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_categories', 1, 'bx_market', '_bx_market_page_block_title_cats', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:15:\"categories_list\";s:6:\"params\";a:2:{i:0;s:14:\"bx_market_cats\";i:1;b:0;}s:5:\"class\";s:20:\"TemplServiceCategory\";}', 0, 1, 1),
('bx_market_categories', 2, 'bx_market', '_bx_market_page_block_title_categories_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:15:"browse_category";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 1, 1);


-- PAGE: popular entries
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_popular', '_bx_market_page_title_sys_entries_popular', '_bx_market_page_title_entries_popular', 'bx_market', 5, 2147483647, 1, 'products-popular', 'page.php?i=products-popular', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_popular', 1, 'bx_market', '_bx_market_page_block_title_popular_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_popular";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);


-- PAGE: recently updated entries
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_updated', '_bx_market_page_title_sys_entries_updated', '_bx_market_page_title_entries_updated', 'bx_market', 5, 2147483647, 1, 'products-updated', 'page.php?i=products-updated', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_updated', 1, 'bx_market', '_bx_market_page_block_title_updated_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_updated";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);


-- PAGE: entries of author
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_author', 'products-author', '_bx_market_page_title_sys_entries_of_author', '_bx_market_page_title_entries_of_author', 'bx_market', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxMarketPageAuthor', 'modules/boonex/market/classes/BxMarketPageAuthor.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_author', 1, 'bx_market', '', '_bx_market_page_block_title_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:18:\"my_entries_actions\";}', 0, 0, 1, 0),
('bx_market_author', 1, 'bx_market', '_bx_market_page_block_title_sys_entries_of_author', '_bx_market_page_block_title_entries_of_author', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"browse_author\";}', 0, 0, 1, 1);

-- PAGE: profile's licenses
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_licenses', '_bx_market_page_title_sys_licenses', '_bx_market_page_title_licenses', 'bx_market', 5, 2147483647, 1, 'products-licenses', '', '', '', '', 0, 1, 0, 'BxMarketPageLicenses', 'modules/boonex/market/classes/BxMarketPageLicenses.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_licenses', 1, 'bx_market', '', '_bx_market_page_block_title_licenses', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:14:\"block_licenses\";}', 0, 0, 1, 0);

-- PAGE: module home
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_home', 'products-home', '_bx_market_page_title_sys_home', '_bx_market_page_title_home', 'bx_market', 1, 2147483647, 1, 'page.php?i=products-home', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_home', 1, 'bx_market', '', '_bx_market_page_block_title_popular_keywords', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:14:\"keywords_cloud\";s:6:\"params\";a:2:{i:0;s:9:\"bx_market\";i:1;s:9:\"bx_market\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 1, 1, 0),
('bx_market_home', 1, 'bx_market', '', '_bx_market_page_block_title_cats', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:15:\"categories_list\";s:6:\"params\";a:2:{i:0;s:14:\"bx_market_cats\";i:1;a:1:{s:10:\"show_empty\";b:1;}}s:5:\"class\";s:20:\"TemplServiceCategory\";}', 0, 1, 1, 1),
('bx_market_home', 2, 'bx_market', '', '_bx_market_page_block_title_recent_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 1, 0),
('bx_market_home', 2, 'bx_market', '', '_bx_market_page_block_title_popular_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 1, 1);

-- PAGE: module manage own
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_manage', '_bx_market_page_title_sys_manage', '_bx_market_page_title_manage', 'bx_market', 5, 2147483647, 1, 'products-manage', 'page.php?i=products-manage', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_manage', 1, 'bx_market', '_bx_market_page_block_title_system_manage', '_bx_market_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:12:\"manage_tools\";}}', 0, 1, 0);

-- PAGE: module manage all
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_administration', '_bx_market_page_title_sys_manage_administration', '_bx_market_page_title_manage', 'bx_market', 5, 192, 1, 'products-administration', 'page.php?i=products-administration', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_market_administration', 1, 'bx_market', '_bx_market_page_block_title_system_manage_administration', '_bx_market_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);

-- PAGE: add block to homepage
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('sys_home', 1, 'bx_market', '_bx_market_page_block_title_recent_entries', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"browse_public\";}', 1, 0, 0, IFNULL(@iBlockOrder, 0) + 1);

-- PAGES: add page block to profiles modules (trigger* page objects are processed separately upon modules enable/disable)
SET @iPBCellProfile = 3;
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_market', '_bx_market_page_block_title_view_profile', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"browse_author\";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:1:{s:8:"per_page";s:26:"bx_market_per_page_profile";}}}', 0, 0, 0);

-- PAGE: service blocks
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('', 0, 'bx_market', '_bx_market_page_block_title_recent_entries', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:13:\"browse_public\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:1;s:13:\"ajax_paginate\";b:0;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 1),

('', 0, 'bx_market', '_bx_market_page_block_title_recent_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 2),
('', 0, 'bx_market', '_bx_market_page_block_title_popular_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 3),
('', 0, 'bx_market', '_bx_market_page_block_title_popular_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_market";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 4);


-- MENU: add to site menu
SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_market', 'products-home', '_bx_market_menu_item_title_system_entries_home', '_bx_market_menu_item_title_entries_home', 'page.php?i=products-home', '', '', 'shopping-cart col-green3', 'bx_market_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu
SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_market', 'products-home', '_bx_market_menu_item_title_system_entries_home', '_bx_market_menu_item_title_entries_home', 'page.php?i=products-home', '', '', 'shopping-cart col-green3', 'bx_market_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

-- MENU: add to "add content" menu
SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_market', 'create-product', '_bx_market_menu_item_title_system_create_entry', '_bx_market_menu_item_title_create_entry', 'page.php?i=create-product', '', '', 'shopping-cart col-green3', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);


-- MENU: actions menu for view entry 
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view', '_bx_market_menu_title_view_entry', 'bx_market_view', 'bx_market', 9, 0, 1, 'BxMarketMenuView', 'modules/boonex/market/classes/BxMarketMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_view', 'bx_market', '_bx_market_menu_set_title_view_entry', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_view', 'bx_market', 'edit-product', '_bx_market_menu_item_title_system_edit_entry', '_bx_market_menu_item_title_edit_entry', 'page.php?i=edit-product&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 1),
('bx_market_view', 'bx_market', 'delete-product', '_bx_market_menu_item_title_system_delete_entry', '_bx_market_menu_item_title_delete_entry', 'page.php?i=delete-product&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 2),
('bx_market_view', 'bx_market', 'download', '_bx_market_menu_item_title_system_download', '_bx_market_menu_item_title_download', 'page.php?i=download-product&id={content_id}', '', '', 'download', '', 2147483647, 1, 0, 3),
('bx_market_view', 'bx_market', 'add-to-cart', '_bx_market_menu_item_title_system_add_to_cart', '{add_to_cart_title}', 'javascript:void(0);', 'javascript:{add_to_cart_onclick}', '', 'cart-plus', '', 2147483647, 1, 0, 4),
('bx_market_view', 'bx_market', 'subscribe', '_bx_market_menu_item_title_system_subscribe', '{subscribe_title}', 'javascript:void(0);', 'javascript:{subscribe_onclick}', '', 'money', '', 2147483647, 1, 0, 5);


-- MENU: actions menu for my entries
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_my', '_bx_market_menu_title_entries_my', 'bx_market_my', 'bx_market', 9, 0, 1, 'BxMarketMenu', 'modules/boonex/market/classes/BxMarketMenu.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_my', 'bx_market', '_bx_market_menu_set_title_entries_my', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_my', 'bx_market', 'create-product', '_bx_market_menu_item_title_system_create_entry', '_bx_market_menu_item_title_create_entry', 'page.php?i=create-product', '', '', 'plus', '', 2147483647, 1, 0, 0);


-- MENU: module sub-menu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_submenu', '_bx_market_menu_title_submenu', 'bx_market_submenu', 'bx_market', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_submenu', 'bx_market', '_bx_market_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_submenu', 'bx_market', 'products-home', '_bx_market_menu_item_title_system_entries_public', '_bx_market_menu_item_title_entries_public', 'page.php?i=products-home', '', '', '', '', 2147483647, 1, 1, 1),
('bx_market_submenu', 'bx_market', 'products-categories', '_bx_market_menu_item_title_system_entries_categories', '_bx_market_menu_item_title_entries_categories', 'page.php?i=products-categories', '', '', '', '', 2147483647, 1, 1, 2),
('bx_market_submenu', 'bx_market', 'products-popular', '_bx_market_menu_item_title_system_entries_popular', '_bx_market_menu_item_title_entries_popular', 'page.php?i=products-popular', '', '', '', '', 2147483647, 1, 1, 3),
('bx_market_submenu', 'bx_market', 'products-manage', '_bx_market_menu_item_title_system_entries_manage', '_bx_market_menu_item_title_entries_manage', 'page.php?i=products-manage', '', '', '', '', 2147483646, 1, 1, 4);

-- MENU: sub-menu for view entry
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_view_submenu', '_bx_market_menu_title_view_entry_submenu', 'bx_market_view_submenu', 'bx_market', 8, 0, 1, 'BxMarketMenuView', 'modules/boonex/market/classes/BxMarketMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_view_submenu', 'bx_market', '_bx_market_menu_set_title_view_entry_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_view_submenu', 'bx_market', 'view-product', '_bx_market_menu_item_title_system_view_entry_submenu_entry', '_bx_market_menu_item_title_view_entry_submenu_entry', 'page.php?i=view-product&id={content_id}', '', '', '', '', 2147483647, 1, 0, 1),
('bx_market_view_submenu', 'bx_market', 'view-product-info', '_bx_market_menu_item_title_system_view_entry_submenu_info', '_bx_market_menu_item_title_view_entry_submenu_info', 'page.php?i=view-product-info&id={content_id}', '', '', '', '', 2147483647, 1, 0, 2),
('bx_market_view_submenu', 'bx_market', 'view-product-comments', '_bx_market_menu_item_title_system_view_entry_submenu_comments', '_bx_market_menu_item_title_view_entry_submenu_comments', 'page.php?i=view-product-comments&id={content_id}', '', '', '', '', 2147483647, 1, 0, 3);

-- MENU: profile stats
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_market', 'profile-stats-manage-products', '_bx_market_menu_item_title_system_manage_my_products', '_bx_market_menu_item_title_manage_my_products', 'page.php?i=products-manage', '', '_self', 'shopping-cart col-green3', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:41:"get_menu_addon_manage_tools_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 1);

-- MENU: manage tools submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_menu_manage_tools', '_bx_market_menu_title_manage_tools', 'bx_market_menu_manage_tools', 'bx_market', 6, 0, 1, 'BxMarketMenuManageTools', 'modules/boonex/market/classes/BxMarketMenuManageTools.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_market_menu_manage_tools', 'bx_market', '_bx_market_menu_set_title_manage_tools', 0);

--INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
--('bx_market_menu_manage_tools', 'bx_market', 'delete-with-content', '_bx_market_menu_item_title_system_delete_with_content', '_bx_market_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'trash-o', '', 128, 1, 0, 0);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_market', 'products-administration', '_bx_market_menu_item_title_system_admt_products', '_bx_market_menu_item_title_admt_products', 'page.php?i=products-administration', '', '_self', '', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);

-- MENU: add menu item to profiles modules (trigger* menu sets are processed separately upon modules enable/disable)
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', 'bx_market', 'products-author', '_bx_market_menu_item_title_system_view_entries_author', '_bx_market_menu_item_title_view_entries_author', 'page.php?i=products-author&profile_id={profile_id}', '', '', 'shopping-cart col-green3', '', 2147483647, 1, 0, 0);

-- MENU: Notifications
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'bx_market', 'notifications-licenses', '_bx_market_menu_item_title_system_notifications_licenses', '_bx_market_menu_item_title_notifications_licenses', 'page.php?i=products-licenses', '', '', 'certificate col-green2', 'a:2:{s:6:"module";s:9:"bx_market";s:6:"method";s:23:"get_unused_licenses_num";}', '', 2147483646, 1, 0, @iMIOrder + 1);


-- PRIVACY 
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_market_allow_view_to', 'bx_market', 'view', '_bx_market_form_entry_input_allow_view_to', '3', 'bx_market_products', 'id', 'author', '', ''),
('bx_market_allow_purchase_to', 'bx_market', 'purchase', '_bx_market_form_entry_input_allow_purchase_to', '3', 'bx_market_products', 'id', 'author', '', ''),
('bx_market_allow_comment_to', 'bx_market', 'comment', '_bx_market_form_entry_input_allow_comment_to', 'c', 'bx_market_products', 'id', 'author', 'BxMarketPrivacy', 'modules/boonex/market/classes/BxMarketPrivacy.php'),
('bx_market_allow_vote_to', 'bx_market', 'vote', '_bx_market_form_entry_input_allow_vote_to', 'c', 'bx_market_products', 'id', 'author', 'BxMarketPrivacy', 'modules/boonex/market/classes/BxMarketPrivacy.php');



-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'create entry', NULL, '_bx_market_acl_action_create_entry', '', 1, 3);
SET @iIdActionEntryCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'delete entry', NULL, '_bx_market_acl_action_delete_entry', '', 1, 3);
SET @iIdActionEntryDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'download entry', NULL, '_bx_market_acl_action_download_entry', '', 1, 3);
SET @iIdActionEntryDownload = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'view entry', NULL, '_bx_market_acl_action_view_entry', '', 1, 0);
SET @iIdActionEntryView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'set thumb', NULL, '_bx_market_acl_action_set_thumb', '', 1, 3);
SET @iIdActionSetThumb = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'set cover', NULL, '_bx_market_acl_action_set_cover', '', 1, 3);
SET @iIdActionSetCover = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'edit any entry', NULL, '_bx_market_acl_action_edit_any_entry', '', 1, 3);
SET @iIdActionEntryEditAny = LAST_INSERT_ID();

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
(@iStandard, @iIdActionEntryCreate),
(@iModerator, @iIdActionEntryCreate),
(@iAdministrator, @iIdActionEntryCreate),
(@iPremium, @iIdActionEntryCreate),

-- entry delete
(@iStandard, @iIdActionEntryDelete),
(@iModerator, @iIdActionEntryDelete),
(@iAdministrator, @iIdActionEntryDelete),
(@iPremium, @iIdActionEntryDelete),

-- entry download
(@iStandard, @iIdActionEntryDownload),
(@iModerator, @iIdActionEntryDownload),
(@iAdministrator, @iIdActionEntryDownload),
(@iPremium, @iIdActionEntryDownload),

-- entry view
(@iUnauthenticated, @iIdActionEntryView),
(@iAccount, @iIdActionEntryView),
(@iStandard, @iIdActionEntryView),
(@iUnconfirmed, @iIdActionEntryView),
(@iPending, @iIdActionEntryView),
(@iModerator, @iIdActionEntryView),
(@iAdministrator, @iIdActionEntryView),
(@iPremium, @iIdActionEntryView),

-- set entry thumb
(@iStandard, @iIdActionSetThumb),
(@iModerator, @iIdActionSetThumb),
(@iAdministrator, @iIdActionSetThumb),
(@iPremium, @iIdActionSetThumb),

-- set entry cover
(@iStandard, @iIdActionSetCover),
(@iModerator, @iIdActionSetCover),
(@iAdministrator, @iIdActionSetCover),
(@iPremium, @iIdActionSetCover),

-- edit any entry
(@iModerator, @iIdActionEntryEditAny),
(@iAdministrator, @iIdActionEntryEditAny);


-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('bx_market', '_bx_market', @iSearchOrder + 1, 'BxMarketSearchResult', 'modules/boonex/market/classes/BxMarketSearchResult.php'),
('bx_market_cmts', '_bx_market_cmts', @iSearchOrder + 2, 'BxMarketCmtsSearchResult', 'modules/boonex/market/classes/BxMarketCmtsSearchResult.php');

-- METATAGS
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_market', 'bx_market_meta_keywords', 'bx_market_meta_locations', '', '', '');

-- CATEGORY
INSERT INTO `sys_objects_category` (`object`, `search_object`, `form_object`, `list_name`, `table`, `field`, `join`, `where`, `override_class_name`, `override_class_file`) VALUES
('bx_market_cats', 'bx_market', 'bx_market', 'bx_market_cats', 'bx_market_products', 'cat', 'INNER JOIN `sys_profiles` ON (`sys_profiles`.`id` = `bx_market_products`.`author`)', 'AND `sys_profiles`.`status` = ''active''', '', '');

-- GRIDS: moderation tools
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_market_administration', 'Sql', 'SELECT * FROM `bx_market_products` WHERE 1 ', 'bx_market_products', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 'BxMarketGridAdministration', 'modules/boonex/market/classes/BxMarketGridAdministration.php'),
('bx_market_common', 'Sql', 'SELECT * FROM `bx_market_products` WHERE 1 ', 'bx_market_products', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 'BxMarketGridCommon', 'modules/boonex/market/classes/BxMarketGridCommon.php'),

-- GRIDS: Licenses
('bx_market_licenses', 'Sql', 'SELECT `tl`.`id` AS `id`, `tl`.`profile_id` AS `profile_id`, `tl`.`product_id` AS `product_id`, `tp`.`title` AS `product`, `tl`.`license` AS `license`, `tl`.`type` AS `type`, `tl`.`domain` AS `domain`, `tl`.`added` AS `added`, `tl`.`expired` AS `expired` FROM `bx_market_licenses` AS `tl` LEFT JOIN `bx_market_products` AS `tp` ON `tl`.`product_id`=`tp`.`id` WHERE 1 ', 'bx_market_licenses', 'id', 'added', '', '', 20, NULL, 'start', '', 'tp`.`title,tl`.`license,tl`.`type,tl`.`domain', '', 'like', '', '', 'BxMarketGridLicenses', 'modules/boonex/market/classes/BxMarketGridLicenses.php');


INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_market_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_market_administration', 'switcher', '_bx_market_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_market_administration', 'title', '_bx_market_grid_column_title_adm_title', '25%', 0, '', '', 3),
('bx_market_administration', 'added', '_bx_market_grid_column_title_adm_added', '20%', 1, '25', '', 4),
('bx_market_administration', 'author', '_bx_market_grid_column_title_adm_author', '25%', 0, '25', '', 5),
('bx_market_administration', 'actions', '', '20%', 0, '', '', 6),
('bx_market_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_market_common', 'switcher', '_bx_market_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_market_common', 'title', '_bx_market_grid_column_title_adm_title', '40%', 0, '', '', 3),
('bx_market_common', 'added', '_bx_market_grid_column_title_adm_added', '30%', 1, '25', '', 4),
('bx_market_common', 'actions', '', '20%', 0, '', '', 5),

('bx_market_licenses', 'product', '_bx_market_grid_column_title_lcs_product', '30%', 0, '28', '', 1),
('bx_market_licenses', 'license', '_bx_market_grid_column_title_lcs_license', '10%', 0, '8', '', 3),
('bx_market_licenses', 'type', '_bx_market_grid_column_title_lcs_type', '10%', 1, '12', '', 4),
('bx_market_licenses', 'domain', '_bx_market_grid_column_title_lcs_domain', '20%', 0, '18', '', 5),
('bx_market_licenses', 'added', '_bx_market_grid_column_title_lcs_added', '10%', 1, '25', '', 6),
('bx_market_licenses', 'expired', '_bx_market_grid_column_title_lcs_expired', '10%', 1, '25', '', 7),
('bx_market_licenses', 'actions', '', '10%', 0, '', '', 8);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_market_administration', 'bulk', 'delete', '_bx_market_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_market_administration', 'single', 'edit', '_bx_market_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_market_administration', 'single', 'delete', '_bx_market_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_market_administration', 'single', 'settings', '_bx_market_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_market_common', 'bulk', 'delete', '_bx_market_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_market_common', 'single', 'edit', '_bx_market_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_market_common', 'single', 'delete', '_bx_market_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_market_common', 'single', 'settings', '_bx_market_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),

('bx_market_licenses', 'single', 'reset', '_bx_market_grid_action_title_lcs_reset', 'eraser', 1, 1, 1);


-- UPLOADERS
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_market_simple', 1, 'BxMarketUploaderSimple', 'modules/boonex/market/classes/BxMarketUploaderSimple.php'),
('bx_market_html5', 1, 'BxMarketUploaderHTML5', 'modules/boonex/market/classes/BxMarketUploaderHTML5.php');


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_market', 'BxMarketAlertsResponse', 'modules/boonex/market/classes/BxMarketAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_market_files', 'file_deleted', @iHandler),
('bx_market_files', 'file_downloaded', @iHandler),
('bx_market_photos', 'file_deleted', @iHandler),
('profile', 'delete', @iHandler);
