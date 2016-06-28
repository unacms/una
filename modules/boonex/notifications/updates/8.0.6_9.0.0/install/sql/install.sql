-- TABLES
ALTER TABLE `bx_notifications_events` CHANGE `object_privacy_view` `object_privacy_view` VARCHAR( 32 ) NOT NULL DEFAULT '3';

DELETE FROM `bx_notifications_handlers` WHERE `alert_unit`='sys_profiles_subscriptions' AND `alert_action` IN('connection_added', 'connection_removed');
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES
('subscription', 'insert', 'sys_profiles_subscriptions', 'connection_added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:22:"get_notifications_post";s:12:"module_class";s:23:"TemplServiceConnections";}'),
('subscription', 'delete', 'sys_profiles_subscriptions', 'connection_removed', '');