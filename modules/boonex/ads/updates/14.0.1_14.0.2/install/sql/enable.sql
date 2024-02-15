-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_ads_entry_attachments' AND `name`='link';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_ads_entry_attachments', 'bx_ads', 'link', '_bx_ads_menu_item_title_system_cpa_link', '_bx_ads_menu_item_title_cpa_link', 'javascript:void(0)', 'javascript:{js_object_link}.showAttachLink(this);', '_self', 'link', '', '', 2147483647, '', 1, 0, 1, 8);
