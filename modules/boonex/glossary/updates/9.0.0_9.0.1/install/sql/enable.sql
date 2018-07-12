-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='2' WHERE `object`='bx_glossary_home';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_glossary_home' AND `title`='_bx_glossary_page_block_title_cats';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_glossary_home', 2, 'bx_glossary', '', '_bx_glossary_page_block_title_cats', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"categories_list";s:6:"params";a:2:{i:0;s:16:"bx_glossary_cats";i:1;a:1:{s:10:"show_empty";b:1;}}s:5:"class";s:20:"TemplServiceCategory";}', 0, 1, 0, 1);


-- GRIDS
UPDATE `sys_objects_grid` SET `sorting_fields`='reports' WHERE `object`='bx_glossary_administration';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_glossary_administration' AND `name`='reports';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_glossary_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 3);

UPDATE `sys_grid_fields` SET `width`='20%' WHERE `object`='bx_glossary_administration' AND `name`='author';
