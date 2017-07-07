SET @sName = 'bx_notifications';


-- TABLES
DELETE FROM `bx_notifications_handlers` WHERE `group`='friendship' AND `type` IN ('insert', 'delete');
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES
('friendship', 'insert', 'sys_profiles_friends', 'connection_added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:33:"get_notifications_post_friendship";s:12:"module_class";s:23:"TemplServiceConnections";}'),
('friendship', 'delete', 'sys_profiles_friends', 'connection_removed', '');
