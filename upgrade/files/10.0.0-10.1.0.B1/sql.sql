
UPDATE `sys_menu_templates` SET `visible` = 0 WHERE `template` = 'menu_interactive_vertical.html';
UPDATE `sys_menu_templates` SET `visible` = 0 WHERE `template` = 'menu_interactive.html';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-quote';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`, `visibility_custom`) VALUES 
('sys_cmts_item_actions', 'system', 'item-quote', '_sys_menu_item_title_system_cmts_item_quote', '_sys_menu_item_title_cmts_item_quote', 'javascript:void(0)', 'javascript:{quote_onclick}', '_self', 'quote-right', '', '', 0, 2147483647, 1, 0, 1, 4, '');

UPDATE `sys_menu_items` SET `order` = 5 WHERE `set_name` = 'sys_cmts_item_actions' AND `name` = 'item-more' AND `order` = 4;

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '10.1.0-B1' WHERE (`version` = '10.0.0') AND `name` = 'system';

