-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_shopify_view_actions' AND `name`='buy-shopify-entry';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_shopify_view_actions', 'bx_shopify', 'buy-shopify-entry', '_bx_shopify_menu_item_title_system_buy_entry', '_bx_shopify_menu_item_title_buy_entry', '', '', '', '', '', '', 0, 2147483647, 1, 0, 0);
