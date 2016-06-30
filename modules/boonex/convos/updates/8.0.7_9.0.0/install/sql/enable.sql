-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_convos_view_entry' AND `title`='_bx_cnv_page_block_title_entry_actions';


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_convos_view_popup';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_convos_submenu' AND `name` IN ('convos-folder-more', 'convos-drafts', 'convos-spam', 'convos-trash');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_convos_submenu', 'bx_convos', 'convos-drafts', '_bx_cnv_menu_item_title_system_folder_drafts', '_bx_cnv_menu_item_title_folder_drafts', 'modules/?r=convos/folder/2', '', '', '', '', 2147483647, 1, 1, 2),
('bx_convos_submenu', 'bx_convos', 'convos-spam', '_bx_cnv_menu_item_title_system_folder_spam', '_bx_cnv_menu_item_title_folder_spam', 'modules/?r=convos/folder/3', '', '', '', '', 2147483647, 1, 1, 3),
('bx_convos_submenu', 'bx_convos', 'convos-trash', '_bx_cnv_menu_item_title_system_folder_trash', '_bx_cnv_menu_item_title_folder_trash', 'modules/?r=convos/folder/4', '', '', '', '', 2147483647, 1, 1, 4);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_convos_menu_folders_more';
DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_convos_menu_folders_more';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_convos_menu_folders_more'; 

DELETE FROM `sys_menu_items` WHERE `set_name`='trigger_group_view_actions' AND `name`='convos-compose';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_group_view_actions', 'bx_convos', 'convos-compose', '_bx_cnv_menu_item_title_system_message_group', '_bx_cnv_menu_item_title_message_group', 'page.php?i=start-convo&profiles={recipients}', '', '', 'envelope', '', 2147483646, 1, 0, 0);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldAuthor`='author' WHERE `Name`='bx_convos';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module`='bx_convos' AND `Name` IN ('bx_cnv_new_message', 'bx_cnv_new_reply');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_convos', '_bx_cnv_email_new_message', 'bx_cnv_new_message', '_bx_cnv_email_new_message_subject', '_bx_cnv_email_new_message_body'),
('bx_convos', '_bx_cnv_email_new_reply', 'bx_cnv_new_reply', '_bx_cnv_email_new_reply_subject', '_bx_cnv_email_new_reply_body');