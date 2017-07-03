SET @sName = 'bx_acl';


-- PAGES
UPDATE `sys_objects_page` SET `override_class_name`='BxAclPageView', `override_class_file`='modules/boonex/acl/classes/BxAclPageView.php' WHERE `object`='bx_acl_view';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name` IN ('sys_account_notifications', 'sys_account_settings') AND `name`='acl-view';
SET @iMoAccountSettings = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_settings' AND `order` < 9999 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_settings', 'bx_acl', 'acl-view', '_bx_acl_menu_item_title_system_acl_view', '_bx_acl_menu_item_title_acl_view', 'page.php?i=acl-view', '', '', 'shield col-red2', '', '', 2147483646, 1, 0, 1, @iMoAccountSettings + 1);
