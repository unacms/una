
-- can be safely applied multiple times


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'system');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_live_updates_interval', '', '3000', 'digit', '', '', '', 70);



DELETE FROM `sys_objects_social_sharing` WHERE `object` = 'facebook';
INSERT INTO `sys_objects_social_sharing` (`object`, `type`, `content`, `order`, `active`) VALUES
('facebook', 'html', '<a class="bx-btn" title="<bx_text:_sys_social_sharing_title_facebook />" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={url_encoded}"><i class="sys-icon facebook"></i></a>', 1, 1);



DELETE FROM `sys_injections` WHERE `name` = 'live_updates';
INSERT INTO `sys_injections`(`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
('live_updates', 0, 'injection_head', 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:4:"init";s:6:"params";a:0:{}s:5:"class";s:24:"TemplLiveUpdatesServices";}', 0, 1);



UPDATE `sys_objects_menu` SET `template_id` = 14 WHERE `object` = 'sys_site';
UPDATE `sys_objects_menu` SET `override_class_name` = 'BxTemplMenuToolbar' WHERE `object` = 'sys_toolbar_site';
UPDATE `sys_objects_menu` SET `override_class_name` = 'BxTemplMenuSite' WHERE `object` = 'sys_add_content';

DELETE FROM `sys_objects_menu` WHERE `object` IN('sys_account_dashboard_manage_tools', 'sys_account_dashboard_submenu', 'sys_account_dashboard_administration');
INSERT INTO `sys_objects_menu` (`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_account_dashboard_manage_tools', '_sys_menu_title_account_dashboard_manage_tools', 'sys_account_dashboard_manage_tools', 'system', 4, 0, 1, '', '');



DELETE FROM `sys_menu_sets` WHERE `set_name` IN('sys_account_dashboard', 'sys_account_dashboard_administration', 'sys_account_dashboard_manage_tools');
INSERT INTO `sys_menu_sets` (`set_name`, `module`, `title`, `deletable`) VALUES
('sys_account_dashboard_manage_tools', 'system', '_sys_menu_set_title_account_dashboard_manage_tools', 0);



DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `name` = 'search';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_site', 'system', 'search', '_sys_menu_item_title_system_search', '_sys_menu_item_title_search', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-search\', this, \'site\');', '', 'search', '', 2147483647, 1, 1, 3);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_toolbar_site' AND `name` IN('main-menu', 'search');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_toolbar_site', 'system', 'main-menu', '_sys_menu_item_title_system_main_menu', '', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-sys_site\', this, \'site\');', '', 'a:bars', '', 2147483647, 1, 1, 1),
('sys_toolbar_site', 'system', 'search', '_sys_menu_item_title_system_search', '', 'javascript:void(0);', 'bx_menu_slide(''#bx-sliding-menu-search'', this, ''site'');', '', 'search', '', 2147483647, 1, 1, 2);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_toolbar_member' AND `name` IN('add-content', 'account');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_toolbar_member', 'system', 'add-content', '_sys_menu_item_title_system_add_content', '', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-sys_add_content\', this, \'site\');', '', 'a:plus', '', 510, 1, 1, 0),
('sys_toolbar_member', 'system', 'account', '_sys_menu_item_title_system_account_menu', '_sys_menu_item_title_account_menu', 'javascript:void(0);', 'bx_menu_slide(''#bx-sliding-menu-account'', this, ''site'');', '', 'user', 'sys_account_popup', 510, 1, 0, 1);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_links' AND `name` = 'add-content';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_links', 'system', 'add-content', '_sys_menu_item_title_system_add_content', '_sys_menu_item_title_add_content', 'javascript:void(0);', 'bx_menu_slide(\'#bx-sliding-menu-sys_add_content\', $(\'bx-menu-toolbar-item-add-content a\').get(0), \'site\');', '', 'plus', '', 2147483646, 1, 1, 4);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_dashboard' AND `name` = 'account-dashboard-administration';



DELETE FROM `sys_objects_page` WHERE `object` = 'sys_add_content';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_add_content';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `title` = '_sys_page_block_title_manage_tools';

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_dashboard', 3, 'system', '_sys_page_block_title_manage_tools', 11, 192, 'menu', 'sys_account_dashboard_manage_tools', 0, 1, 0);



CREATE TABLE IF NOT EXISTS `sys_objects_live_updates` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `frequency` tinyint(4) NOT NULL DEFAULT '1',
  `service_call` text NOT NULL default '', 
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



-- last step is to update current version

UPDATE `sys_modules` SET `version` = '8.0.0-A8' WHERE `version` = '8.0.0-A7' AND `name` = 'system';

