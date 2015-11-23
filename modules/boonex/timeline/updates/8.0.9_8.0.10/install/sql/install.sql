ALTER TABLE `bx_timeline_handlers` ADD `group` varchar(64) NOT NULL default '' AFTER `id`;

UPDATE `bx_timeline_handlers` SET `group`='common_post' WHERE `alert_unit`='timeline_common_post' AND `alert_action`='' LIMIT 1;
UPDATE `bx_timeline_handlers` SET `group`='common_share' WHERE `alert_unit`='timeline_common_share' AND `alert_action`='' LIMIT 1;
UPDATE `bx_timeline_handlers` SET `group`='profile' WHERE `alert_unit`='profile' AND `alert_action`='delete' LIMIT 1;
UPDATE `bx_timeline_handlers` SET `group`=CONCAT(`alert_unit`, '_', IF(`alert_action`='doVote' OR `alert_action`='undoVote', 'vote', IF(`alert_action`='commentPost' OR `alert_action`='commentRemoved', 'comment', 'object'))) WHERE `group`='';

ALTER TABLE `bx_timeline_handlers` DROP INDEX `handler`;
ALTER TABLE `bx_timeline_handlers` ADD UNIQUE `alert` (`alert_unit`, `alert_action`);
ALTER TABLE `bx_timeline_handlers` ADD UNIQUE `handler` (`group`, `type`);


ALTER TABLE `bx_timeline_links` ADD `media_id` int(11) NOT NULL DEFAULT '0' AFTER `profile_id`;


ALTER TABLE `bx_timeline_votes_track` ADD `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;