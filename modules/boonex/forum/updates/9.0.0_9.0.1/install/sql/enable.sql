SET @sName = 'bx_forum';


-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_forum@modules/boonex/forum/|std-icon.svg' WHERE `name`=@sName;

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_forum_autosubscribe_created', 'bx_forum_autosubscribe_replied');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_forum_autosubscribe_created', '', @iCategId, '_bx_forum_option_autosubscribe_created', 'checkbox', '', '', '', 40),
('bx_forum_autosubscribe_replied', '', @iCategId, '_bx_forum_option_autosubscribe_replied', 'checkbox', '', '', '', 41);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_view_entry' AND `title` IN ('_bx_forum_page_block_title_entry_all_actions');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_view_entry', 4, @sName, '_bx_forum_page_block_title_entry_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:18:"entity_all_actions";}', 0, 0, 1, 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_author' AND `title` IN ('_bx_forum_page_block_title_favorites_of_author');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_author', 1, @sName, '_bx_forum_page_block_title_sys_favorites_of_author', '_bx_forum_page_block_title_favorites_of_author', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:15:"browse_favorite";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 2);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_forum_view', @sName, 'subscribe-discussion', '_bx_forum_menu_item_title_system_subscribe', '_bx_forum_menu_item_title_subscribe', 'javascript:void(0)', 'bx_conn_action(this, \'bx_forum_subscribers\', \'add\', \'{content_id}\')', '', 'check', '', 0, 2147483647, 1, 0, 1),
('bx_forum_view', @sName, 'stick-discussion', '_bx_forum_menu_item_title_system_stick_entry', '_bx_forum_menu_item_title_stick_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'stick\', {content_id});', '', 'thumb-tack', '', 0, 2147483647, 1, 0, 2),
('bx_forum_view', @sName, 'lock-discussion', '_bx_forum_menu_item_title_system_lock_entry', '_bx_forum_menu_item_title_lock_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'lock\', {content_id});', '', 'lock', '', 0, 2147483647, 1, 0, 3),
('bx_forum_view', @sName, 'hide-discussion', '_bx_forum_menu_item_title_system_hide_entry', '_bx_forum_menu_item_title_hide_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'hide\', {content_id});', '', 'eye-slash', '', 0, 2147483647, 1, 0, 4),
('bx_forum_view', @sName, 'more', '_bx_forum_menu_item_title_system_more', '_bx_forum_menu_item_title_more', 'javascript:void(0)', 'bx_menu_popup(\'bx_forum_view_more\', this, {}, {id:{content_id}});', '', 'cog', 'bx_forum_view_more', 1, 2147483647, 1, 0, 9999);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_forum_view_more';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_view_more', '_bx_forum_menu_title_view_more', 'bx_forum_view_more', @sName, 6, 0, 1, 'BxForumMenuView', 'modules/boonex/forum/classes/BxForumMenuView.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_forum_view_more';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_view_more', @sName, '_bx_forum_menu_set_title_view_more', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_view_more';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_forum_view_more', @sName, 'unsubscribe-discussion', '_bx_forum_menu_item_title_system_unsubscribe', '_bx_forum_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'bx_forum_subscribers\', \'remove\', \'{content_id}\')', '', 'check', '', 2147483647, 1, 0, 1),
('bx_forum_view_more', @sName, 'unstick-discussion', '_bx_forum_menu_item_title_system_unstick_entry', '_bx_forum_menu_item_title_unstick_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'unstick\', {content_id});', '', 'thumb-tack', '', 2147483647, 1, 0, 2),
('bx_forum_view_more', @sName, 'unlock-discussion', '_bx_forum_menu_item_title_system_unlock_entry', '_bx_forum_menu_item_title_unlock_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'unlock\', {content_id});', '', 'unlock', '', 2147483647, 1, 0, 3),
('bx_forum_view_more', @sName, 'unhide-discussion', '_bx_forum_menu_item_title_system_unhide_entry', '_bx_forum_menu_item_title_unhide_entry', 'javascript:void(0);', '{js_object}.updateStatus(this, \'unhide\', {content_id});', '', 'eye', '', 2147483647, 1, 0, 4),
('bx_forum_view_more', @sName, 'edit-discussion', '_bx_forum_menu_item_title_system_edit_entry', '_bx_forum_menu_item_title_edit_entry', 'page.php?i=edit-discussion&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 5),
('bx_forum_view_more', @sName, 'delete-discussion', '_bx_forum_menu_item_title_system_delete_entry', '_bx_forum_menu_item_title_delete_entry', 'page.php?i=delete-discussion&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 6);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_notifications' AND `name` IN('notifications-forum');
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_stats' AND `name` IN('profile-stats-unreplied-discussions');

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_stats' AND `name` IN('profile-stats-discussions');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', @sName, 'profile-stats-discussions', '_bx_forum_menu_item_title_system_discussions', '_bx_forum_menu_item_title_discussions', 'page.php?i=discussions-author&profile_id={member_id}', '', '', 'comments-o col-blue2', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:19:"get_discussions_num";}', '', 2147483646, 1, 0, 2);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_forum_favorite';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_mode`, `sorting_fields`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_favorite', 'Sql', 'SELECT `bx_forum_discussions`.*, `bx_forum_cmts`.`cmt_text` AS `cmt_text` FROM `bx_forum_discussions` LEFT JOIN `bx_forum_cmts` ON (`bx_forum_cmts`.`cmt_id` = `bx_forum_discussions`.`lr_comment_id`) %s WHERE 1 %s', 'bx_forum_discussions', 'id', 'lr_timestamp', '', 10, NULL, 'start', '', 'title,text,cmt_text', 'auto', '', 2147483647, 'BxForumGrid', 'modules/boonex/forum/classes/BxForumGrid.php');


DELETE FROM `sys_grid_fields` WHERE `object`='bx_forum_favorite';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_forum_favorite', 'author', '', '10%', '', 1),
('bx_forum_favorite', 'lr_timestamp', '', '85%', '', 2),
('bx_forum_favorite', 'comments', '', '5%', '', 3);


-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object`='bx_forum_subscribers';
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_subscribers', 'bx_forum_subscribers', 'one-way', '', '');


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldTitle`='title' WHERE `Name`=@sName;


-- VOTES
DELETE FROM `sys_objects_vote` WHERE `Name`=@sName;
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
(@sName, 'bx_forum_votes', 'bx_forum_votes_track', '604800', '1', '1', '0', '1', 'bx_forum_discussions', 'id', 'author', 'rate', 'votes', '', '');


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`=@sName;
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
(@sName, 'bx_forum_favorites_track', '1', '1', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_discussions', 'id', 'author', 'favorites', '', '');