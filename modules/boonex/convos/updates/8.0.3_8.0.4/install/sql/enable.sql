UPDATE `sys_pages_blocks` SET `designbox_id`='13' WHERE `object`='bx_convos_view_entry' AND `title`='_bx_cnv_page_block_title_entry_collaborators' LIMIT 1;
UPDATE `sys_pages_blocks` SET `designbox_id`='13' WHERE `object`='bx_convos_view_entry' AND `title`='_bx_cnv_page_block_title_entry_actions' LIMIT 1;
UPDATE `sys_pages_blocks` SET `designbox_id`='13' WHERE `object`='bx_convos_view_entry' AND `title`='_bx_cnv_page_block_title_entry_author' LIMIT 1;


UPDATE `sys_objects_cmts` SET `IsRatable`='1' WHERE `Name`='bx_convos' LIMIT 1;


SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_convos' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='profile' AND `action`='delete' AND `handler_id`=@iHandler LIMIT 1;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES ('profile', 'delete', @iHandler);