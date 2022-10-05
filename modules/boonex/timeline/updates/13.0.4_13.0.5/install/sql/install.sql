SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_events2users` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE `view` (`user_id`, `event_id`)
);
