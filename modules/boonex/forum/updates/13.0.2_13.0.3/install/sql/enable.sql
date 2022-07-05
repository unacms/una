SET @sName = 'bx_forum';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_forum_visible_categories';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_forum_visible_categories', '10', @iCategId, '_bx_forum_option_visible_categories', 'digit', '', '', '', 40);


-- PAGES
UPDATE `sys_pages_blocks` SET `type`='menu', `content`='bx_forum_categories' WHERE `object`='bx_forum_category' AND `title`='_bx_forum_page_block_title_cats';

UPDATE `sys_pages_blocks` SET `type`='menu', `content`='bx_forum_categories' WHERE `object`='bx_forum_home' AND `title`='_bx_forum_page_block_title_cats';
UPDATE `sys_pages_blocks` SET `content`='a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:14:\"keywords_cloud\";s:6:\"params\";a:3:{i:0;s:8:\"bx_forum\";i:1;s:8:\"bx_forum\";i:2;a:1:{s:9:\"menu_view\";b:1;}}s:5:\"class\";s:20:\"TemplServiceMetatags\";}' WHERE `object`='bx_forum_home' AND `title`='_bx_forum_page_block_title_popular_keywords';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_snippet_meta_main' AND `name` IN ('score', 'badges');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`, `hidden_on_col`) VALUES 
('bx_forum_snippet_meta_main', 'bx_forum', 'score', '_bx_forum_menu_item_title_system_sm_vote', '_bx_forum_menu_item_title_sm_vote', '', '', '', '', '', 2147483647, 1, 0, 1, 0, 0);

UPDATE `sys_menu_items` SET `hidden_on_col`='3' WHERE `set_name`='bx_forum_snippet_meta_main' AND `name`='comments';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_forum_categories';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_categories', '_bx_forum_menu_title_categories', '', 'bx_forum', 21, 0, 1, 'BxForumMenuCategories', 'modules/boonex/forum/classes/BxForumMenuCategories.php');


-- GRIDS
DELETE FROM `sys_grid_fields` WHERE `object`=@sName AND `name` IN ('category', 'participants', 'rating');
UPDATE `sys_grid_fields` SET `width`='100%', `order`='1' WHERE `object`=@sName AND `name`='text';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_favorite' AND `name` IN ('author');
UPDATE `sys_grid_fields` SET `width`='100%' WHERE `object`='bx_forum_favorite' AND `name`='text';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_feature';
