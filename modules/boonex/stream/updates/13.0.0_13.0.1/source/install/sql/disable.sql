
-- SETTINGS
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name`='bx_stream';

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_stream';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_stream' OR `object` IN('bx_stream_create_entry', 'bx_stream_edit_entry', 'bx_stream_delete_entry', 'bx_stream_view_entry', 'bx_stream_broadcast', 'bx_stream_view_entry_comments', 'bx_stream_manage');

-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_stream';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_stream';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_stream' OR `set_name` IN('bx_stream_view', 'bx_stream_view_actions', 'bx_stream_snippet_meta', 'bx_stream_menu_manage_tools');

-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN ('bx_stream_allow_view_to');

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_stream';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_stream';

-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_stream';

-- CATEGORY
DELETE FROM `sys_objects_category` WHERE `object` = 'bx_stream_cats';

-- STATS
DELETE FROM `sys_statistics` WHERE `name` LIKE 'bx_stream%';

-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` LIKE 'bx_stream%';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_stream%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_stream%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_stream%';

-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` LIKE 'bx_stream_%';

-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_stream' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_stream%';
