-- PAGES
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_location' AND `content` LIKE '%entity_location%';
UPDATE `sys_pages_blocks` SET `cell_id`='4', `active`='0' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_actions';
UPDATE `sys_pages_blocks` SET `cell_id`='1' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_text';
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_attachments';
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_social_sharing';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_all_actions'; 
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_location' AND `content` LIKE '%locations_map%';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_view_entry', 4, 'bx_posts', '', '_bx_posts_page_block_title_entry_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:18:\"entity_all_actions\";}', 0, 0, 1, 0),
('bx_posts_view_entry', 3, 'bx_posts', '', '_bx_posts_page_block_title_entry_location', 3, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:13:\"locations_map\";s:6:\"params\";a:2:{i:0;s:8:\"bx_posts\";i:1;s:4:\"{id}\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 0, 1, 0);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_posts_page_block_title_entry_comments', `title`='_bx_posts_page_block_title_entry_comments_link', `designbox_id`='11' WHERE `object`='bx_posts_view_entry_comments' AND `title`='_bx_posts_page_block_title_entry_comments';

DELETE FROM `sys_objects_page` WHERE `object`='bx_posts_updated';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_updated', '_bx_posts_page_title_sys_entries_updated', '_bx_posts_page_title_entries_updated', 'bx_posts', 5, 2147483647, 1, 'posts-updated', 'page.php?i=posts-updated', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_updated';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_updated', 1, 'bx_posts', '_bx_posts_page_block_title_updated_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:14:"browse_updated";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);

UPDATE `sys_pages_blocks` SET `title`='_bx_posts_page_block_title_recent_entries_view_extended', `content`='a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{i:0;s:8:"extended";}}' WHERE `object`='bx_posts_home' AND `title`='_bx_posts_page_block_title_recent_entries';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_home' AND `title`='_bx_posts_page_block_title_cats';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_home', 2, 'bx_posts', '', '_bx_posts_page_block_title_cats', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:15:\"categories_list\";s:6:\"params\";a:2:{i:0;s:13:\"bx_posts_cats\";i:1;b:0;}s:5:\"class\";s:20:\"TemplServiceCategory\";}', 0, 1, 1, 1);


UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='sys_home' AND `title`='_bx_posts_page_block_title_recent_entries';

UPDATE `sys_pages_blocks` SET `title`='_bx_posts_page_block_title_recent_entries', `content`='a:3:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:13:\"browse_public\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:7:\"gallery\";s:13:\"empty_message\";b:1;s:13:\"ajax_paginate\";b:0;}}' WHERE `object`='' AND `title`='_bx_posts_page_block_title_recent_entries_view_extended';


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_homepage' AND `name`='posts-home';
SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_posts', 'posts-home', '_bx_posts_menu_item_title_system_entries_home', '_bx_posts_menu_item_title_entries_home', 'page.php?i=posts-home', '', '', 'file-text col-red3', 'bx_posts_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_posts_view_popup';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_view_popup', '_bx_posts_menu_title_view_entry_popup', '', 'bx_posts', 16, 0, 1, 'BxPostsMenuViewActions', 'modules/boonex/posts/classes/BxPostsMenuViewActions.php');

UPDATE `sys_objects_menu` SET `template_id`='6' WHERE `object`='bx_posts_view_submenu';

UPDATE `sys_menu_items` SET `icon`='file-text col-red3' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='posts-author';


-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object`='bx_posts_cats';
INSERT INTO `sys_objects_category` (`object`, `search_object`, `form_object`, `list_name`, `table`, `field`, `join`, `where`, `override_class_name`, `override_class_file`) VALUES
('bx_posts_cats', 'bx_posts', 'bx_posts', 'bx_posts_cats', 'bx_posts_posts', 'cat', 'INNER JOIN `sys_profiles` ON (`sys_profiles`.`id` = `bx_posts_posts`.`author`)', 'AND `sys_profiles`.`status` = ''active''', '', '');


-- GRIDS
UPDATE `sys_grid_fields` SET `title`='_bx_posts_grid_column_title_adm_active' WHERE `object`='bx_posts_common' AND `name`='switcher';
