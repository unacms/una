SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_post_profile', `title`='_bx_timeline_page_block_title_post_profile', `active`='1', `order`='1' WHERE `object`='bx_timeline_view' AND `title`='_bx_timeline_page_block_title_post';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_profile', `title`='_bx_timeline_page_block_title_view_profile', `active`='1', `order`='2' WHERE `object`='bx_timeline_view' AND `title`='_bx_timeline_page_block_title_view';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_profile_outline', `title`='_bx_timeline_page_block_title_view_profile_outline', `active`='0', `order`='3' WHERE `object`='bx_timeline_view' AND `title`='_bx_timeline_page_block_title_view_outline';

UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_post_home', `title`='_bx_timeline_page_block_title_post_home', `active`='1', `order`='1' WHERE `object`='bx_timeline_view_home' AND `title`='_bx_timeline_page_block_title_post';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_home', `title`='_bx_timeline_page_block_title_view_home', `active`='0', `order`='2' WHERE `object`='bx_timeline_view_home' AND `title`='_bx_timeline_page_block_title_view';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_home_outline', `title`='_bx_timeline_page_block_title_view_home_outline', `active`='1', `order`='3' WHERE `object`='bx_timeline_view_home' AND `title`='_bx_timeline_page_block_title_view_outline';

SET @iPBCellDashboard = 2;
SET @iPBOrderDashboard = 1;
DELETE FROM `sys_pages_blocks` WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_post_account';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard, @sName, '_bx_timeline_page_block_title_system_post_account', '_bx_timeline_page_block_title_post_account', 11, 2147483646, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_account";}', 0, 1, 1, @iPBOrderDashboard);

UPDATE `sys_pages_blocks` SET `cell_id`=@iPBCellDashboard, `title_system`='_bx_timeline_page_block_title_system_view_account', `title`='_bx_timeline_page_block_title_view_account', `visible_for_levels`='2147483646', `active`='1', `order`=@iPBOrderDashboard + 1 WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_view_account';
UPDATE `sys_pages_blocks` SET `cell_id`=@iPBCellDashboard, `title_system`='_bx_timeline_page_block_title_system_view_account_outline', `title`='_bx_timeline_page_block_title_view_account_outline', `visible_for_levels`='2147483646', `active`='0', `order`=@iPBOrderDashboard + 1 WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_view_account_outline';

UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_post_home', `title`='_bx_timeline_page_block_title_post_home' WHERE `object`='sys_home' AND `title`='_bx_timeline_page_block_title_post_home';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_home', `title`='_bx_timeline_page_block_title_view_home' WHERE `object`='sys_home' AND `title`='_bx_timeline_page_block_title_view_home';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_home_outline', `title`='_bx_timeline_page_block_title_view_home_outline' WHERE `object`='sys_home' AND `title`='_bx_timeline_page_block_title_view_home_outline';

UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_post_profile', `title`='_bx_timeline_page_block_title_post_profile' WHERE `object`='trigger_page_profile_view_entry' AND `title`='_bx_timeline_page_block_title_post_profile';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_profile', `title`='_bx_timeline_page_block_title_view_profile' WHERE `object`='trigger_page_profile_view_entry' AND `title`='_bx_timeline_page_block_title_view_profile';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_profile_outline', `title`='_bx_timeline_page_block_title_view_profile_outline' WHERE `object`='trigger_page_profile_view_entry' AND `title`='_bx_timeline_page_block_title_view_profile_outline';

UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_post_profile', `title`='_bx_timeline_page_block_title_post_profile', `active`='1' WHERE `object`='trigger_page_group_view_entry' AND `title`='_bx_timeline_page_block_title_post_profile';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_profile', `title`='_bx_timeline_page_block_title_view_profile', `active`='1' WHERE `object`='trigger_page_group_view_entry' AND `title`='_bx_timeline_page_block_title_view_profile';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_timeline_page_block_title_system_view_profile_outline', `title`='_bx_timeline_page_block_title_view_profile_outline', `content`='a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:30:"get_block_view_profile_outline";s:6:"params";a:1:{i:0;s:6:"{type}";}}', `active`='0' WHERE `object`='trigger_page_group_view_entry' AND `title`='_bx_timeline_page_block_title_view_profile_outline';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-view';


-- PRIVACY 
UPDATE `sys_objects_privacy` SET `table_field_author`='' WHERE `object`='bx_timeline_privacy_view';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Module`=@sName WHERE `Name`=@sName;


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN (@sName, 'bx_timeline_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
(@sName, '_bx_timeline', @sName, 'post_common', '', 'delete', '', ''),
('bx_timeline_cmts', '_bx_timeline_cmts', @sName, 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');
