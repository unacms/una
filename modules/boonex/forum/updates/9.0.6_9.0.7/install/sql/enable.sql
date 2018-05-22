SET @sName = 'bx_forum';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_view_entry', 2, @sName, '_bx_forum_page_block_title_sys_entry_context', '_bx_forum_page_block_title_entry_context', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:14:\"entity_context\";}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_forum_context';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_context', 'discussions-context', '_bx_forum_page_title_sys_entries_in_context', '_bx_forum_page_title_entries_in_context', 'bx_forum', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxForumPageAuthor', 'modules/boonex/forum/classes/BxForumPageAuthor.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_context', 1, @sName, '_bx_forum_page_block_title_sys_entries_in_context', '_bx_forum_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_forum\";s:6:\"method\";s:14:\"browse_context\";}', 0, 0, 1, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `module`=@sName AND `name`='discussions-context';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_group_view_submenu', @sName, 'discussions-context', '_bx_forum_menu_item_title_system_view_entries_in_context', '_bx_forum_menu_item_title_view_entries_in_context', 'page.php?i=discussions-context&profile_id={profile_id}', '', '', 'comments-o col-blue2', '', 2147483647, 1, 0, 0);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='2' WHERE `Name`=@sName;


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`=@sName;
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
(@sName, @sName, 'bx_forum_scores', 'bx_forum_scores_track', '604800', '0', 'bx_forum_discussions', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');
