DROP TABLE IF EXISTS `[db_prefix]entries`;
DROP TABLE IF EXISTS `[db_prefix]comments`;
DROP TABLE IF EXISTS `[db_prefix]comments_track`;
DROP TABLE IF EXISTS `[db_prefix]voting`;
DROP TABLE IF EXISTS `[db_prefix]voting_track`;
DROP TABLE IF EXISTS `[db_prefix]views_track`;

SET @iTMParentId = (SELECT `ID` FROM `sys_menu_top` WHERE `Name`='News' LIMIT 1);
DELETE FROM `sys_menu_top` WHERE `Name` IN ('News', '[db_prefix]_view') OR `Parent`=@iTMParentId;
DELETE FROM `sys_menu_member` WHERE `Name`='News';
DELETE FROM `sys_menu_admin` WHERE `name`='bx_news';

DELETE FROM `sys_permalinks` WHERE `check`='permalinks_module_news';

SET @iCategoryId = (SELECT `ID` FROM `sys_options_cats` WHERE `name`='News' LIMIT 1);
DELETE FROM `sys_options_cats` WHERE `name`='News' LIMIT 1;
DELETE FROM `sys_options` WHERE `kateg`=@iCategoryId OR `Name` IN ('permalinks_module_news', 'category_auto_app_bx_news');

DELETE FROM `sys_objects_cmts` WHERE `ObjectName`='bx_news' LIMIT 1;
DELETE FROM `sys_objects_vote` WHERE `ObjectName`='bx_news' LIMIT 1;
DELETE FROM `sys_objects_tag` WHERE `ObjectName`='bx_news' LIMIT 1;
DELETE FROM `sys_objects_categories` WHERE `ObjectName`='bx_news' LIMIT 1;
DELETE FROM `sys_categories` WHERE `Type` = 'bx_news';
DELETE FROM `sys_objects_search` WHERE `ObjectName`='bx_news' LIMIT 1;
DELETE FROM `sys_objects_views` WHERE `name`='bx_news' LIMIT 1;

DELETE FROM `sys_page_compose_pages` WHERE `Name` IN ('news_single', 'news_home');
DELETE FROM `sys_page_compose` WHERE `Page` IN ('news_single', 'news_home') OR `Caption` IN ('_news_bcaption_featured', '_news_bcaption_latest', '_news_bcaption_member');

DELETE FROM `sys_objects_actions` WHERE `Type`='bx_news';

DELETE FROM `sys_sbs_entries` USING `sys_sbs_types`, `sys_sbs_entries` WHERE `sys_sbs_types`.`id`=`sys_sbs_entries`.`subscription_id` AND `sys_sbs_types`.`unit`='bx_news';
DELETE FROM `sys_sbs_types` WHERE `unit`='bx_news';

DELETE FROM `sys_email_templates` WHERE `Name` IN ('t_sbsNewsComments', 't_sbsNewsRates');

DELETE FROM `sys_acl_actions` WHERE `Name` IN ('News Delete');

DELETE FROM `sys_cron_jobs` WHERE `name`='bx_news';

-- mobile

DELETE FROM `sys_menu_mobile` WHERE `type` = 'bx_news';

