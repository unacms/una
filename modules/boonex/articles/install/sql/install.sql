SET @sName = 'bx_articles';


--
-- Studio page and widget.
--
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_articles_adm_page_cpt', '_articles_adm_page_cpt', 'bx_articles@modules/boonex/articles/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id`=@iParentPageId);
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, CONCAT('{url_studio}module.php?name=', @sName), '', 'bx_articles@modules/boonex/articles/|std-wi.png', '_articles_adm_wgt_cpt', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(NOT ISNULL(@iParentPageOrder), @iParentPageOrder + 1, 1));


--
-- Table structure for table `bx_arl_entries`
--
CREATE TABLE IF NOT EXISTS `bx_arl_entries` (
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

--
-- Table structure for table `bx_arl_comments`
--

CREATE TABLE IF NOT EXISTS `bx_arl_comments` (
  `cmt_id` int(11) NOT NULL auto_increment,
  `cmt_parent_id` int(11) NOT NULL default '0',
  `cmt_object_id` int(11) NOT NULL default '0',
  `cmt_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL default '0',
  `cmt_rate` int(11) NOT NULL default '0',
  `cmt_rate_count` int(11) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Table structure for table `bx_arl_comments_track`
--

CREATE TABLE IF NOT EXISTS `bx_arl_comments_track` (
  `cmt_system_id` int(11) NOT NULL default '0',
  `cmt_id` int(11) NOT NULL default '0',
  `cmt_rate` tinyint(4) NOT NULL default '0',
  `cmt_rate_author_id` int(10) unsigned NOT NULL default '0',
  `cmt_rate_author_nip` int(11) unsigned NOT NULL default '0',
  `cmt_rate_ts` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cmt_system_id`,`cmt_id`,`cmt_rate_author_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bx_arl_voting`
--
CREATE TABLE `bx_arl_voting` (
  `arl_id` bigint(8) NOT NULL default '0',
  `arl_rating_count` int(11) NOT NULL default '0',
  `arl_rating_sum` int(11) NOT NULL default '0',
  UNIQUE KEY `arl_id` (`arl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bx_arl_voting_track`
--
CREATE TABLE `bx_arl_voting_track` (
  `arl_id` bigint(8) NOT NULL default '0',
  `arl_ip` varchar(20) default NULL,
  `arl_date` datetime default NULL,
  KEY `arl_ip` (`arl_ip`,`arl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `bx_arl_views_track`
--
CREATE TABLE IF NOT EXISTS `bx_arl_views_track` (
  `id` int(10) unsigned NOT NULL,
  `viewer` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `ts` int(10) unsigned NOT NULL,
  KEY `id` (`id`,`viewer`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `sys_categories` (`Category`, `ID`, `Type`, `Owner`, `Status`) VALUES 
('Default', '0', @sName, '0', 'active'),
('BoonEx Products', '0', @sName, '0', 'active'),
('Some Useful Info', '0', @sName, '0', 'active');


INSERT INTO `sys_sbs_types`(`unit`, `action`, `template`, `params`) VALUES
(@sName, '', '', 'return BxDolService::call(\'articles\', \'get_subscription_params\', array($arg1, $arg2, $arg3));'),
(@sName, 'commentPost', 't_sbsArticlesComments', 'return BxDolService::call(\'articles\', \'get_subscription_params\', array($arg1, $arg2, $arg3));'),
(@sName, 'rate', 't_sbsArticlesRates', 'return BxDolService::call(\'articles\', \'get_subscription_params\', array($arg1, $arg2, $arg3));');