
ALTER TABLE `[db_prefix]_posts` ADD FULLTEXT KEY `ftTags` (`Tags`);
ALTER TABLE `[db_prefix]_posts` ADD FULLTEXT KEY `ftCategories` (`Categories`);

ALTER TABLE `[db_prefix]_cmts` CHANGE `cmt_rate` `cmt_rate` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `[db_prefix]_cmts` CHANGE `cmt_rate_count` `cmt_rate_count` INT( 11 ) NOT NULL DEFAULT '0';

UPDATE `sys_menu_top` SET `Caption` = '_bx_blog_post_view' WHERE `Name` = 'bx_blogpost_view' AND `Caption` = '_bx_blog_Posts';

INSERT IGNORE INTO `sys_permalinks`(`standard`, `permalink`, `check`) VALUES
('modules/boonex/blogs/blogs.php?action=all', 'blogs/all/', 'permalinks_blogs');

SET @iGlCategID = (SELECT `kateg` FROM `sys_options` WHERE `Name` = 'bx_blogs_thumbsize');
INSERT IGNORE INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`) VALUES
('category_auto_app_bx_blogs', 'on', @iGlCategID, 'Auto-activation for categories after blog posts creation', 'checkbox', '', '', 9);

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'blogs' AND `version` = '1.0.0';

