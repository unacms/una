ALTER TABLE `bx_notifications_events` ADD `subobject_id` int(11) NOT NULL default '0' AFTER `object_privacy_view`;
ALTER TABLE `bx_notifications_events` ADD `processed` tinyint(4) NOT NULL default '0' AFTER `date`;


ALTER TABLE `bx_notifications_handlers` ADD `group` varchar(64) NOT NULL default '' AFTER `id`;

UPDATE `bx_notifications_handlers` SET `group`='profile' WHERE `alert_unit`='profile' AND `alert_action`='delete' LIMIT 1;
UPDATE `bx_notifications_handlers` SET `group`=CONCAT(`alert_unit`, '_', IF(`alert_action`='doVote' OR `alert_action`='undoVote', 'vote', IF(`alert_action`='commentPost' OR `alert_action`='commentRemoved', 'comment', 'object'))) WHERE `group`='';

ALTER TABLE `bx_notifications_handlers` DROP INDEX `handler`;
ALTER TABLE `bx_notifications_handlers` ADD UNIQUE `alert` (`alert_unit`, `alert_action`);
ALTER TABLE `bx_notifications_handlers` ADD UNIQUE `handler` (`group`, `type`);