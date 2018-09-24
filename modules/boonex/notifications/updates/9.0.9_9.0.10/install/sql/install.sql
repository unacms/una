SET @sName = 'bx_notifications';


-- TABLES
ALTER TABLE `bx_notifications_events` CHANGE `owner_id` `owner_id` int(11) NOT NULL default '0';
ALTER TABLE `bx_notifications_events` CHANGE `object_owner_id` `object_owner_id` int(11) NOT NULL default '0';

CREATE TABLE IF NOT EXISTS `bx_notifications_settings` (
  `id` int(11) NOT NULL auto_increment,
  `group` varchar(64) NOT NULL default '',
  `handler_id` int(11) NOT NULL DEFAULT '0',
  `delivery` enum('site','email','push') NOT NULL DEFAULT 'site',
  `type` enum('personal','follow_member','follow_context','other') NOT NULL DEFAULT 'personal',
  `title` varchar(64) NOT NULL default '',
  `active` tinyint(4) NOT NULL default '1',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE `setting` (`handler_id`, `type`, `delivery`)
);

CREATE TABLE IF NOT EXISTS `bx_notifications_settings2users` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `setting_id` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY (`id`),
  UNIQUE `setting` (`user_id`, `setting_id`)
);

SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `group`='mention' AND `alert_unit`='meta_mention' AND `alert_action`='added' LIMIT 1);
DELETE FROM `bx_notifications_settings` WHERE `group`='mention' AND `handler_id`=@iHandlerId;
INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('mention', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_mention_added_personal', 1),
('mention', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_mention_added_personal', 1),
('mention', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_mention_added_personal', 1);

SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `group`='friendship' AND `alert_unit`='sys_profiles_friends' AND `alert_action`='connection_added' LIMIT 1);
DELETE FROM `bx_notifications_settings` WHERE `group`='friendship' AND `handler_id`=@iHandlerId;
INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('friendship', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_friendship_added_personal', 2),
('friendship', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_friendship_added_personal', 2),
('friendship', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_friendship_added_personal', 2);

SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `group`='subscription' AND `alert_unit`='sys_profiles_subscriptions' AND `alert_action`='connection_added' LIMIT 1);
DELETE FROM `bx_notifications_settings` WHERE `group`='subscription' AND `handler_id`=@iHandlerId;
INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('subscription', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_subscription_added_personal', 3),
('subscription', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_subscription_added_personal', 3),
('subscription', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_subscription_added_personal', 3);
