SET @sName = 'bx_lucid';


-- PAGE: explore
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_lucid_explore', '_bx_lucid_page_title_sys_explore', '_bx_lucid_page_title_explore', @sName, 5, 2147483647, 1, 'explore', 'page.php?i=explore', '', '', '', 0, 1, 0, 'BxTemplPageHome', '');

-- PAGE: updates
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_lucid_updates', '_bx_lucid_page_title_sys_updates', '_bx_lucid_page_title_updates', @sName, 5, 2147483647, 1, 'updates', 'page.php?i=updates', '', '', '', 0, 1, 0, 'BxTemplPageHome', '');

-- PAGE: trends
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_lucid_trends', '_bx_lucid_page_title_sys_trends', '_bx_lucid_page_title_trends', @sName, 5, 2147483647, 1, 'trends', 'page.php?i=trends', '', '', '', 0, 1, 0, 'BxTemplPageHome', '');


-- MENU: dropdown menu
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(ROUND(RAND()*(9999 - 1000) + 1000), 'menu_dropdown_site.html', '_bx_lucid_menu_template_title_dropdown_site', 1);
SET @iTemplId = (SELECT `id` FROM `sys_menu_templates` WHERE `template`='menu_dropdown_site.html' AND `title`='_bx_lucid_menu_template_title_dropdown_site' LIMIT 1);

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_lucid_dropdown_site', '_bx_lucid_menu_title_dropdown_site', 'sys_site', @sName, @iTemplId, 0, 1, 'BxTemplMenuDropdownSite', '');

-- MENU: member toolbar
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_toolbar_member' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_toolbar_member', 'system', 'bx_lucid_search', '_bx_lucid_menu_item_title_system_search', '', 'javascript:void(0);', 'bx_menu_slide_inline(''#bx-sliding-menu-search'', this, ''site'');', '', 'search', '', '', 0, 2147483647, 1, 0, 0),
('sys_toolbar_member', 'system', 'bx_lucid_login', '_bx_lucid_menu_item_title_system_login', '_bx_lucid_menu_item_title_login', 'page.php?i=login', '', '', '',  '', '', 0, 1, 1, 0, @iMIOrder + 1),
('sys_toolbar_member', 'system', 'bx_lucid_join', '_bx_lucid_menu_item_title_system_join', '_bx_lucid_menu_item_title_join', 'page.php?i=create-account', '', '', '',  '', '', 0, 1, 1, 0, @iMIOrder + 2);

-- MENU: home page submenu
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_lucid_homepage_submenu', '_bx_lucid_menu_title_homepage_submenu', 'bx_lucid_homepage_submenu', @sName, 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('bx_lucid_homepage_submenu', @sName, '_bx_lucid_menu_set_title_homepage_submenu', 0);

INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_lucid_homepage_submenu', 'system', 'home', '_bx_lucid_menu_item_title_system_home', '_bx_lucid_menu_item_title_home', 'index.php', '', '', 'bolt', '', 2147483647, 1, 1, 1),
('bx_lucid_homepage_submenu', @sName, 'explore', '_bx_lucid_menu_item_title_system_explore', '_bx_lucid_menu_item_title_explore', 'page.php?i=explore', '', '', 'compass ', '', 2147483647, 1, 1, 2),
('bx_lucid_homepage_submenu', @sName, 'updates', '_bx_lucid_menu_item_title_system_updates', '_bx_lucid_menu_item_title_updates', 'page.php?i=updates', '', '', 'fire', '', 2147483647, 1, 1, 3),
('bx_lucid_homepage_submenu', @sName, 'trends', '_bx_lucid_menu_item_title_system_trends', '_bx_lucid_menu_item_title_trends', 'page.php?i=trends', '', '', 'hashtag', '', 2147483647, 1, 1, 4);
