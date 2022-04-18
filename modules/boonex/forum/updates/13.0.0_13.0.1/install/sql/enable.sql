SET @sName = 'bx_forum';


-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='1' WHERE `object`='bx_forum_category';

UPDATE `sys_pages_blocks` SET `cell_id`='2' WHERE `object`='bx_forum_category' AND `title`='_bx_forum_page_block_title_entries_by_category';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_category' AND `title`='_bx_forum_page_block_title_cats';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_category', 1, @sName, '', '_bx_forum_page_block_title_cats', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:15:\"categories_list\";}', 0, 0, 1, 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_context' AND `title`='_bx_forum_page_block_title_multi_categories_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_context', 1, @sName, '_bx_forum_page_block_title_sys_multi_categories_in_context', '_bx_forum_page_block_title_multi_categories_in_context', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:29:\"categories_multi_list_context\";}', 0, 0, 0, 2);

UPDATE `sys_objects_page` SET `cover`='1', `cover_image`='0', `type_id`='1', `layout_id`='6', `submenu`='', `inj_head`='', `inj_footer`='', `sticky_columns`='0' WHERE `object`='bx_forum_home';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_home' AND `title` IN ('_bx_forum_page_block_title_featured_entries_view_showcase', '_bx_forum_page_block_title_popular_entries', '_bx_forum_page_block_title_home', '_bx_forum_page_block_title_featured_entries', '_bx_forum_page_block_title_latest_entries');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `async`, `cache_lifetime`, `submenu`, `tabs`, `hidden_on`, `visible_for_levels`, `type`, `content`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_home', 1, @sName, '_bx_forum_page_block_title_sys_featured_entries_view_showcase', '_bx_forum_page_block_title_featured_entries_view_showcase', 0, 0, NULL, '', 0, '', 2147483647, 'service', 'a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:15:\"browse_featured\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', NULL, 0, 1, 1, 0),
('bx_forum_home', 2, @sName, '_bx_forum_page_block_title_popular_entries_view_extended', '_bx_forum_page_block_title_popular_entries', 11, 0, NULL, '', 0, '', 2147483647, 'service', 'a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:14:\"browse_popular\";s:6:\"params\";a:1:{i:0;s:8:\"extended\";}}', NULL, 0, 1, 1, 3),
('bx_forum_home', 3, @sName, '', '_bx_forum_page_block_title_home', 11, 0, NULL, '', 0, '', 2147483647, 'service', 'a:3:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:13:\"browse_latest\";s:6:\"params\";a:3:{i:0;s:5:\"table\";i:1;b:1;i:2;b:0;}}', NULL, 0, 1, 1, 1);

UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:15:\"categories_list\";}' WHERE `object`='bx_forum_home' AND `title`='_bx_forum_page_block_title_cats';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view' AND `name`='approve';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_forum_view', @sName, 'approve', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this, ''{module_uri}'', {content_id});', '', 'check', '', '', '', 0, 2147483647, '', 1, 0, 5);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_entry_attachments' AND `name`='add-link';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_forum_entry_attachments', @sName, 'add-link', '_bx_forum_menu_item_title_system_add_link', '_bx_forum_menu_item_title_add_link', 'javascript:void(0)', 'javascript:{js_object_link}.showAttachLink(this);', '_self', 'link', '', '', 2147483647, '', 1, 0, 1, 10);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view' AND `name`='resolve-discussion';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_forum_view', @sName, 'resolve-discussion', '_bx_forum_menu_item_title_system_resolve_entry', '_bx_forum_menu_item_title_resolve_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'resolve\', {content_id});', '', 'check-circle', '', 0, 2147483647, 1, 0, 1);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_more' AND `name`='unresolve-discussion';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_forum_view_more', @sName, 'unresolve-discussion', '_bx_forum_menu_item_title_system_unresolve_entry', '_bx_forum_menu_item_title_unresolve_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'unresolve\', {content_id});', '', 'check-circle', '', 2147483647, 1, 0, 1);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_actions' AND `name` IN ('resolve-discussion', 'unresolve-discussion', 'social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_forum_view_actions', @sName, 'resolve-discussion', '_bx_forum_menu_item_title_system_resolve_entry', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 20),
('bx_forum_view_actions', @sName, 'unresolve-discussion', '_bx_forum_menu_item_title_system_unresolve_entry', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 25),
('bx_forum_view_actions', @sName, 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_submenu' AND `name` IN ('discussions-new', 'discussions-top', 'discussions-categories', 'discussions-partaken');

UPDATE `sys_menu_items` SET `icon`='clock' WHERE `set_name`='bx_forum_snippet_meta_main' AND `name`='date';
UPDATE `sys_menu_items` SET `hidden_on_col`='3' WHERE `set_name`='bx_forum_snippet_meta_main' AND `name`='category';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_snippet_meta_main' AND `name` IN ('comments', 'status', 'badges');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`, `hidden_on_col`) VALUES 
('bx_forum_snippet_meta_main', 'bx_forum', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', 'comments', '', 2147483647, 1, 0, 1, 4, 0),
('bx_forum_snippet_meta_main', 'bx_forum', 'status', '_bx_forum_menu_item_title_system_sm_status', '_bx_forum_menu_item_title_sm_status', '', '', '', '', '', 2147483647, 1, 0, 1, 5, 3),
('bx_forum_snippet_meta_main', 'bx_forum', 'badges', '_bx_forum_menu_item_title_system_sm_badges', '_bx_forum_menu_item_title_sm_badges', '', '', '', '', '', 2147483647, 1, 0, 1, 6, 3);

UPDATE `sys_menu_items` SET `name`='profile-stats-my-forum', `link`='page.php?i=discussions-author&profile_id={member_id}' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-discussions';


-- GRIDS
UPDATE `sys_grid_fields` SET `width`='75%' WHERE `object`=@sName AND `name`='text';

DELETE FROM `sys_grid_fields` WHERE `object`=@sName AND `name` IN ('category', 'participants', 'rating');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
(@sName, 'category', '', '10%', '', 1),
(@sName, 'participants', '', '10%', '', 3),
(@sName, 'rating', '', '5%', '', 4);

DELETE FROM `sys_grid_actions` WHERE `object`=@sName AND `type`='independent' AND `name`='add';

UPDATE `sys_grid_fields` SET `width`='50%' WHERE `object`='bx_forum_categories' AND `name`='title';
UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_forum_categories' AND `name`='visible_for_levels';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_categories' AND `name` IN ('icon', 'actions');
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_forum_categories', 'icon', '_bx_forum_grid_column_title_icon', '10%', '', 1),
('bx_forum_categories', 'actions', '', '20%', '', 4);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_forum_categories' AND `type`='single' AND `name`='edit';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_forum_categories', 'single', 'edit', '_bx_forum_grid_action_title_adm_edit', 'pencil-alt', 0, 2);


-- ACL
SET @iIdActionEntryResolveAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='resolve any entry' LIMIT 1);

DELETE FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='resolve any entry';
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEntryResolveAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'resolve any entry', NULL, '_bx_forum_acl_action_resolve_any_entry', '', 1, 3);
SET @iIdActionEntryResolveAny = LAST_INSERT_ID();

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
(@iModerator, @iIdActionEntryResolveAny),
(@iAdministrator, @iIdActionEntryResolveAny);


-- CONNECTIONS
UPDATE `sys_objects_connection` SET `profile_initiator`='1', `profile_content`='0' WHERE `object`='bx_forum_subscribers';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`=@sName WHERE `object`=@sName;


-- CATEGORY
UPDATE `sys_objects_category` SET `module`=@sName WHERE `object`='bx_forum_cats';
