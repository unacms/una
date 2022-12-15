
-- CONTENT PLACEHOLDERS
DELETE FROM `sys_pages_content_placeholders` WHERE `module` = 'bx_timeline';


-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_timeline' OR `object` IN('bx_timeline_view', 'bx_timeline_view_home', 'bx_timeline_item', 'bx_photos_item_brief');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_menu_sets` WHERE `module` = 'bx_timeline';
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_timeline' OR `set_name` IN('bx_timeline_menu_view', 'bx_timeline_menu_feeds', 'bx_timeline_menu_item_share', 'bx_timeline_menu_item_manage', 'bx_timeline_menu_item_actions', 'bx_timeline_menu_item_actions', 'bx_timeline_menu_item_counters', 'bx_timeline_menu_item_meta', 'bx_timeline_menu_post_attachments');
DELETE FROM `sys_menu_templates` WHERE `template`='menu_feeds.html' AND `title`='_bx_timeline_menu_template_title_feeds';


-- SETTINGS
DELETE FROM `tot`, `toc`, `to` USING `sys_options_types` AS `tot` LEFT JOIN `sys_options_categories` AS `toc` ON `tot`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `tot`.`name` = 'bx_timeline';


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `module` = 'bx_timeline';


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_timeline';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_timeline';


-- ALERTS
SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_timeline' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandlerId;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandlerId LIMIT 1;


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName` IN ('bx_timeline', 'bx_timeline_cmts');


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_timeline%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_timeline%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_timeline%';


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object` = 'bx_timeline';


-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object` = 'bx_timeline_mute';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_timeline';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` LIKE 'bx_timeline%';
