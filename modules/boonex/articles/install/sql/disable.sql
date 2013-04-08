SET @sName = 'bx_articles';


SET @iTMParentId = (SELECT `ID` FROM `sys_menu_top` WHERE `Name`='Articles' LIMIT 1);
DELETE FROM `sys_menu_top` WHERE `Name` IN ('Articles', 'bx_arl_view') OR `Parent`=@iTMParentId;
DELETE FROM `sys_menu_member` WHERE `Name`='Articles';


DELETE FROM `sys_page_compose_pages` WHERE `Name` IN ('articles_single', 'articles_home');
DELETE FROM `sys_page_compose` WHERE `Page` IN ('articles_single', 'articles_home') OR `Caption` IN ('_articles_bcaption_featured', '_articles_bcaption_latest', '_articles_bcaption_member');


DELETE FROM `sys_options` WHERE `Name` IN ('category_auto_app_bx_articles');
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name`=@sName;


DELETE FROM `sys_objects_cmts` WHERE `ObjectName`=@sName LIMIT 1;
DELETE FROM `sys_objects_vote` WHERE `ObjectName`=@sName LIMIT 1;
DELETE FROM `sys_objects_tag` WHERE `ObjectName`=@sName LIMIT 1;
DELETE FROM `sys_objects_categories` WHERE `ObjectName`=@sName LIMIT 1;
DELETE FROM `sys_objects_search` WHERE `ObjectName`=@sName LIMIT 1;
DELETE FROM `sys_objects_views` WHERE `name`=@sName LIMIT 1;
DELETE FROM `sys_objects_actions` WHERE `Type`=@sName;


DELETE FROM `sys_email_templates` WHERE `Name` IN ('t_sbsArticlesComments', 't_sbsArticlesRates');


DELETE FROM `sys_acl_actions` WHERE `Module`=@sName;


DELETE FROM `sys_cron_jobs` WHERE `name`=@sName;
