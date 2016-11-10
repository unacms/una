-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_convos@modules/boonex/convos/|std-icon.svg' WHERE `name`='bx_convos';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_convos_edit_entry';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_convos_edit_entry', '_bx_cnv_page_title_sys_edit_entry', '_bx_cnv_page_title_edit_entry', 'bx_convos', 5, 2147483647, 0, 'edit-convo', '', '', '', '', 0, 0, 0, 'BxCnvPageBrowse', 'modules/boonex/convos/classes/BxCnvPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_convos_edit_entry' AND `title` IN ('_bx_cnv_page_block_title_edit_entry');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_convos_edit_entry', 1, 'bx_convos', '_bx_cnv_page_block_title_edit_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_convos";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_convos_view' AND `name`='edit-convo';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_convos_view', 'bx_convos', 'edit-convo', '_bx_cnv_menu_item_title_system_edit_entry', '_bx_cnv_menu_item_title_edit_entry', 'page.php?i=edit-convo&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 1);