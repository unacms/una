-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_spaces_edit_profile_settings';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_spaces_edit_profile_settings', 'edit-space-settings', '_bx_spaces_page_title_sys_edit_profile_settings', '_bx_spaces_page_title_edit_profile_settings', 'bx_spaces', 5, 2147483647, 1, 'page.php?i=edit-space-settings', '', '', '', 0, 1, 0, 'BxSpacesPageEntry', 'modules/boonex/spaces/classes/BxSpacesPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_spaces_edit_profile_settings';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_spaces_edit_profile_settings', 1, 'bx_spaces', '_bx_spaces_page_block_title_edit_profile_settings', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_spaces";s:6:"method";s:20:"entity_edit_settings";}', 0, 0, 1, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_spaces_view_actions_more' AND `name`='edit-space-settings';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_spaces_view_actions_more', 'bx_spaces', 'edit-space-settings', '_bx_spaces_menu_item_title_system_edit_settings', '_bx_spaces_menu_item_title_edit_settings', 'page.php?i=edit-space-settings&id={content_id}', '', '', 'toolbox', '', 2147483647, '', 1, 0, 42);
UPDATE `sys_menu_items` SET `order`='45' WHERE `set_name`='bx_spaces_view_actions_more' AND `name`='invite-to-space';
