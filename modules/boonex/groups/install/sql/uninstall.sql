
-- studio page and widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id`=`tw`.`page_id` AND `tw`.`id`=`tpw`.`widget_id` AND `tp`.`name`='bx_groups';

-- tables
DROP TABLE IF EXISTS `bx_groups_main`;
DROP TABLE IF EXISTS `bx_groups_fans`;
DROP TABLE IF EXISTS `bx_groups_admins`;
DROP TABLE IF EXISTS `bx_groups_images`;
DROP TABLE IF EXISTS `bx_groups_rating`;
DROP TABLE IF EXISTS `bx_groups_rating_track`;
DROP TABLE IF EXISTS `bx_groups_cmts`;
DROP TABLE IF EXISTS `bx_groups_cmts_track`;
DROP TABLE IF EXISTS `bx_groups_views_track`;

-- system objects
DELETE FROM `sys_objects_categories` WHERE `ObjectName` = 'bx_groups';
DELETE FROM `sys_categories` WHERE `Type` = 'bx_groups';
DELETE FROM `sys_objects_tag` WHERE `ObjectName` = 'bx_groups';
DELETE FROM `sys_tags` WHERE `Type` = 'bx_groups';


