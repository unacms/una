
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_posts', '_bx_posts', 'bx_posts@modules/boonex/posts/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_posts', '_bx_posts', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_posts_summary_chars', '700', @iCategId, '_bx_posts_option_summary_chars', 'digit', '', '', '', 1),
('bx_posts_plain_summary_chars', '200', @iCategId, '_bx_posts_option_plain_summary_chars', 'digit', '', '', '', 2),
('bx_posts_per_page_browse', '12', @iCategId, '_bx_posts_option_per_page_browse', 'digit', '', '', '', 10),
('bx_posts_rss_num', '10', @iCategId, '_bx_posts_option_rss_num', 'digit', '', '', '', 20);

-- PAGE: create entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_create_entry', '_bx_posts_page_title_sys_create_entry', '_bx_posts_page_title_create_entry', 'bx_posts', 5, 2147483647, 1, 'create-post', 'page.php?i=create-post', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_posts_create_entry', 1, 'bx_posts', '_bx_posts_page_block_title_create_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"entity_create";}', 0, 1, 1);


-- PAGE: edit entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_edit_entry', '_bx_posts_page_title_sys_edit_entry', '_bx_posts_page_title_edit_entry', 'bx_posts', 5, 2147483647, 1, 'edit-post', '', '', '', '', 0, 1, 0, 'BxPostsPageEntry', 'modules/boonex/posts/classes/BxPostsPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_posts_edit_entry', 1, 'bx_posts', '_bx_posts_page_block_title_edit_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);


-- PAGE: delete entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_delete_entry', '_bx_posts_page_title_sys_delete_entry', '_bx_posts_page_title_delete_entry', 'bx_posts', 5, 2147483647, 1, 'delete-post', '', '', '', '', 0, 1, 0, 'BxPostsPageEntry', 'modules/boonex/posts/classes/BxPostsPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_posts_delete_entry', 1, 'bx_posts', '_bx_posts_page_block_title_delete_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"entity_delete";}', 0, 0, 0);


