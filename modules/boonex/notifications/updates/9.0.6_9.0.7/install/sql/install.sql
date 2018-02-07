-- TABLES
DELETE FROM `bx_notifications_handlers` WHERE `group`='mention';
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES
('mention', 'insert', 'meta_mention', 'added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:30:"get_notifications_post_mention";s:12:"module_class";s:20:"TemplServiceMetatags";}');

-- TABLE: bx_notifications_events

ALTER TABLE `bx_notifications_events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_notifications_events` CHANGE `type` `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_events` CHANGE `action` `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_events` CHANGE `object_id` `object_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_events` CHANGE `object_privacy_view` `object_privacy_view` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_events` CHANGE `content` `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_events` CHANGE `allow_view_event_to` `allow_view_event_to` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_notifications_events`;
OPTIMIZE TABLE `bx_notifications_events`;


-- TABLE: bx_notifications_events2users

ALTER TABLE `bx_notifications_events2users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_notifications_events2users`;
OPTIMIZE TABLE `bx_notifications_events2users`;


-- TABLE: bx_notifications_handlers

ALTER TABLE `bx_notifications_handlers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_notifications_handlers` CHANGE `group` `group` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_handlers` CHANGE `alert_unit` `alert_unit` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_handlers` CHANGE `alert_action` `alert_action` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_handlers` CHANGE `content` `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_notifications_handlers` CHANGE `privacy` `privacy` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_notifications_handlers`;
OPTIMIZE TABLE `bx_notifications_handlers`;
