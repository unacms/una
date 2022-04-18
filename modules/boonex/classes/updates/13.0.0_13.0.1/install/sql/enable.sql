-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_classes_entry_attachments' AND `name`='add-link';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_classes_entry_attachments', 'bx_classes', 'add-link', '_bx_classes_menu_item_title_system_add_link', '_bx_classes_menu_item_title_add_link', 'javascript:void(0)', 'javascript:{js_object_link}.showAttachLink(this);', '_self', 'link', '', '', 2147483647, '', 1, 0, 1, 10);

DELETE FROM `sys_menu_items` WHERE `set_name`='' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_classes_view_actions', 'bx_classes', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300);

-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_classes' WHERE `object`='bx_classes';
