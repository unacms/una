SET @sName = 'bx_notifications';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_notifications_events2users` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  `clicked` tinyint(4) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE `event` (`user_id`, `event_id`)
);
