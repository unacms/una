SET @sName = 'bx_notifications';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_notifications_events` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `type` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `object_id` text NOT NULL,
  `object_owner_id` int(11) NOT NULL default '0',
  `object_privacy_view` varchar(32) NOT NULL default '3',
  `subobject_id` int(11) NOT NULL default '0',
  `content` text NOT NULL,
  `allow_view_event_to` varchar(32) NOT NULL default '3',
  `date` int(11) NOT NULL default '0',
  `processed` tinyint(4) NOT NULL default '0',
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `object_owner_id` (`object_owner_id`)
);

CREATE TABLE IF NOT EXISTS `bx_notifications_events2users` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
);

CREATE TABLE IF NOT EXISTS `bx_notifications_handlers` (
  `id` int(11) NOT NULL auto_increment,
  `group` varchar(64) NOT NULL default '',
  `type` enum('insert','update','delete') NOT NULL DEFAULT 'insert',
  `alert_unit` varchar(64) NOT NULL default '',
  `alert_action` varchar(64) NOT NULL default '',
  `content` text NOT NULL,
  `privacy` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE `handler` (`group`, `type`),
  UNIQUE `alert` (`alert_unit`, `alert_action`)
);

CREATE TABLE IF NOT EXISTS `bx_notifications_settings` (
  `id` int(11) NOT NULL auto_increment,
  `group` varchar(64) NOT NULL default '',
  `handler_id` int(11) NOT NULL DEFAULT '0',
  `delivery` enum('site','email','push') NOT NULL DEFAULT 'site',
  `type` enum('personal','follow_member','follow_context','other') NOT NULL DEFAULT 'personal',
  `title` varchar(64) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '1',
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
  UNIQUE `setting` (`setting_id`, `user_id`)
);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('profile', 'delete', 'profile', 'delete', '');

-- Mentions
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('mention', 'insert', 'meta_mention', 'added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:30:"get_notifications_post_mention";s:12:"module_class";s:20:"TemplServiceMetatags";}');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('mention', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_mention_added_personal', 1),
('mention', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_mention_added_personal', 1),
('mention', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_mention_added_personal', 1);

-- Friendship
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('friendship', 'insert', 'sys_profiles_friends', 'connection_added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:33:"get_notifications_post_friendship";s:12:"module_class";s:23:"TemplServiceConnections";}');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('friendship', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_friendship_added_personal', 2),
('friendship', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_friendship_added_personal', 2),
('friendship', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_friendship_added_personal', 2);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('friendship', 'delete', 'sys_profiles_friends', 'connection_removed', '');

-- Subscription
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('subscription', 'insert', 'sys_profiles_subscriptions', 'connection_added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:22:"get_notifications_post";s:12:"module_class";s:23:"TemplServiceConnections";}');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('subscription', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_subscription_added_personal', 3),
('subscription', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_subscription_added_personal', 3),
('subscription', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_subscription_added_personal', 3);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('subscription', 'delete', 'sys_profiles_subscriptions', 'connection_removed', '');

-- Comments
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('comment', 'insert', 'comment', 'added', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:31:"get_notifications_comment_added";s:12:"module_class";s:17:"TemplCmtsServices";}');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('comment', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_comment_added_personal', 1),
('comment', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_comment_added_personal', 1),
('comment', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_comment_added_personal', 1);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('comment', 'delete', 'comment', 'deleted', '');

-- Comments Actions (Like, Reaction, Score Up\Down)
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`, `privacy`) VALUES 
('sys_cmts_vote', 'insert', 'sys_cmts', 'doVote', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:22:"get_notifications_vote";s:12:"module_class";s:17:"TemplCmtsServices";}', '');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('vote', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_doVote_personal', 4),
('vote', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_doVote_personal', 4),
('vote', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_doVote_personal', 4),

('vote', @iHandlerId, 'site', 'follow_member', '_bx_ntfs_alert_action_doVote_follow_member', 1),
('vote', @iHandlerId, 'email', 'follow_member', '_bx_ntfs_alert_action_doVote_follow_member', 1),
('vote', @iHandlerId, 'push', 'follow_member', '_bx_ntfs_alert_action_doVote_follow_member', 1),

('vote', @iHandlerId, 'site', 'follow_context', '_bx_ntfs_alert_action_doVote_follow_context', 1),
('vote', @iHandlerId, 'email', 'follow_context', '_bx_ntfs_alert_action_doVote_follow_context', 1),
('vote', @iHandlerId, 'push', 'follow_context', '_bx_ntfs_alert_action_doVote_follow_context', 1);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('sys_cmts_vote', 'delete', 'sys_cmts', 'undoVote', '');

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`, `privacy`) VALUES 
('sys_cmts_reaction', 'insert', 'sys_cmts_reactions', 'doVote', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:26:"get_notifications_reaction";s:12:"module_class";s:17:"TemplCmtsServices";}', '');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('vote', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_doVote_personal', 5),
('vote', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_doVote_personal', 5),
('vote', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_doVote_personal', 5),

('vote', @iHandlerId, 'site', 'follow_member', '_bx_ntfs_alert_action_doVote_follow_member', 2),
('vote', @iHandlerId, 'email', 'follow_member', '_bx_ntfs_alert_action_doVote_follow_member', 2),
('vote', @iHandlerId, 'push', 'follow_member', '_bx_ntfs_alert_action_doVote_follow_member', 2),

('vote', @iHandlerId, 'site', 'follow_context', '_bx_ntfs_alert_action_doVote_follow_context', 2),
('vote', @iHandlerId, 'email', 'follow_context', '_bx_ntfs_alert_action_doVote_follow_context', 2),
('vote', @iHandlerId, 'push', 'follow_context', '_bx_ntfs_alert_action_doVote_follow_context', 2);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('sys_cmts_reaction', 'delete', 'sys_cmts_reactions', 'undoVote', '');

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`, `privacy`) VALUES 
('sys_cmts_score_up', 'insert', 'sys_cmts', 'doVoteUp', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:26:"get_notifications_score_up";s:12:"module_class";s:17:"TemplCmtsServices";}', '');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('score_up', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_doVoteUp_personal', 6),
('score_up', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_doVoteUp_personal', 6),
('score_up', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_doVoteUp_personal', 6),

('score_up', @iHandlerId, 'site', 'follow_member', '_bx_ntfs_alert_action_doVoteUp_follow_member', 3),
('score_up', @iHandlerId, 'email', 'follow_member', '_bx_ntfs_alert_action_doVoteUp_follow_member', 3),
('score_up', @iHandlerId, 'push', 'follow_member', '_bx_ntfs_alert_action_doVoteUp_follow_member', 3),

('score_up', @iHandlerId, 'site', 'follow_context', '_bx_ntfs_alert_action_doVoteUp_follow_context', 3),
('score_up', @iHandlerId, 'email', 'follow_context', '_bx_ntfs_alert_action_doVoteUp_follow_context', 3),
('score_up', @iHandlerId, 'push', 'follow_context', '_bx_ntfs_alert_action_doVoteUp_follow_context', 3);

INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`, `privacy`) VALUES 
('sys_cmts_score_down', 'insert', 'sys_cmts', 'doVoteDown', 'a:3:{s:11:"module_name";s:6:"system";s:13:"module_method";s:28:"get_notifications_score_down";s:12:"module_class";s:17:"TemplCmtsServices";}', '');
SET @iHandlerId = LAST_INSERT_ID();

INSERT INTO `bx_notifications_settings`(`group`, `handler_id`, `delivery`, `type`, `title`, `order`) VALUES
('score_down', @iHandlerId, 'site', 'personal', '_bx_ntfs_alert_action_doVoteDown_personal', 7),
('score_down', @iHandlerId, 'email', 'personal', '_bx_ntfs_alert_action_doVoteDown_personal', 7),
('score_down', @iHandlerId, 'push', 'personal', '_bx_ntfs_alert_action_doVoteDown_personal', 7),

('score_down', @iHandlerId, 'site', 'follow_member', '_bx_ntfs_alert_action_doVoteDown_follow_member', 4),
('score_down', @iHandlerId, 'email', 'follow_member', '_bx_ntfs_alert_action_doVoteDown_follow_member', 4),
('score_down', @iHandlerId, 'push', 'follow_member', '_bx_ntfs_alert_action_doVoteDown_follow_member', 4),

('score_down', @iHandlerId, 'site', 'follow_context', '_bx_ntfs_alert_action_doVoteDown_follow_context', 4),
('score_down', @iHandlerId, 'email', 'follow_context', '_bx_ntfs_alert_action_doVoteDown_follow_context', 4),
('score_down', @iHandlerId, 'push', 'follow_context', '_bx_ntfs_alert_action_doVoteDown_follow_context', 4);

CREATE TABLE IF NOT EXISTS `bx_notifications_queue` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `delivery` varchar(64) NOT NULL default '',
  `content` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_ntfs', '_bx_ntfs', 'bx_notifications@modules/boonex/notifications/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, 'extensions', '{url_studio}module.php?name=bx_notifications', '', 'bx_notifications@modules/boonex/notifications/|std-icon.svg', '_bx_ntfs', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
