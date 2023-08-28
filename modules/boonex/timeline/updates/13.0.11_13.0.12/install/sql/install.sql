SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_events_slice` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL default '0',
  `system` tinyint(4) NOT NULL default '1',
  `type` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL default '0',
  `object_owner_id` int(11) NOT NULL default '0',
  `object_privacy_view` varchar(16) NOT NULL default '3',
  `object_cf` int(11) NOT NULL default '1',
  `content` text NOT NULL,
  `source` varchar(32) NOT NULL default '',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` text NOT NULL,
  `views` int(11) unsigned NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) unsigned NOT NULL default '0',
  `rrate` float NOT NULL default '0',
  `rvotes` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `sc_up` int(11) NOT NULL default '0',
  `sc_down` int(11) NOT NULL default '0',
  `comments` int(11) unsigned NOT NULL default '0',
  `reports` int(11) unsigned NOT NULL default '0',
  `reposts` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `published` int(11) NOT NULL default '0',
  `reacted` int(11) NOT NULL default '0',
  `status` enum ('active', 'awaiting', 'failed', 'hidden', 'deleted') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  `active` tinyint(4) NOT NULL default '1',
  `pinned` int(11) NOT NULL default '0',
  `sticked` int(11) NOT NULL default '0',
  `promoted` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `object_id` (`object_id`),
  FULLTEXT KEY `search_fields` (`title`, `description`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_ef_photos` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_ef_videos` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
);

CREATE TABLE IF NOT EXISTS `bx_timeline_ef_files` (
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
);


-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:4:"1000";s:1:"h";s:4:"1000";}' WHERE `transcoder_object`='bx_timeline_photos_big';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:4:"1000";s:1:"h";s:4:"1000";}' WHERE `transcoder_object`='bx_timeline_videos_photo_big';


-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_timeline_post_edit' AND `input_name`='object_privacy_view';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_timeline_post_edit', 'object_privacy_view', 2147483647, 1, 10);


-- VIEWS
UPDATE `sys_objects_view` SET `module`='bx_timeline' WHERE `name`='bx_timeline';


-- VOTES
UPDATE `sys_objects_vote` SET `Module`='bx_timeline' WHERE `Name` IN ('bx_timeline', 'bx_timeline_reactions');
