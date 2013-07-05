--
-- Table structure for table `[db_prefix]updated`
--

CREATE TABLE IF NOT EXISTS `[db_prefix]updated` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `author_id` int(11) unsigned NOT NULL default '0',  
  `caption` varchar(100) NOT NULL default '',
  `snippet` text NOT NULL,
  `content` mediumtext NOT NULL,
  `when` int(11) NOT NULL default '0',
  `uri` varchar(100) NOT NULL default '',
  `tags` varchar(255) NOT NULL default '',
  `categories` varchar(255) NOT NULL default '',
  `comment` tinyint(0) NOT NULL default '0',
  `vote` tinyint(0) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `featured` tinyint(4) NOT NULL default '0',
  `rate` int(11) NOT NULL default '0',
  `rate_count` int(11) NOT NULL default '0',
  `view_count` int(11) NOT NULL default '0',
  `cmts_count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uri` (`uri`),
  FULLTEXT KEY `search_group` (`caption`, `content`, `tags`, `categories`),
  FULLTEXT KEY `search_caption` (`caption`),
  FULLTEXT KEY `search_content` (`content`),
  FULLTEXT KEY `search_tags` (`tags`),
  FULLTEXT KEY `search_categories` (`categories`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;