
DELETE FROM `sys_account_custom_stat_elements` WHERE `Label` = '_bx_blog_Blog';
INSERT INTO `sys_account_custom_stat_elements` (`Label`, `Value`) VALUES
('_bx_blog_Blog', '__mbp__ __l_bx_blog_Posts__, __mbpc__ __l_comments__ (<a href="__site_url__modules/boonex/blogs/blogs.php?action=my_page&mode=add">__l_Post__</a>)');

UPDATE `sys_modules` SET `version` = '1.0.2' WHERE `uri` = 'blogs' AND `version` = '1.0.1';

