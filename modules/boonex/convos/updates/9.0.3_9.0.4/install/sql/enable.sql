-- PAGES
UPDATE `sys_pages_blocks` SET `cell_id`='3', `active`='0', `order`='0' WHERE `object`='sys_dashboard' AND `title`='_bx_cnv_page_block_title_convos';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Module`='bx_convos' WHERE `Name`='bx_convos';


-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name`='bx_convos';
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('bx_convos', 1, 'a:3:{s:6:"module";s:9:"bx_convos";s:6:"method";s:16:"get_live_updates";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:20:"notifications-convos";}i:2;s:7:"{count}";}}', 1);
