
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_posts' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_posts';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_posts' OR `object` IN('bx_posts_create_entry', 'bx_posts_edit_entry', 'bx_posts_delete_entry', 'bx_posts_view_entry', 'bx_posts_view_entry_comments', 'bx_posts_home', 'bx_posts_featured', 'bx_posts_author');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_posts';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_posts';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_posts' OR `set_name` IN('bx_posts_view', 'bx_posts_submenu', 'bx_posts_view_submenu', 'bx_posts_my');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` = 'bx_posts_allow_view_to';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_posts';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_posts';

-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_posts', 'bx_posts_cmts');

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_posts';

