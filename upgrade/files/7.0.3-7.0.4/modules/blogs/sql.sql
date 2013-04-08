
DELETE FROM `sys_menu_top` WHERE `Name` = 'Featured Posts' AND `Link` = 'modules/boonex/blogs/blogs.php?action=featured_posts';
DELETE FROM `sys_permalinks` WHERE `standard` = 'modules/boonex/blogs/blogs.php?action=featured_posts' AND `permalink` = 'blogs/featured_posts/' AND `check` = 'permalinks_blogs';

UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'blogs' AND `version` = '1.0.3';

