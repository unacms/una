-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_posts' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_posts_searchable_fields');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_posts_searchable_fields', 'title,text', @iCategoryId, '_bx_posts_option_searchable_fields', 'list', '', '', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:21:"get_searchable_fields";}', 30);


-- MENUS
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_posts_view_submenu' AND `module`='bx_posts' AND `name` IN ('view-post', 'view-post-comments');


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='192' WHERE `object`='bx_posts_administration';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_posts_common';