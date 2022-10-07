SET @sName = 'bx_notifications';


-- TABLES
UPDATE `bx_notifications_handlers` SET `priority`='1' WHERE `alert_unit`='meta_mention' AND `alert_action`='added';

SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `alert_unit`='comment' AND `alert_action`='added' LIMIT 1);
DELETE FROM `bx_notifications_handlers` WHERE `id`=@iHandlerId;
DELETE FROM `bx_notifications_settings` WHERE `handler_id`=@iHandlerId;

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('comment', 'insert', 'comment', 'added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:31:"get_notifications_comment_added";s:12:"module_class";s:17:"TemplCmtsServices";}');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('comment', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_comment_added_personal', 1),
('comment', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_comment_added_personal', 1),
('comment', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_comment_added_personal', 1);

DELETE FROM `bx_notifications_handlers` WHERE `alert_unit`='comment' AND `alert_action`='deleted';
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('comment', 'delete', 'comment', 'deleted', '');