-- PAGE: view entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_view_entry', '_bx_posts_page_title_sys_view_entry', '_bx_posts_page_title_view_entry', 'bx_posts', 11, 2147483647, 1, 'view-post', '', '', '', '', 0, 1, 0, 'BxPostsPageEntry', 'modules/boonex/posts/classes/BxPostsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_view_entry', 2, 'bx_posts', '_bx_posts_page_block_title_entry_author', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:13:\"entity_author\";}', 0, 0, 0),
('bx_posts_view_entry', 3, 'bx_posts', '_bx_posts_page_block_title_entry_actions', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0),
('bx_posts_view_entry', 1, 'bx_posts', '_bx_posts_page_block_title_entry_text', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 0),
('bx_posts_view_entry', 1, 'bx_posts', '_bx_posts_page_block_title_entry_social_sharing', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 1),
('bx_posts_view_entry', 1, 'bx_posts', '_bx_posts_page_block_title_entry_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 2);


-- PAGE: view entry comments

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_view_entry_comments', '_bx_posts_page_title_sys_view_entry_comments', '_bx_posts_page_title_view_entry_comments', 'bx_posts', 5, 2147483647, 1, 'view-post-comments', '', '', '', '', 0, 1, 0, 'BxPostsPageEntry', 'modules/boonex/posts/classes/BxPostsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_view_entry_comments', 1, 'bx_posts', '_bx_posts_page_block_title_entry_comments', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1);


-- PAGE: featured entries

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_featured', '_bx_posts_page_title_sys_entries_featured', '_bx_posts_page_title_entries_featured', 'bx_posts', 5, 2147483647, 1, 'posts-featured', 'page.php?i=posts-featured', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_featured', 1, 'bx_posts', '_bx_posts_page_block_title_featured_entries', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:15:\"browse_featured\";}', 0, 1, 1);


-- PAGE: entries of author

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_author', 'posts-author', '_bx_posts_page_title_sys_entries_of_author', '_bx_posts_page_title_entries_of_author', 'bx_posts', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxPostsPageAuthor', 'modules/boonex/posts/classes/BxPostsPageAuthor.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_author', 1, 'bx_posts', '_bx_posts_page_block_title_entries_actions', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:18:\"my_entries_actions\";}', 0, 0, 0),
('bx_posts_author', 1, 'bx_posts', '_bx_posts_page_block_title_entries_of_author', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:13:\"browse_author\";}', 0, 0, 1);

-- PAGE: module home

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_home', '_bx_posts_page_title_sys_home', '_bx_posts_page_title_home', 'bx_posts', 5, 2147483647, 1, 'posts-home', 'page.php?i=posts-home', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_home', 1, 'bx_posts', '_bx_posts_page_block_title_recent_entries', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:13:\"browse_public\";}', 0, 1, 0);

-- PAGE: add block to homepage

SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('sys_home', 1, 'bx_posts', '_bx_posts_page_block_title_recent_entries', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:13:\"browse_public\";}', 1, 0, IFNULL(@iBlockOrder, 0) + 1);


-- MENU: add to site menu

SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_posts', 'posts-home', '_bx_posts_menu_item_title_system_entries_home', '_bx_posts_menu_item_title_entries_home', 'page.php?i=posts-home', '', '', 'file-text col-red3', 'bx_posts_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to "add content" menu

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_posts', 'create-post', '_bx_posts_menu_item_title_system_create_entry', '_bx_posts_menu_item_title_create_entry', 'page.php?i=create-post', '', '', 'file-text col-red3', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);


-- MENU: actions menu for view entry 

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_view', '_bx_posts_menu_title_view_entry', 'bx_posts_view', 'bx_posts', 9, 0, 1, 'BxPostsMenuView', 'modules/boonex/posts/classes/BxPostsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_posts_view', 'bx_posts', '_bx_posts_menu_set_title_view_entry', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_posts_view', 'bx_posts', 'edit-post', '_bx_posts_menu_item_title_system_edit_entry', '_bx_posts_menu_item_title_edit_entry', 'page.php?i=edit-post&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 1),
('bx_posts_view', 'bx_posts', 'delete-post', '_bx_posts_menu_item_title_system_delete_entry', '_bx_posts_menu_item_title_delete_entry', 'page.php?i=delete-post&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 2);


-- MENU: actions menu for my entries

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_my', '_bx_posts_menu_title_entries_my', 'bx_posts_my', 'bx_posts', 9, 0, 1, 'BxPostsMenu', 'modules/boonex/posts/classes/BxPostsMenu.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_posts_my', 'bx_posts', '_bx_posts_menu_set_title_entries_my', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_posts_my', 'bx_posts', 'create-post', '_bx_posts_menu_item_title_system_create_entry', '_bx_posts_menu_item_title_create_entry', 'page.php?i=create-post', '', '', 'plus', '', 2147483647, 1, 0, 0);


-- MENU: module sub-menu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_submenu', '_bx_posts_menu_title_submenu', 'bx_posts_submenu', 'bx_posts', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_posts_submenu', 'bx_posts', '_bx_posts_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_posts_submenu', 'bx_posts', 'posts-home', '_bx_posts_menu_item_title_system_entries_public', '_bx_posts_menu_item_title_entries_public', 'page.php?i=posts-home', '', '', '', '', 2147483647, 1, 1, 1),
('bx_posts_submenu', 'bx_posts', 'posts-featured', '_bx_posts_menu_item_title_system_entries_featured', '_bx_posts_menu_item_title_entries_featured', 'page.php?i=posts-featured', '', '', '', '', 2147483647, 1, 1, 2);


-- MENU: sub-menu for view entry

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_view_submenu', '_bx_posts_menu_title_view_entry_submenu', 'bx_posts_view_submenu', 'bx_posts', 8, 0, 1, 'BxPostsMenuView', 'modules/boonex/posts/classes/BxPostsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_posts_view_submenu', 'bx_posts', '_bx_posts_menu_set_title_view_entry_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_posts_view_submenu', 'bx_posts', 'view-post', '_bx_posts_menu_item_title_system_view_entry', '_bx_posts_menu_item_title_view_entry_submenu_entry', 'page.php?i=view-post&id={content_id}', '', '', '', '', 2147483647, 1, 0, 1),
('bx_posts_view_submenu', 'bx_posts', 'view-post-comments', '_bx_posts_menu_item_title_system_view_entry_comments', '_bx_posts_menu_item_title_view_entry_submenu_comments', 'page.php?i=view-post-comments&id={content_id}', '', '', '', '', 2147483647, 1, 0, 2);


-- MENU: add menu item to profiles modules (trigger* menu sets are processed separately upon modules enable/disable)

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', 'bx_posts', 'posts-author', '_bx_posts_menu_item_title_system_view_entries_author', '_bx_posts_menu_item_title_view_entries_author', 'page.php?i=posts-author&profile_id={profile_id}', '', '', '', '', 2147483647, 1, 0, 0);


-- PRIVACY 

INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_posts_allow_view_to', 'bx_posts', 'view', '_bx_posts_form_entry_input_allow_view_to', '3', 'bx_posts_posts', 'id', 'author', '', '');


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_posts', 'create entry', NULL, '_bx_posts_acl_action_create_entry', '', 1, 3);
SET @iIdActionEntryCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_posts', 'delete entry', NULL, '_bx_posts_acl_action_delete_entry', '', 1, 3);
SET @iIdActionEntryDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_posts', 'view entry', NULL, '_bx_posts_acl_action_view_entry', '', 1, 0);
SET @iIdActionEntryView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_posts', 'set thumb', NULL, '_bx_posts_acl_action_set_thumb', '', 1, 3);
SET @iIdActionSetThumb = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_posts', 'edit any entry', NULL, '_bx_posts_acl_action_edit_any_entry', '', 1, 3);
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

-- edit any entry
(@iModerator, @iIdActionEntryEditAny),
(@iAdministrator, @iIdActionEntryEditAny);


-- SEARCH
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES
('bx_posts', '_bx_posts', 'BxPostsSearchResult', 'modules/boonex/posts/classes/BxPostsSearchResult.php'),
('bx_posts_cmts', '_bx_posts_cmts', 'BxPostsCmtsSearchResult', 'modules/boonex/posts/classes/BxPostsCmtsSearchResult.php');

-- METATAGS
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_posts', 'bx_posts_meta_keywords', 'bx_posts_meta_locations', '', '', '');

