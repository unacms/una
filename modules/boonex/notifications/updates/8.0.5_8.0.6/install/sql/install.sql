ALTER TABLE `bx_notifications_events` ADD `subobject_id` int(11) NOT NULL default '0' AFTER `object_privacy_view`;
ALTER TABLE `bx_notifications_events` ADD `processed` tinyint(4) NOT NULL default '0' AFTER `date`;

ALTER TABLE `bx_notifications_handlers` ADD `group` varchar(64) NOT NULL default '' AFTER `id`;

ALTER TABLE `bx_notifications_handlers` DROP INDEX `handler`;
ALTER TABLE `bx_notifications_handlers` ADD UNIQUE `alert` (`alert_unit`, `alert_action`);
ALTER TABLE `bx_notifications_handlers` ADD UNIQUE `handler` (`group`, `type`);

UPDATE `bx_notifications_handlers` SET `group`='profile' WHERE `alert_unit`='profile' AND `alert_action`='delete' LIMIT 1;