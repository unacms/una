SET @sName = 'bx_notifications';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_notifications_queue` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `delivery` varchar(64) NOT NULL default '',
  `content` text NOT NULL,
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);


-- CUSTOM
UPDATE `bx_notifications_events` SET `content`=REPLACE(`content`, 's:8:"lang_key";s:34:"_bx_timeline_ntfs_txt_object_added";', 's:8:"lang_key";s:0:"";') WHERE `content` LIKE '%_bx_timeline_ntfs_txt_object_added%';
