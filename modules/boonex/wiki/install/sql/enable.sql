
-- Wiki object

INSERT INTO `sys_objects_wiki` (`object`, `uri`, `title`, `module`, `allow_add_for_levels`, `allow_edit_for_levels`, `allow_delete_for_levels`, `allow_translate_for_levels`, `allow_unsafe_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_wiki', 'wiki', '_bx_wiki_object_title', 'bx_wiki', 192, 192, 192, 192, 0, '', '');

-- Permalinks

INSERT INTO `sys_permalinks` (`standard`, `permalink`, `check`, `compare_by_prefix`) VALUES
('r.php?_q=wiki/', 'wiki/', 'permalinks_pages', 1);

-- Rewrite rules

INSERT INTO `sys_rewrite_rules` (`preg`, `service`, `active`) VALUES
('^wiki/(.*)$', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:9:"wiki_page";s:6:"params";a:2:{i:0;s:4:"wiki";i:1;s:3:"{1}";}s:5:"class";s:16:"TemplServiceWiki";}', 1),
('^wiki-action/(.*)$', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"wiki_action";s:6:"params";a:2:{i:0;s:4:"wiki";i:1;s:3:"{1}";}s:5:"class";s:16:"TemplServiceWiki";}', '1');

-- MENU: add to site menu

SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_wiki', 'wiki-home', '', '_bx_wiki_menu_item_title_system_home', 'r.php?_q=wiki/wiki-home', '', '', 'fab wikipedia-w', '', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu

SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_wiki', 'wiki-home', '', '_bx_wiki_menu_item_title_system_home', 'r.php?_q=wiki/wiki-home', '', '', 'fab wikipedia-w', '', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);
