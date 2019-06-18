-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_shopify_view_actions' AND `name` IN ('reaction', 'social-sharing-googleplus');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_shopify_view_actions', 'bx_shopify', 'reaction', '_sys_menu_item_title_system_va_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 225);

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_shopify_view_actions' AND `name`='vote';

UPDATE `sys_menu_items` SET `icon`='shopping-cart' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='shopify-administration' AND `icon`='';
