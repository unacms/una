
-- Wiki object

INSERT INTO `sys_objects_wiki` (`object`, `title`, `module`, `allow_add_for_levels`, `allow_edit_for_levels`, `allow_delete_for_levels`, `allow_translate_for_levels`, `allow_unsafe_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_wiki', '_sys_wiki_system_title', 'system', 192, 192, 192, 192, 192, '', '');

-- Permalinks

INSERT INTO `sys_permalinks` (`standard`, `permalink`, `check`, `compare_by_prefix`) VALUES
('r.php?_q=wiki/', 'wiki/', 'permalinks_pages', 1);

-- Rewrite rules

INSERT INTO `sys_rewrite_rules` (`preg`, `service`, `active`) VALUES
('^wiki/(.*)$', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:9:"wiki_page";s:6:"params";a:2:{i:0;s:4:"wiki";i:1;s:3:"{1}";}s:5:"class";s:16:"TemplServiceWiki";}', 1);

