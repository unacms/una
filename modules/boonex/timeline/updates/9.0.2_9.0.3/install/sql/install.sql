SET @sName = 'bx_timeline';


-- TABLES
UPDATE `bx_timeline_events` SET `type`='timeline_common_repost' WHERE `type`='timeline_common_share';

UPDATE `bx_timeline_handlers` SET `group`='common_repost', `alert_unit`='timeline_common_repost' WHERE `group`='common_share' AND `alert_unit`='timeline_common_share';

ALTER TABLE `bx_timeline_photos` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_timeline_photos_processed` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_timeline_videos` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_timeline_videos_processed` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;

CREATE TABLE IF NOT EXISTS `bx_timeline_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;