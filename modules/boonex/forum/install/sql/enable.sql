SET @sName = 'bx_forum';


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_forum', 'bx_forum@modules/boonex/forum/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_forum', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_forum_summary_chars', '700', @iCategId, '_bx_forum_option_summary_chars', 'digit', '', '', '', 1),
('bx_forum_plain_summary_chars', '240', @iCategId, '_bx_forum_option_plain_summary_chars', 'digit', '', '', '', 2),
('bx_forum_per_page_browse', '12', @iCategId, '_bx_forum_option_per_page_browse', 'digit', '', '', '', 10),
('bx_forum_per_page_preview', '3', @iCategId, '_bx_forum_option_per_page_preview', 'digit', '', '', '', 11),
('bx_forum_rss_num', '10', @iCategId, '_bx_forum_option_rss_num', 'digit', '', '', '', 20);


-- PAGE: create entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_create_entry', '_bx_forum_page_title_sys_create_entry', '_bx_forum_page_title_create_entry', @sName, 5, 2147483647, 1, 'create-discussion', 'page.php?i=create-discussion', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_forum_create_entry', 1, @sName, '_bx_forum_page_block_title_create_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"entity_create";}', 0, 1, 1);

-- PAGE: edit entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_edit_entry', '_bx_forum_page_title_sys_edit_entry', '_bx_forum_page_title_edit_entry', @sName, 5, 2147483647, 1, 'edit-discussion', '', '', '', '', 0, 1, 0, 'BxForumPageEntry', 'modules/boonex/forum/classes/BxForumPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_forum_edit_entry', 1, @sName, '_bx_forum_page_block_title_edit_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);

-- PAGE: delete entry
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_delete_entry', '_bx_forum_page_title_sys_delete_entry', '_bx_forum_page_title_delete_entry', @sName, 5, 2147483647, 1, 'delete-discussion', '', '', '', '', 0, 1, 0, 'BxForumPageEntry', 'modules/boonex/forum/classes/BxForumPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_forum_delete_entry', 1, @sName, '_bx_forum_page_block_title_delete_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"entity_delete";}', 0, 0, 0);

-- PAGE: view entry
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_view_entry', 'view-discussion', '_bx_forum_page_title_sys_view_entry', '_bx_forum_page_title_view_entry', @sName, 10, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxForumPageEntry', 'modules/boonex/forum/classes/BxForumPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_view_entry', 1, @sName, '_bx_forum_page_block_title_entry_breadcrumb', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:17:"entity_breadcrumb";}', 0, 0, 1, 0),
('bx_forum_view_entry', 2, @sName, '_bx_forum_page_block_title_entry_author', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"entity_author";}', 0, 0, 1, 0),
('bx_forum_view_entry', 3, @sName, '_bx_forum_page_block_title_entry_participants', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:19:"entity_participants";}', 0, 0, 1, 0),
('bx_forum_view_entry', 4, @sName, '_bx_forum_page_block_title_entry_text', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:17:"entity_text_block";}', 0, 0, 1, 0),
('bx_forum_view_entry', 4, @sName, '_bx_forum_page_block_title_entry_attachments', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:18:"entity_attachments";}', 0, 0, 1, 1),
('bx_forum_view_entry', 4, @sName, '_bx_forum_page_block_title_entry_comments', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:15:"entity_comments";}', 0, 0, 1, 2);

-- PAGE: new entries
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_new', '_bx_forum_page_title_sys_entries_new', '_bx_forum_page_title_entries_new', @sName, 5, 2147483647, 1, 'discussions-new', 'page.php?i=discussions-new', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_new', 1, @sName, '_bx_forum_page_block_title_new_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:10:"browse_new";s:6:"params";a:1:{i:0;s:5:"table";}}', 0, 1, 1);

-- PAGE: top entries
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_top', '_bx_forum_page_title_sys_entries_top', '_bx_forum_page_title_entries_top', @sName, 5, 2147483647, 1, 'discussions-top', 'page.php?i=discussions-top', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_top', 1, @sName, '_bx_forum_page_block_title_top_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:10:"browse_top";s:6:"params";a:1:{i:0;s:5:"table";}}', 0, 1, 1);

