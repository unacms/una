SET @sName = 'bx_notifications';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_notifications_enable_group_events', 'bx_notifications_enable_comment_post_ext');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_enable_group_events', 'on', @iCategId, '_bx_ntfs_option_enable_group_events', 'checkbox', '', '', '', '', 11), 
('bx_notifications_enable_comment_post_ext', '', @iCategId, '_bx_ntfs_option_enable_comment_post_ext', 'checkbox', '', '', '', '', 40);


-- MENUS
UPDATE `sys_menu_items` SET `onclick`='bx_menu_slide(''bx_notifications_preview'', $(this).parents(''ul:first''), ''site'', {id:{value:''bx_notifications_preview'', force:1}, pointer:{align:''right''}, cssClass: ''''});', `hidden_on`='9' WHERE `set_name`='sys_toolbar_member' AND `name`='notifications-preview';

UPDATE `sys_menu_items` SET `hidden_on`='6', `active`='1' WHERE `set_name`='sys_account_notifications' AND `name`='notifications-notifications';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='comment' AND `action` IN ('added', 'deleted') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('comment', 'added', @iHandler),
('comment', 'deleted', @iHandler);
