SET @sName = 'bx_notifications';


-- TABLES
-- Comments Actions (Like, Reaction, Score Up\Down)
SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `group`='sys_cmts_vote' AND `type`='insert' AND `alert_unit`='sys_cmts' AND `alert_action`='doVote' LIMIT 1);
DELETE FROM `bx_notifications_settings` WHERE `handler_id`=@iHandlerId;
DELETE FROM `bx_notifications_handlers` WHERE `id`=@iHandlerId;

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

DELETE FROM `bx_notifications_handlers` WHERE `group`='sys_cmts_vote' AND `type`='delete' AND `alert_unit`='sys_cmts' AND `alert_action`='undoVote';
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('sys_cmts_vote', 'delete', 'sys_cmts', 'undoVote', '');

SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `group`='sys_cmts_reaction' AND `type`='insert' AND `alert_unit`='sys_cmts_reactions' AND `alert_action`='doVote' LIMIT 1);
DELETE FROM `bx_notifications_settings` WHERE `handler_id`=@iHandlerId;
DELETE FROM `bx_notifications_handlers` WHERE `id`=@iHandlerId;

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

DELETE FROM `bx_notifications_handlers` WHERE `group`='sys_cmts_reaction' AND `type`='delete' AND `alert_unit`='sys_cmts_reactions' AND `alert_action`='undoVote';
INSERT INTO `bx_notifications_handlers`(`group`, `type`, `alert_unit`, `alert_action`, `content`) VALUES 
('sys_cmts_reaction', 'delete', 'sys_cmts_reactions', 'undoVote', '');

SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `group`='sys_cmts_score_up' AND `type`='insert' AND `alert_unit`='sys_cmts' AND `alert_action`='doVoteUp' LIMIT 1);
DELETE FROM `bx_notifications_settings` WHERE `handler_id`=@iHandlerId;
DELETE FROM `bx_notifications_handlers` WHERE `id`=@iHandlerId;

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

SET @iHandlerId = (SELECT `id` FROM `bx_notifications_handlers` WHERE `group`='sys_cmts_score_down' AND `type`='insert' AND `alert_unit`='sys_cmts' AND `alert_action`='doVoteDown' LIMIT 1);
DELETE FROM `bx_notifications_settings` WHERE `handler_id`=@iHandlerId;
DELETE FROM `bx_notifications_handlers` WHERE `id`=@iHandlerId;

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