-- PAGE: popular entries
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_popular', '_bx_forum_page_title_sys_entries_popular', '_bx_forum_page_title_entries_popular', @sName, 5, 2147483647, 1, 'discussions-popular', 'page.php?i=discussions-popular', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_popular', 1, @sName, '_bx_forum_page_block_title_popular_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:5:"table";}}', 0, 1, 1);

-- PAGE: recently updated entries
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_updated', '_bx_forum_page_title_sys_entries_updated', '_bx_forum_page_title_entries_updated', @sName, 5, 2147483647, 1, 'discussions-updated', 'page.php?i=discussions-updated', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_updated', 1, @sName, '_bx_forum_page_block_title_updated_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:14:"browse_updated";s:6:"params";a:1:{i:0;s:5:"table";}}', 0, 1, 1);

-- PAGE: categories
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_categories', 'discussions-categories', '_bx_forum_page_title_sys_entries_categories', '_bx_forum_page_title_entries_categories', @sName, 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_categories', 1, @sName, '', '_bx_forum_page_block_title_entries_categories', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:17:"browse_categories";}', 0, 0, 1, 0);

-- PAGE: entries by category
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_category', 'discussions-category', '_bx_forum_page_title_sys_entries_by_category', '_bx_forum_page_title_entries_by_category', @sName, 5, 2147483647, 1, 'page.php?i=discussions-category', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_category', 1, @sName, '_bx_forum_page_block_title_sys_entries_by_category', '_bx_forum_page_block_title_entries_by_category', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:15:"browse_category";s:6:"params";a:1:{i:0;s:5:"table";}}', 0, 0, 1, 1);


-- PAGE: entries of author
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_author', 'discussions-author', '_bx_forum_page_title_sys_entries_of_author', '_bx_forum_page_title_entries_of_author', @sName, 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxForumPageAuthor', 'modules/boonex/forum/classes/BxForumPageAuthor.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_author', 1, @sName, '', '_bx_forum_page_block_title_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:18:"my_entries_actions";}', 0, 0, 1, 0),
('bx_forum_author', 1, @sName, '_bx_forum_page_block_title_sys_entries_of_author', '_bx_forum_page_block_title_entries_of_author', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_author";}', 0, 0, 1, 1);


-- PAGE: module home
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_home', 'discussions-home', '_bx_forum_page_title_sys_home', '_bx_forum_page_title_home', @sName, 2, 2147483647, 1, 'page.php?i=discussions-home', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_home', 1, @sName, '', '_bx_forum_page_block_title_latest_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_latest";s:6:"params";a:1:{i:0;s:5:"table";}}', 0, 1, 1, 0),
('bx_forum_home', 2, @sName, '', '_bx_forum_page_block_title_cats', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:17:"browse_categories";}', 0, 1, 1, 0),
('bx_forum_home', 2, @sName, '', '_bx_forum_page_block_title_popular_keywords', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"keywords_cloud";s:6:"params";a:2:{i:0;s:8:"bx_forum";i:1;s:8:"bx_forum";}s:5:"class";s:20:"TemplServiceMetatags";}', 0, 1, 1, 1);

-- PAGE: module manage own
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_manage', '_bx_forum_page_title_sys_manage', '_bx_forum_page_title_manage', @sName, 5, 2147483647, 1, 'discussions-manage', 'page.php?i=discussions-manage', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_manage', 1, @sName, '_bx_forum_page_block_title_system_manage', '_bx_forum_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:12:"manage_tools";}}', 0, 1, 0);

-- PAGE: module manage all
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_administration', '_bx_forum_page_title_sys_manage_administration', '_bx_forum_page_title_manage', @sName, 5, 192, 1, 'discussions-administration', 'page.php?i=discussions-administration', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_administration', 1, @sName, '_bx_forum_page_block_title_system_manage_administration', '_bx_forum_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:12:"manage_tools";s:6:"params";a:1:{i:0;s:14:"administration";}}', 0, 1, 0);

