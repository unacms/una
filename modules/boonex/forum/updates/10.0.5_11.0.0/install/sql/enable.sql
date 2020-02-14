SET @sName = 'bx_forum';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_forum_auto_activation_for_categories';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_forum_auto_activation_for_categories', 'on', @iCategId, '_bx_posts_option_auto_activation_for_categories', 'checkbox', '', '', '', '', 35);


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_forum_partaken';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_partaken', '_bx_forum_page_title_sys_entries_partaken', '_bx_forum_page_title_entries_partaken', @sName, 5, 2147483647, 1, 'discussions-partaken', 'page.php?i=discussions-partaken', '', '', '', 0, 1, 0, 'BxForumPageBrowse', 'modules/boonex/forum/classes/BxForumPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_partaken';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_partaken', 1, @sName, '', '_bx_forum_page_block_title_partaken_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:15:"browse_partaken";s:6:"params";a:1:{i:0;s:5:"table";}}', 0, 1, 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_home' AND `title_system`='_bx_forum_page_block_title_sys_multicats';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_forum_home', 2, @sName, '_bx_forum_page_block_title_sys_multicats', '_bx_forum_page_block_title_multicats', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:21:"categories_multi_list";}', 0, 1, 0, 2);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_actions' AND `name`='set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_forum_view_actions', @sName, 'set-badges', '_sys_menu_item_title_system_set_badges', '_sys_menu_item_title_set_badges', 'javascript:void(0)', 'bx_menu_popup(''sys_set_badges'', window, {}, {module: ''bx_forum'', content_id: {content_id}});', '', 'check-circle', '', '', 0, 2147483647, 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 110);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_submenu' AND `name`='discussions-partaken';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_forum_submenu', @sName, 'discussions-partaken', '_bx_forum_menu_item_title_system_entries_partaken', '_bx_forum_menu_item_title_entries_partaken', 'page.php?i=discussions-partaken', '', '', '', '', '', 2147483646, '', 1, 1, 6);


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `bx_forum_discussions`.*, `bx_forum_cmts`.`cmt_text` AS `cmt_text` %s FROM `bx_forum_discussions` LEFT JOIN `bx_forum_cmts` ON (`bx_forum_cmts`.`cmt_id` = `bx_forum_discussions`.`lr_comment_id`) %s WHERE 1 %s %s' WHERE `object`=@sName;
UPDATE `sys_objects_grid` SET `source`='SELECT `bx_forum_discussions`.*, `bx_forum_cmts`.`cmt_text` AS `cmt_text` %s FROM `bx_forum_discussions` LEFT JOIN `bx_forum_cmts` ON (`bx_forum_cmts`.`cmt_id` = `bx_forum_discussions`.`lr_comment_id`) %s WHERE 1 %s %s' WHERE `object`='bx_forum_favorite';
UPDATE `sys_objects_grid` SET `source`='SELECT `bx_forum_discussions`.*, `bx_forum_cmts`.`cmt_text` AS `cmt_text` %s FROM `bx_forum_discussions` LEFT JOIN `bx_forum_cmts` ON (`bx_forum_cmts`.`cmt_id` = `bx_forum_discussions`.`lr_comment_id`) %s WHERE 1 %s %s' WHERE `object`='bx_forum_feature';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_forum_administration' AND `name`='audit_content';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_forum_administration', 'single', 'audit_content', '_bx_forum_grid_action_title_adm_audit_content', 'search', 1, 0, 4);
