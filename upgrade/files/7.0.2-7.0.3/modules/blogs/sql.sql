
ALTER TABLE `[db_prefix]_posts` ADD KEY `OwnerID` (`OwnerID`);

UPDATE `sys_page_compose_pages` SET `Title` = 'Blog Post View' WHERE `Name` = 'bx_blogs' AND `Title` = 'blog post view';

SET @iPCPOrder = (SELECT `Order` FROM `sys_page_compose_pages` WHERE `Name` = 'bx_blogs' ORDER BY `Order` DESC LIMIT 1);
INSERT IGNORE INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_blogs_home', 'Blog Home', @iPCPOrder);

INSERT INTO `sys_page_compose` (`ID`, `Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES
(NULL, 'bx_blogs_home', '998px', '', '_bx_blog_Top_blog', 1, 1, 'Top', '', 1, 34, 'non,memb', 0),
(NULL, 'bx_blogs_home', '998px', '', '_bx_blog_Latest_posts', 2, 1, 'Latest', '', 1, 66, 'non,memb', 0);

UPDATE `sys_objects_actions` SET `Eval` = 'if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode} == true || {edit_allowed}) {\r\n    return (getParam(''permalinks_blogs'') == ''on'') ? ''blogs/my_page/edit/{post_id}'' : ''modules/boonex/blogs/blogs.php?action=edit_post&EditPostID={post_id}'';\r\n}\r\nelse\r\n    return null;' WHERE `Type` = '[db_prefix]' AND `Eval` = 'if (({visitor_id}=={owner_id} && {owner_id}>0) || {admin_mode} == true) {\r\n    return (getParam(''permalinks_blogs'') == ''on'') ? ''blogs/my_page/edit/{post_id}'' : ''modules/boonex/blogs/blogs.php?action=edit_post&EditPostID={post_id}'';\r\n}\r\nelse\r\n    return null;';
UPDATE `sys_objects_actions` SET `Eval` = '$sButAct = ''{sSACaption}'';\r\nif ({admin_mode} == true || {allow_approve}) {\r\nreturn $sButAct;\r\n}\r\nelse\r\nreturn null;' WHERE `Type` = '[db_prefix]' AND `Eval` = '$sButAct = ''{sSACaption}'';\r\nif ({admin_mode} == true) {\r\nreturn $sButAct;\r\n}\r\nelse\r\nreturn null;';

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'blogs' AND `version` = '1.0.2';