-- PAGE: add block to homepage
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('sys_home', 1, @sName, '_bx_forum_page_block_title_latest_entries', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_public";}', 1, 0, 0, IFNULL(@iBlockOrder, 0) + 1);

-- PAGES: add page block to profiles modules (trigger* page objects are processed separately upon modules enable/disable)
SET @iPBCellProfile = 3;
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, @sName, '_bx_forum_page_block_title_my_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_author";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 0);

-- PAGE: service blocks
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('', 0, @sName, '_bx_forum_page_block_title_latest_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_latest";s:6:"params";a:3:{i:0;s:7:"gallery";i:1;b:1;i:2;b:0;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 1),
('', 0, @sName, '_bx_forum_page_block_title_latest_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_latest";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 2),
('', 0, @sName, '_bx_forum_page_block_title_new_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:10:"browse_new";s:6:"params";a:3:{i:0;s:7:"gallery";i:1;b:1;i:2;b:0;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 3),
('', 0, @sName, '_bx_forum_page_block_title_new_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:10:"browse_new";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 4),
('', 0, @sName, '_bx_forum_page_block_title_top_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:10:"browse_top";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 5),
('', 0, @sName, '_bx_forum_page_block_title_top_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:10:"browse_top";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 6),
('', 0, @sName, '_bx_forum_page_block_title_popular_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 7),
('', 0, @sName, '_bx_forum_page_block_title_popular_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 8);


-- MENU: add to site menu
SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', @sName, 'discussions-home', '_bx_forum_menu_item_title_system_entries_home', '_bx_forum_menu_item_title_entries_home', 'page.php?i=discussions-home', '', '', 'comments-o col-blue2', 'bx_forum_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu
SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', @sName, 'discussions-home', '_bx_forum_menu_item_title_system_entries_home', '_bx_forum_menu_item_title_entries_home', 'page.php?i=discussions-home', '', '', 'comments-o col-blue2', 'bx_forum_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

-- MENU: add to "add content" menu
SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', @sName, 'create-discussion', '_bx_forum_menu_item_title_system_create_entry', '_bx_forum_menu_item_title_create_entry', 'page.php?i=create-discussion', '', '', 'comments-o col-blue2', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

-- MENU: actions menu for view entry 
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_view', '_bx_forum_menu_title_view_entry', 'bx_forum_view', @sName, 9, 0, 1, 'BxForumMenuView', 'modules/boonex/forum/classes/BxForumMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_view', @sName, '_bx_forum_menu_set_title_view_entry', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_forum_view', @sName, 'stick-discussion', '_bx_forum_menu_item_title_system_stick_entry', '_bx_forum_menu_item_title_stick_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'stick\', {content_id});', '', 'thumb-tack', '', 2147483647, 1, 0, 1),
('bx_forum_view', @sName, 'unstick-discussion', '_bx_forum_menu_item_title_system_unstick_entry', '_bx_forum_menu_item_title_unstick_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'unstick\', {content_id});', '', 'thumb-tack', '', 2147483647, 1, 0, 2),
('bx_forum_view', @sName, 'lock-discussion', '_bx_forum_menu_item_title_system_lock_entry', '_bx_forum_menu_item_title_lock_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'lock\', {content_id});', '', 'lock', '', 2147483647, 1, 0, 3),
('bx_forum_view', @sName, 'unlock-discussion', '_bx_forum_menu_item_title_system_unlock_entry', '_bx_forum_menu_item_title_unlock_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'unlock\', {content_id});', '', 'unlock', '', 2147483647, 1, 0, 4),
('bx_forum_view', @sName, 'hide-discussion', '_bx_forum_menu_item_title_system_hide_entry', '_bx_forum_menu_item_title_hide_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'hide\', {content_id});', '', 'eye-slash', '', 2147483647, 1, 0, 5),
('bx_forum_view', @sName, 'unhide-discussion', '_bx_forum_menu_item_title_system_unhide_entry', '_bx_forum_menu_item_title_unhide_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'unhide\', {content_id});', '', 'eye', '', 2147483647, 1, 0, 6),
('bx_forum_view', @sName, 'edit-discussion', '_bx_forum_menu_item_title_system_edit_entry', '_bx_forum_menu_item_title_edit_entry', 'page.php?i=edit-discussion&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 7),
('bx_forum_view', @sName, 'delete-discussion', '_bx_forum_menu_item_title_system_delete_entry', '_bx_forum_menu_item_title_delete_entry', 'page.php?i=delete-discussion&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 8);

-- MENU: actions menu for my entries
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_my', '_bx_forum_menu_title_entries_my', 'bx_forum_my', @sName, 9, 0, 1, 'BxForumMenu', 'modules/boonex/forum/classes/BxForumMenu.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_my', 'bx_forum', '_bx_forum_menu_set_title_entries_my', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_forum_my', 'bx_forum', 'create-discussion', '_bx_forum_menu_item_title_system_create_entry', '_bx_forum_menu_item_title_create_entry', 'page.php?i=create-discussion', '', '', 'plus', '', 2147483647, 1, 0, 0);

-- MENU: module sub-menu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_submenu', '_bx_forum_menu_title_submenu', 'bx_forum_submenu', @sName, 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_submenu', @sName, '_bx_forum_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_forum_submenu', @sName, 'discussions-home', '_bx_forum_menu_item_title_system_entries_public', '_bx_forum_menu_item_title_entries_public', 'page.php?i=discussions-home', '', '', '', '', 2147483647, 1, 1, 1),
('bx_forum_submenu', @sName, 'discussions-new', '_bx_forum_menu_item_title_system_entries_new', '_bx_forum_menu_item_title_entries_new', 'page.php?i=discussions-new', '', '', '', '', 2147483647, 1, 1, 2),
('bx_forum_submenu', @sName, 'discussions-top', '_bx_forum_menu_item_title_system_entries_top', '_bx_forum_menu_item_title_entries_top', 'page.php?i=discussions-top', '', '', '', '', 2147483647, 1, 1, 3),
('bx_forum_submenu', @sName, 'discussions-categories', '_bx_forum_menu_item_title_system_entries_categories', '_bx_forum_menu_item_title_entries_categories', 'page.php?i=discussions-categories', '', '', '', '', 2147483647, 1, 1, 4),
('bx_forum_submenu', @sName, 'discussions-manage', '_bx_forum_menu_item_title_system_entries_manage', '_bx_forum_menu_item_title_entries_manage', 'page.php?i=discussions-manage', '', '', '', '', 2147483646, 1, 1, 5);

-- MENU: manage tools submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_menu_manage_tools', '_bx_forum_menu_title_manage_tools', 'bx_forum_menu_manage_tools', 'bx_forum', 6, 0, 1, 'BxForumMenuManageTools', 'modules/boonex/forum/classes/BxForumMenuManageTools.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_menu_manage_tools', 'bx_forum', '_bx_forum_menu_set_title_manage_tools', 0);

--INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
--('bx_forum_menu_manage_tools', 'bx_forum', 'delete-with-content', '_bx_forum_menu_item_title_system_delete_with_content', '_bx_forum_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'trash-o', '', 128, 1, 0, 0);

-- MENU: notifications
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', @sName, 'notifications-forum', '_bx_forum_menu_item_title_system_forum', '_bx_forum_menu_item_title_forum', 'page.php?i=discussions-author&profile_id={member_id}', '', '', 'comments-o col-blue2', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:29:"get_unreplied_discussions_num";}', '', 2147483646, 1, 0, @iMIOrder + 1);

-- MENU: profile stats
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', @sName, 'profile-stats-unreplied-discussions', '_bx_forum_menu_item_title_system_unreplied_discussions', '_bx_forum_menu_item_title_unreplied_discussions', 'page.php?i=discussions-author&profile_id={member_id}', '', '', 'comments-o col-blue2', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:29:"get_unreplied_discussions_num";}', '', 2147483646, 1, 0, 2);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', @sName, 'discussions-administration', '_bx_forum_menu_item_title_system_admt_discussions', '_bx_forum_menu_item_title_admt_discussions', 'page.php?i=discussions-administration', '', '_self', '', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);

-- MENU: add menu item to profiles modules (trigger* menu sets are processed separately upon modules enable/disable)
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', @sName, 'discussions-author', '_bx_forum_menu_item_title_system_view_entries_author', '_bx_forum_menu_item_title_view_entries_author', 'page.php?i=discussions-author&profile_id={profile_id}', '', '', 'comments-o col-blue2', '', 2147483647, 1, 0, 0);


-- GRIDS: main
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
(@sName, 'Sql', 'SELECT `bx_forum_discussions`.*, `bx_forum_cmts`.`cmt_text` FROM `bx_forum_discussions` LEFT JOIN `bx_forum_cmts` ON (`bx_forum_cmts`.`cmt_id` = `bx_forum_discussions`.`lr_comment_id`) %s WHERE 1 %s', 'bx_forum_discussions', 'id', 'lr_timestamp', '', 10, NULL, 'start', '', 'text', 'auto', '', 2147483647, 'BxForumGrid', 'modules/boonex/forum/classes/BxForumGrid.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
(@sName, 'author', '', '10%', '', 1),
(@sName, 'lr_timestamp', '', '85%', '', 2),
(@sName, 'comments', '', '5%', '', 3);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
(@sName, 'independent', 'add', '_bx_forum_grid_action_title_add', '', 0, 1);

-- GRIDS: moderation tools
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_administration', 'Sql', 'SELECT * FROM `bx_forum_discussions` WHERE 1 ', 'bx_forum_discussions', 'id', 'added', 'status_admin', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 192, 'BxForumGridAdministration', 'modules/boonex/forum/classes/BxForumGridAdministration.php'),
('bx_forum_common', 'Sql', 'SELECT * FROM `bx_forum_discussions` WHERE 1 ', 'bx_forum_discussions', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 2147483647, 'BxForumGridCommon', 'modules/boonex/forum/classes/BxForumGridCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_forum_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_forum_administration', 'switcher', '_bx_forum_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_forum_administration', 'title', '_bx_forum_grid_column_title_adm_title', '25%', 0, '', '', 3),
('bx_forum_administration', 'added', '_bx_forum_grid_column_title_adm_added', '20%', 1, '25', '', 4),
('bx_forum_administration', 'author', '_bx_forum_grid_column_title_adm_author', '25%', 0, '25', '', 5),
('bx_forum_administration', 'actions', '', '20%', 0, '', '', 6),
('bx_forum_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_forum_common', 'switcher', '_bx_forum_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_forum_common', 'title', '_bx_forum_grid_column_title_adm_title', '40%', 0, '', '', 3),
('bx_forum_common', 'added', '_bx_forum_grid_column_title_adm_added', '30%', 1, '25', '', 4),
('bx_forum_common', 'actions', '', '20%', 0, '', '', 5);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_forum_administration', 'bulk', 'delete', '_bx_forum_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_forum_administration', 'single', 'edit', '_bx_forum_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_forum_administration', 'single', 'delete', '_bx_forum_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_forum_administration', 'single', 'settings', '_bx_forum_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_forum_common', 'bulk', 'delete', '_bx_forum_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_forum_common', 'single', 'edit', '_bx_forum_grid_action_title_adm_edit', 'pencil', 1, 0, 1),
('bx_forum_common', 'single', 'delete', '_bx_forum_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_forum_common', 'single', 'settings', '_bx_forum_grid_action_title_adm_more_actions', 'cog', 1, 0, 3);

-- GRIDS: categories manager
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_categories', 'Array', '', '', 'category', '', '', 10, NULL, 'start', '', 'title', 'auto', '', 192, 'BxForumGridCategories', 'modules/boonex/forum/classes/BxForumGridCategories.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_forum_categories', 'title', '_bx_forum_grid_column_title_title', '60%', '', 1),
('bx_forum_categories', 'visible_for_levels', '_bx_forum_grid_column_title_visible_for_levels', '40%', '', 2);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_forum_categories', 'single', 'show_to', '_bx_forum_grid_action_title_show_to', '', 0, 1);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'create entry', NULL, '_bx_forum_acl_action_create_entry', '', 1, 3);
SET @iIdActionEntryCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'delete entry', NULL, '_bx_forum_acl_action_delete_entry', '', 1, 3);
SET @iIdActionEntryDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'view entry', NULL, '_bx_forum_acl_action_view_entry', '', 1, 0);
SET @iIdActionEntryView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'edit any entry', NULL, '_bx_forum_acl_action_edit_any_entry', '', 1, 3);
SET @iIdActionEntryEditAny = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'stick any entry', NULL, '_bx_forum_acl_action_stick_any_entry', '', 1, 3);
SET @iIdActionEntryStickAny = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'lock any entry', NULL, '_bx_forum_acl_action_lock_any_entry', '', 1, 3);
SET @iIdActionEntryLockAny = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'hide any entry', NULL, '_bx_forum_acl_action_hide_any_entry', '', 1, 3);
SET @iIdActionEntryHideAny = LAST_INSERT_ID();

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

-- entry view
(@iUnauthenticated, @iIdActionEntryView),
(@iAccount, @iIdActionEntryView),
(@iStandard, @iIdActionEntryView),
(@iUnconfirmed, @iIdActionEntryView),
(@iPending, @iIdActionEntryView),
(@iModerator, @iIdActionEntryView),
(@iAdministrator, @iIdActionEntryView),
(@iPremium, @iIdActionEntryView),

-- edit any entry
(@iModerator, @iIdActionEntryEditAny),
(@iAdministrator, @iIdActionEntryEditAny),

-- stick any entry
(@iModerator, @iIdActionEntryStickAny),
(@iAdministrator, @iIdActionEntryStickAny),

-- lock any entry
(@iModerator, @iIdActionEntryLockAny),
(@iAdministrator, @iIdActionEntryLockAny),

-- hide any entry
(@iModerator, @iIdActionEntryHideAny),
(@iAdministrator, @iIdActionEntryHideAny);


-- PRIVACY 
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_allow_view_to', @sName, 'view', '_bx_forum_form_entry_input_allow_view_to', '3', 'bx_forum_discussions', 'id', 'author', '', '');


-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
(@sName, '_bx_forum', @iSearchOrder + 1, 'BxForumSearchResult', 'modules/boonex/forum/classes/BxForumSearchResult.php'),
('bx_forum_cmts', '_bx_forum_cmts', @iSearchOrder + 2, 'BxForumCmtsSearchResult', 'modules/boonex/forum/classes/BxForumCmtsSearchResult.php');


-- METATAGS
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
(@sName, 'bx_forum_meta_keywords', '', '', '', '');


-- CATEGORY
INSERT INTO `sys_objects_category` (`object`, `search_object`, `form_object`, `list_name`, `table`, `field`, `join`, `where`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_cats', @sName, @sName, 'bx_forum_cats', 'bx_forum_discussions', 'cat', 'INNER JOIN `sys_profiles` ON (`sys_profiles`.`id` = `bx_forum_discussions`.`author`)', 'AND `sys_profiles`.`status` = ''active''', '', '');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(@sName, 'bx_forum_cmts', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 0, 0, 1, -3, 1, 'cmt', 'page.php?i=view-discussion&id={object_id}', '', 'bx_forum_discussions', 'id', 'author', '', 'comments', 'BxForumCmts', 'modules/boonex/forum/classes/BxForumCmts.php');


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
(@sName, 'bx_forum_views_track', '86400', '1', 'bx_forum_discussions', 'id', 'views', '', '');


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxForumAlertsResponse', 'modules/boonex/forum/classes/BxForumAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
(@sName, 'commentPost', @iHandler),
(@sName, 'commentRemoved', @iHandler),
('profile', 'delete', @iHandler);


-- EMAIL TEMPLATES
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
(@sName, '_bx_forum_email_new_reply', 'bx_forum_new_reply', '_bx_forum_email_new_reply_subject', '_bx_forum_email_new_reply_body');
