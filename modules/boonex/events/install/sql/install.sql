-- create tables
CREATE TABLE IF NOT EXISTS `[db_prefix]main` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `Title` varchar(100) NOT NULL default '',
  `EntryUri` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Status` enum('approved','pending') NOT NULL default 'approved',
  `Country` varchar(2) NOT NULL default 'US',
  `City` varchar(50) NOT NULL default '',
  `Place` varchar(100) NOT NULL default '',
  `PrimPhoto` int(11) NOT NULL,
  `Date` int(11) NOT NULL,
  `EventStart` int(11) NOT NULL default '0',
  `EventEnd` int(11) NOT NULL default '0',
  `ResponsibleID` int(10) unsigned NOT NULL default '0',
  `EventMembershipFilter` varchar(100) NOT NULL default '',  
  `Tags` varchar(255) NOT NULL default '',
  `Categories` text NOT NULL,
  `Views` int(11) NOT NULL,
  `Rate` float NOT NULL,
  `RateCount` int(11) NOT NULL,
  `CommentsCount` int(11) NOT NULL,
  `FansCount` int(11) NOT NULL,
  `Featured` tinyint(4) NOT NULL,
  `allow_view_event_to` int(11) NOT NULL,
  `allow_view_participants_to` varchar(16) NOT NULL,
  `allow_comment_to` varchar(16) NOT NULL,
  `allow_rate_to` varchar(16) NOT NULL,
  `allow_join_to` int(11) NOT NULL,
  `allow_post_in_forum_to` varchar(16) NOT NULL,
  `JoinConfirmation` tinyint(4) NOT NULL default '0',
  `allow_upload_photos_to` varchar(16) NOT NULL,
  `allow_upload_videos_to` varchar(16) NOT NULL,
  `allow_upload_sounds_to` varchar(16) NOT NULL,
  `allow_upload_files_to` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `EntryUri` (`EntryUri`),
  KEY `ResponsibleID` (`ResponsibleID`),
  KEY `EventStart` (`EventStart`),
  KEY `Date` (`Date`),
  FULLTEXT KEY `Title` (`Title`,`Description`,`City`,`Place`,`Tags`,`Categories`),
  FULLTEXT KEY `Tags` (`Tags`),
  FULLTEXT KEY `Categories` (`Categories`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]images` (
  `entry_id` int(10) unsigned NOT NULL,
  `media_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `entry_id` (`entry_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]videos` (
  `entry_id` int(10) unsigned NOT NULL,
  `media_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `entry_id` (`entry_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]sounds` (
  `entry_id` int(10) unsigned NOT NULL,
  `media_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `entry_id` (`entry_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]files` (
  `entry_id` int(10) unsigned NOT NULL,
  `media_id` int(10) unsigned NOT NULL,
  UNIQUE KEY `entry_id` (`entry_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]participants` (
  `id_entry` int(10) unsigned NOT NULL,
  `id_profile` int(10) unsigned NOT NULL,
  `when` int(10) unsigned NOT NULL,
  `confirmed` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id_entry`,`id_profile`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]admins` (
  `id_entry` int(10) unsigned NOT NULL,
  `id_profile` int(10) unsigned NOT NULL,
  `when` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_entry`, `id_profile`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]rating` (
  `gal_id` int(10) unsigned NOT NULL default '0',
  `gal_rating_count` int( 11 ) NOT NULL default '0',
  `gal_rating_sum` int( 11 ) NOT NULL default '0',
  UNIQUE KEY `gal_id` (`gal_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `[db_prefix]rating_track` (
  `gal_id` int(10) unsigned NOT NULL default '0',
  `gal_ip` varchar( 20 ) default NULL,
  `gal_date` datetime default NULL,
  KEY `gal_ip` (`gal_ip`, `gal_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `[db_prefix]cmts` (
  `cmt_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
  `cmt_parent_id` int( 11 ) NOT NULL default '0',
  `cmt_object_id` int( 12 ) NOT NULL default '0',
  `cmt_author_id` int( 10 ) unsigned NOT NULL default '0',
  `cmt_text` text NOT NULL ,
  `cmt_mood` tinyint( 4 ) NOT NULL default '0',
  `cmt_rate` int( 11 ) NOT NULL default '0',
  `cmt_rate_count` int( 11 ) NOT NULL default '0',
  `cmt_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `cmt_replies` int( 11 ) NOT NULL default '0',
  PRIMARY KEY ( `cmt_id` ),
  KEY `cmt_object_id` (`cmt_object_id` , `cmt_parent_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `[db_prefix]cmts_track` (
  `cmt_system_id` int( 11 ) NOT NULL default '0',
  `cmt_id` int( 11 ) NOT NULL default '0',
  `cmt_rate` tinyint( 4 ) NOT NULL default '0',
  `cmt_rate_author_id` int( 10 ) unsigned NOT NULL default '0',
  `cmt_rate_author_nip` int( 11 ) unsigned NOT NULL default '0',
  `cmt_rate_ts` int( 11 ) NOT NULL default '0',
  PRIMARY KEY (`cmt_system_id` , `cmt_id` , `cmt_rate_author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `[db_prefix]views_track` (
  `id` int(10) unsigned NOT NULL,
  `viewer` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `ts` int(10) unsigned NOT NULL,
  KEY `id` (`id`,`viewer`,`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- create forum tables

CREATE TABLE `[db_prefix]forum` (
  `forum_id` int(10) unsigned NOT NULL auto_increment,
  `forum_uri` varchar(255) NOT NULL default '',
  `cat_id` int(11) NOT NULL default '0',
  `forum_title` varchar(255) default NULL,
  `forum_desc` varchar(255) NOT NULL default '',
  `forum_posts` int(11) NOT NULL default '0',
  `forum_topics` int(11) NOT NULL default '0',
  `forum_last` int(11) NOT NULL default '0',
  `forum_type` enum('public','private') NOT NULL default 'public',
  `forum_order` int(11) NOT NULL default '0',
  `entry_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`forum_id`),
  KEY `cat_id` (`cat_id`),
  KEY `forum_uri` (`forum_uri`),
  KEY `entry_id` (`entry_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_cat` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_uri` varchar(255) NOT NULL default '',
  `cat_name` varchar(255) default NULL,
  `cat_icon` varchar(32) NOT NULL default '',
  `cat_order` float NOT NULL default '0',
  `cat_expanded` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`cat_id`),
  KEY `cat_order` (`cat_order`),
  KEY `cat_uri` (`cat_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `[db_prefix]forum_cat` (`cat_id`, `cat_uri`, `cat_name`, `cat_icon`, `cat_order`) VALUES 
(1, 'Events', 'Events', '', 64);

CREATE TABLE `[db_prefix]forum_flag` (
  `user` varchar(32) NOT NULL default '',
  `topic_id` int(11) NOT NULL default '0',
  `when` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`,`topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_post` (
  `post_id` int(10) unsigned NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL default '0',
  `forum_id` int(11) NOT NULL default '0',
  `user` varchar(32) NOT NULL default '0',
  `post_text` mediumtext NOT NULL,
  `when` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `hidden` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`post_id`),
  KEY `topic_id` (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `user` (`user`),
  KEY `when` (`when`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_topic` (
  `topic_id` int(10) unsigned NOT NULL auto_increment,
  `topic_uri` varchar(255) NOT NULL default '',
  `forum_id` int(11) NOT NULL default '0',
  `topic_title` varchar(255) NOT NULL default '',
  `when` int(11) NOT NULL default '0',
  `topic_posts` int(11) NOT NULL default '0',
  `first_post_user` varchar(32) NOT NULL default '0',
  `first_post_when` int(11) NOT NULL default '0',
  `last_post_user` varchar(32) NOT NULL default '',
  `last_post_when` int(11) NOT NULL default '0',
  `topic_sticky` int(11) NOT NULL default '0',
  `topic_locked` tinyint(4) NOT NULL default '0',
  `topic_hidden` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`),
  KEY `forum_id` (`forum_id`),
  KEY `forum_id_2` (`forum_id`,`when`),
  KEY `topic_uri` (`topic_uri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_user` (
  `user_name` varchar(32) NOT NULL default '',
  `user_pwd` varchar(32) NOT NULL default '',
  `user_email` varchar(128) NOT NULL default '',
  `user_join_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_user_activity` (
  `user` varchar(32) NOT NULL default '',
  `act_current` int(11) NOT NULL default '0',
  `act_last` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_user_stat` (
  `user` varchar(32) NOT NULL default '',
  `posts` int(11) NOT NULL default '0',
  `user_last_post` int(11) NOT NULL default '0',
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_vote` (
  `user_name` varchar(32) NOT NULL default '',
  `post_id` int(11) NOT NULL default '0',
  `vote_when` int(11) NOT NULL default '0',
  `vote_point` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`user_name`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_actions_log` (
  `user_name` varchar(32) NOT NULL default '',
  `id` int(11) NOT NULL default '0',
  `action_name` varchar(32) NOT NULL default '',
  `action_when` int(11) NOT NULL default '0',
  KEY `action_when` (`action_when`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `[db_prefix]forum_attachments` (
  `att_hash` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `att_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `att_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `att_when` int(11) NOT NULL,
  `att_size` int(11) NOT NULL,
  `att_downloads` int(11) NOT NULL,
  PRIMARY KEY (`att_hash`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `[db_prefix]forum_signatures` (
  `user` varchar(32) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `when` int(11) NOT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- page compose pages
SET @iMaxOrder = (SELECT `Order` FROM `sys_page_compose_pages` ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_events_view', 'Event View', @iMaxOrder+1);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_events_celendar', 'Events Calendar', @iMaxOrder+2);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_events_main', 'Main Events Page', @iMaxOrder+3);
INSERT INTO `sys_page_compose_pages` (`Name`, `Title`, `Order`) VALUES ('bx_events_my', 'My Events Page', @iMaxOrder+4);

-- page compose blocks
INSERT INTO `sys_page_compose` (`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`) VALUES 
    ('bx_events_view', '998px', 'Event''s actions block', '_bx_events_block_actions', '1', '0', 'Actions', '', '1', '34', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s rate block', '_bx_events_block_rate', '1', '1', 'Rate', '', '1', '34', 'non,memb', '0'),    
    ('bx_events_view', '998px', 'Event''s info block', '_bx_events_block_info', '1', '2', 'Info', '', '1', '34', 'non,memb', '0'),    
    ('bx_events_view', '998px', 'Event''s participants block', '_bx_events_block_participants', '1', '3', 'Participants', '', '1', '34', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s unconfirmed participants block', '_bx_events_block_participants_unconfirmed', '1', '4', 'ParticipantsUnconfirmed', '', '1', '34', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s description block', '_bx_events_block_desc', '2', '0', 'Desc', '', '1', '66', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s photos block', '_bx_events_block_photos', '2', '1', 'Photos', '', '1', '66', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s videos block', '_bx_events_block_videos', '2', '2', 'Videos', '', '1', '66', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s sounds block', '_bx_events_block_sounds', '2', '3', 'Sounds', '', '1', '66', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s files block', '_bx_events_block_files', '2', '4', 'Files', '', '1', '66', 'non,memb', '0'),
    ('bx_events_view', '998px', 'Event''s comments block', '_bx_events_block_comments', '2', '5', 'Comments', '', '1', '66', 'non,memb', '0'),    

    ('bx_events_main', '998px', 'Upcoming Events Photo', '_bx_events_block_upcoming_photo', '2', '0', 'UpcomingPhoto', '', '1', '66', 'non,memb', '0'),
    ('bx_events_main', '998px', 'Upcoming Events List', '_bx_events_block_upcoming_list', '2', '1', 'UpcomingList', '', '1', '66', 'non,memb', '0'),
    ('bx_events_main', '998px', 'Past Events', '_bx_events_block_past_list', '1', '0', 'PastList', '', '1', '34', 'non,memb', '0'),
    ('bx_events_main', '998px', 'Recently Added Events', '_bx_events_block_recently_added_list', '1', '1', 'RecentlyAddedList', '', '1', '34', 'non,memb', '0'),

    ('bx_events_my', '998px', 'Administration', '_bx_events_block_administration', '1', '0', 'Owner', '', '1', '100', 'non,memb', '0'),
    ('bx_events_my', '998px', 'User''s events', '_bx_events_block_user_events', '1', '1', 'Browse', '', '0', '100', 'non,memb', '0'),

    ('index', '998px', 'Events', '_bx_events_block_home', 0, 0, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''events'', ''homepage_block'');', 1, 66, 'non,memb', 0),
	('profile', '998px', 'Joined Events', '_bx_events_block_joined_events', 0, 0, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''events'', ''profile_block_joined'', array($this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0),
    ('profile', '998px', 'User Events', '_bx_events_block_my_events', 0, 0, 'PHP', 'bx_import(''BxDolService''); return BxDolService::call(''events'', ''profile_block'', array($this->oProfileGen->_iProfileID));', 1, 66, 'non,memb', 0);

-- permalink
INSERT INTO `sys_permalinks` VALUES (NULL, 'modules/?r=events/', 'm/events/', 'bx_events_permalinks');

-- settings
SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Events', @iMaxOrder);
SET @iCategId = (SELECT LAST_INSERT_ID());
INSERT INTO `sys_options` (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) VALUES
('category_auto_app_bx_events', 'on', @iCategId, 'Activate all categories after creation automatically', 'checkbox', '', '', '0', ''),
('bx_events_permalinks', 'on', 26, 'Enable friendly permalinks in events', 'checkbox', '', '', '0', ''),
('bx_events_autoapproval', 'on', @iCategId, 'Activate all events after creation automatically', 'checkbox', '', '', '0', ''),
('bx_events_main_upcoming_event_from_featured_only', '', @iCategId, 'Main upcoming event from featured events only', 'checkbox', '', '', '0', ''),
('bx_events_max_email_invitations', '10', @iCategId, 'Max number of email invitation to send per one invite', 'digit', '', '', '0', ''),
('bx_events_perpage_main_upcoming', '10', @iCategId, 'Number of upcoming events to show on main page', 'digit', '', '', '0', ''),
('bx_events_perpage_main_recent', '4', @iCategId, 'Number of recently added events to show on main page', 'digit', '', '', '0', ''),
('bx_events_perpage_main_past', '6', @iCategId, 'Number of past events to show on main page', 'digit', '', '', '0', ''),
('bx_events_perpage_participants', '9', @iCategId, 'Number of participants to show on event view page', 'digit', '', '', '0', ''),
('bx_events_perpage_browse_participants', '30', @iCategId, 'Number of items to show on browse participants page', 'digit', '', '', '0', ''),
('bx_events_perpage_browse', '14', @iCategId, 'Number of events to show on browse pages', 'digit', '', '', '0', ''),
('bx_events_perpage_homepage', '5', @iCategId, 'Number of events to show on homepage', 'digit', '', '', '0', ''),
('bx_events_homepage_default_tab', 'upcoming', @iCategId, 'Default block tab on homepage', 'select', '', '', '0', 'upcoming,featured,recent,top,popular'),
('bx_events_perpage_profile', '5', @iCategId, 'Number of events to show on profile page', 'digit', '', '', '0', ''),
('bx_events_max_rss_num', '10', @iCategId, 'Max number of rss items to provide', 'digit', '', '', '0', '');

-- search objects
INSERT INTO `sys_objects_search` VALUES(NULL, 'bx_events', '_bx_events', 'BxEventsSearchResult', 'modules/boonex/events/classes/BxEventsSearchResult.php');

-- vote objects
INSERT INTO `sys_objects_vote` VALUES (NULL, 'bx_events', 'bx_events_rating', 'bx_events_rating_track', 'gal_', '5', 'vote_send_result', 'BX_PERIOD_PER_VOTE', '1', '', '', 'bx_events_main', 'Rate', 'RateCount', 'ID', 'BxEventsVoting', 'modules/boonex/events/classes/BxEventsVoting.php');

-- comments objects
INSERT INTO `sys_objects_cmts` VALUES (NULL, 'bx_events', 'bx_events_cmts', 'bx_events_cmts_track', '0', '1', '90', '5', '1', '-3', 'slide', '2000', '1', '1', 'cmt', 'bx_events_main', 'ID', 'CommentsCount', 'BxEventsCmts', 'modules/boonex/events/classes/BxEventsCmts.php');

-- views objects
INSERT INTO `sys_objects_views` VALUES(NULL, 'bx_events', 'bx_events_views_track', 86400, 'bx_events_main', 'ID', 'Views', 1);

-- tag objects
INSERT INTO `sys_objects_tag` VALUES (NULL, 'bx_events', 'SELECT `Tags` FROM `[db_prefix]main` WHERE `ID` = {iID} AND `Status` = ''approved''', 'bx_events_permalinks', 'm/events/browse/tag/{tag}', 'modules/?r=events/browse/tag/{tag}', '_bx_events');

-- category objects
INSERT INTO `sys_objects_categories` VALUES (NULL, 'bx_events', 'SELECT `Categories` FROM `[db_prefix]main` WHERE `ID` = {iID} AND `Status` = ''approved''', 'bx_events_permalinks', 'm/events/browse/category/{tag}', 'modules/?r=events/browse/category/{tag}', '_bx_events');

INSERT INTO `sys_categories` (`Category`, `ID`, `Type`, `Owner`, `Status`) VALUES 
('Events', '0', 'bx_photos', '0', 'active'),
('Party', '0', 'bx_events', '0', 'active'),
('Expedition', '0', 'bx_events', '0', 'active'),
('Presentation', '0', 'bx_events', '0', 'active'),
('Last Friday', '0', 'bx_events', '0', 'active'),
('Birthday', '0', 'bx_events', '0', 'active'),
('Exhibition', '0', 'bx_events', '0', 'active'),
('Bushwalking', '0', 'bx_events', '0', 'active');

-- users actions
INSERT INTO `sys_objects_actions` (`Caption`, `Icon`, `Url`, `Script`, `Eval`, `Order`, `Type`) VALUES 
    ('{TitleEdit}', 'modules/boonex/events/|edit.png', '{evalResult}', '', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return  BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''edit/{ID}'';', '0', 'bx_events'),
    ('{TitleDelete}', 'modules/boonex/events/|action_block.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return  BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''delete/{ID}'';', '1', 'bx_events'),
    ('{TitleJoin}', 'modules/boonex/events/|user_add.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''join/{ID}/{iViewer}'';', '2', 'bx_events'),
    ('{TitleInvite}', 'modules/boonex/events/|group_add.png', '{evalResult}', '', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''invite/{ID}'';', '3', 'bx_events'),
    ('{TitleShare}', 'modules/boonex/events/|action_share.png', '', 'showPopupAnyHtml (''{BaseUri}share_popup/{ID}'');', '', '4', 'bx_events'),
    ('{TitleBroadcast}', 'modules/boonex/events/|action_broadcast.png', '{BaseUri}broadcast/{ID}', '', '', '5', 'bx_events'),
    ('{AddToFeatured}', 'modules/boonex/events/|star__plus.png', '', 'getHtmlData( ''ajaxy_popup_result_div_{ID}'', ''{evalResult}'', false, ''post'');return false;', '$oConfig = $GLOBALS[''oBxEventsModule'']->_oConfig; return BX_DOL_URL_ROOT . $oConfig->getBaseUri() . ''mark_featured/{ID}'';', 6, 'bx_events'),

    ('{TitleManageFans}', 'modules/boonex/events/|action_manage_fans.png', '', 'showPopupAnyHtml (''{BaseUri}manage_fans_popup/{ID}'');', '', '7', 'bx_events'),
    ('{TitleUploadPhotos}', 'modules/boonex/events/|action_upload_photos.png', '{BaseUri}upload_photos/{URI}', '', '', '8', 'bx_events'),
    ('{TitleUploadVideos}', 'modules/boonex/events/|action_upload_videos.png', '{BaseUri}upload_videos/{URI}', '', '', '9', 'bx_events'),
    ('{TitleUploadSounds}', 'modules/boonex/events/|action_upload_sounds.png', '{BaseUri}upload_sounds/{URI}', '', '', '10', 'bx_events'),
    ('{TitleUploadFiles}', 'modules/boonex/events/|action_upload_files.png', '{BaseUri}upload_files/{URI}', '', '', '11', 'bx_events'),    

    ('{TitleSubscribe}', 'action_subscribe.png', '', '{ScriptSubscribe}', '', 7, 'bx_events'),
    ('{evalResult}', 'modules/boonex/events/|calendar_add.png', '{BaseUri}browse/my&bx_events_filter=add_event', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_bx_events_action_create_event'') : '''';', '1', 'bx_events_title'),
    ('{evalResult}', 'modules/boonex/events/|events.png', '{BaseUri}browse/my', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_bx_events_action_my_events'') : '''';', '2', 'bx_events_title'),
    ('{evalResult}', 'modules/boonex/events/|events.png', '{BaseUri}', '', 'return $GLOBALS[''logged''][''member''] || $GLOBALS[''logged''][''admin''] ? _t(''_bx_events_action_events_home'') : '''';', '3', 'bx_events_title');
    
-- top menu
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, 0, 'Events', '_bx_events_menu_root', 'modules/?r=events/view/|modules/?r=events/broadcast/|modules/?r=events/invite/|modules/?r=events/edit/|modules/?r=events/upload_photos/|modules/?r=events/upload_videos/|modules/?r=events/upload_sounds/|modules/?r=events/upload_files/', '', 'non,memb', '', '', '', 1, 1, 1, 'system', 'modules/boonex/events/|bx_events.png', '', '0', '');
SET @iCatRoot := LAST_INSERT_ID();
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, @iCatRoot, 'Event View', '_bx_events_menu_view', 'modules/?r=events/view/{bx_events_view_uri}', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Event View Forum', '_bx_events_menu_view_forum', 'forum/events/forum/{bx_events_view_uri}-0.htm|forum/events/', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Event View Comments', '_bx_events_menu_view_comments', 'modules/?r=events/comments/{bx_events_view_uri}', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Event View Participants', '_bx_events_menu_view_participants', 'modules/?r=events/browse_participants/{bx_events_view_uri}', 3, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, '');


SET @iMaxMenuOrder := (SELECT `Order` + 1 FROM `sys_menu_top` WHERE `Parent` = 0 ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, 0, 'Events', '_bx_events_menu_root', 'modules/?r=events/home/|modules/?r=events/', @iMaxMenuOrder, 'non,memb', '', '', '', 1, 1, 1, 'top', 'modules/boonex/events/|bx_events.png', '', 1, '');
SET @iCatRoot := LAST_INSERT_ID();
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, @iCatRoot, 'Events Main Page', '_bx_events_menu_main', 'modules/?r=events/home/', 0, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Upcoming Events', '_bx_events_menu_upcoming_events', 'modules/?r=events/browse/upcoming', 1, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Past Events', '_bx_events_menu_past_events', 'modules/?r=events/browse/past', 2, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Recently Added Events', '_bx_events_menu_recently_added', 'modules/?r=events/browse/recent', 3, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Top Rated Events', '_bx_events_menu_top_rated', 'modules/?r=events/browse/top', 4, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Popular Events', '_bx_events_menu_popular', 'modules/?r=events/browse/popular', 5, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Featured Events', '_bx_events_menu_featured', 'modules/?r=events/browse/featured', 6, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Events Tags', '_bx_events_menu_tags', 'modules/?r=events/tags', 8, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, 'bx_events'),
(NULL, @iCatRoot, 'Events Categories', '_bx_events_menu_categories', 'modules/?r=events/categories', 9, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, 'bx_events'),
(NULL, @iCatRoot, 'Calendar', '_bx_events_menu_calendar', 'modules/?r=events/calendar', 10, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, ''),
(NULL, @iCatRoot, 'Search', '_bx_events_menu_search', 'modules/?r=events/search', 11, 'non,memb', '', '', '', 1, 1, 1, 'custom', 'modules/boonex/events/|bx_events.png', '', 0, '');

--SET @iCatProfile := (SELECT `ID` FROM `sys_menu_top` WHERE `Parent` = 0 AND `Name` = 'View Profile' LIMIT 1);
SET @iCatProfileOrder := (SELECT MAX(`Order`)+1 FROM `sys_menu_top` WHERE `Parent` = 9 ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, 9, 'Events', '_bx_events_menu_my_events_profile', 'modules/?r=events/browse/user/{profileNick}|modules/?r=events/browse/joined/{profileNick}', @iCatProfileOrder, 'non,memb', '', '', '', 1, 1, 1, 'custom', '', '', 0, '');
SET @iCatProfileOrder := (SELECT MAX(`Order`)+1 FROM `sys_menu_top` WHERE `Parent` = 4 ORDER BY `Order` DESC LIMIT 1);
INSERT INTO `sys_menu_top`(`ID`, `Parent`, `Name`, `Caption`, `Link`, `Order`, `Visible`, `Target`, `Onclick`, `Check`, `Editable`, `Deletable`, `Active`, `Type`, `Picture`, `Icon`, `BQuickLink`, `Statistics`) VALUES
(NULL, 4, 'Events', '_bx_events_menu_my_events_profile', 'modules/?r=events/browse/my', @iCatProfileOrder, 'memb', '', '', '', 1, 1, 1, 'custom', '', '', 0, '');

-- admin menu
SET @iMax = (SELECT MAX(`order`) FROM `sys_menu_admin` WHERE `parent_id` = '2');
INSERT IGNORE INTO `sys_menu_admin` (`parent_id`, `name`, `title`, `url`, `description`, `icon`, `order`) VALUES
(2, 'bx_events', '_bx_events', '{siteUrl}modules/?r=events/administration/', 'Events module by BoonEx', 'modules/boonex/events/|events.png', @iMax+1);

-- email templates
INSERT INTO `sys_email_templates` (`Name`, `Subject`, `Body`, `Desc`, `LangID`) VALUES 
('bx_events_invitation', 'Invitation to event: <EventName>', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p><a href="<InviterUrl>"><InviterNickName></a> has invited you to his event:</p> <pre><InvitationText></pre> <p> <b>Event Information:</b><br /> Name: <EventName><br /> Location: <EventLocation><br /> Date of beginning: <EventStart><br /> <a href="<EventUrl>">More details</a> </p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Events invitation template', '0'),

('bx_events_broadcast', '<BroadcastTitle>', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p><a href="<EntryUrl>"><EntryTitle></a> event admin has sent the following broadcast message:</p> <pre><BroadcastMessage></pre> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Events broadcast message template', '0'),

('bx_events_sbs', 'Event was changed', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p><a href="<ViewLink>"><EntryTitle></a> event was changed: <br /> <ActionName> </p> <p>You may cancel the subscription by clicking the following link: <a href="<UnsubscribeLink>"><UnsubscribeLink></a></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Events subscription template', '0'),

('bx_events_join_request', 'New join request to your event', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>New join request in your event <a href="<EntryUrl>"><EntryTitle></a>. Please review this request and reject or confirm it.</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New join request to an event notification message', '0'),

('bx_events_join_reject', 'Your join request to an event was rejected', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Sorry, but your request to join <a href="<EntryUrl>"><EntryTitle></a> event was rejected by event admin(s).</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Join request to an event was rejected notification message', '0'),

('bx_events_join_confirm', 'Your join request to an event was confirmed', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Congratulations! Your request to join <a href="<EntryUrl>"><EntryTitle></a> event was confirmed by event admin(s).</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'Join request to an event was confirmed notification message', '0'),

('bx_events_fan_remove', 'You was removed from participants of an event', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>You was removed from participants of <a href="<EntryUrl>"><EntryTitle></a> event by event admin(s).</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'User was removed from participants of event notification message', '0'),

('bx_events_fan_become_admin', 'You become admin of an event', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Congratulations! You become admin of <a href="<EntryUrl>"><EntryTitle></a> event.</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'User become admin of an event notification message', '0'),

('bx_events_admin_become_fan', 'You event admin status was removed', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p>Hello <NickName>,</p> <p>Your admin status was removed from <a href="<EntryUrl>"><EntryTitle></a> event by event author.</p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'User event admin status was removed notification message', '0');

-- site stats
INSERT INTO `sys_stat_site` VALUES(NULL, 'evs', 'bx_events', 'modules/?r=events/', 'SELECT COUNT(`ID`) FROM `[db_prefix]main` WHERE `Status` = ''approved''', '../modules/?r=events/administration', 'SELECT COUNT(`ID`) FROM `[db_prefix]main` WHERE `Status` != ''approved''', 'modules/boonex/events/|events.png', 0);

-- PQ statistics
INSERT INTO `sys_stat_member` VALUES ('bx_events', 'SELECT COUNT(*) FROM `[db_prefix]main` WHERE `ResponsibleID` = ''__member_id__'' AND `Status`=''approved''');
INSERT INTO `sys_stat_member` VALUES ('bx_eventsp', 'SELECT COUNT(*) FROM `[db_prefix]main` WHERE `ResponsibleID` = ''__member_id__'' AND `Status`!=''approved''');
INSERT INTO `sys_account_custom_stat_elements` VALUES(NULL, '_bx_events', '__bx_events__ __l_created__ (<a href="modules/?r=events/browse/my&bx_events_filter=add_event">__l_add__</a>)');

-- membership actions
SET @iLevelNonMember := 1;
SET @iLevelStandard := 2;
SET @iLevelPromotion := 3;

INSERT INTO `sys_acl_actions` VALUES (NULL, 'events view', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'events browse', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'events search', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelNonMember, @iAction), (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'events add', NULL);
SET @iAction := LAST_INSERT_ID();
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
    (@iLevelStandard, @iAction), (@iLevelPromotion, @iAction);

INSERT INTO `sys_acl_actions` VALUES (NULL, 'events comments delete and edit', NULL);
INSERT INTO `sys_acl_actions` VALUES (NULL, 'events edit any event', NULL);
INSERT INTO `sys_acl_actions` VALUES (NULL, 'events delete any event', NULL);
INSERT INTO `sys_acl_actions` VALUES (NULL, 'events mark as featured', NULL);
INSERT INTO `sys_acl_actions` VALUES (NULL, 'events approve', NULL);
INSERT INTO `sys_acl_actions` VALUES (NULL, 'events broadcast message', NULL);

-- alert handlers
INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'bx_events_profile_delete', '', '', 'BxDolService::call(''events'', ''response_profile_delete'', array($this));');
SET @iHandler := LAST_INSERT_ID();
INSERT INTO `sys_alerts` VALUES (NULL , 'profile', 'delete', @iHandler);

INSERT INTO `sys_alerts_handlers` VALUES (NULL, 'bx_events_media_delete', '', '', 'BxDolService::call(''events'', ''response_media_delete'', array($this));');
SET @iHandler := LAST_INSERT_ID();
INSERT INTO `sys_alerts` VALUES (NULL , 'bx_photos', 'delete', @iHandler);
INSERT INTO `sys_alerts` VALUES (NULL , 'bx_videos', 'delete', @iHandler);
INSERT INTO `sys_alerts` VALUES (NULL , 'bx_sounds', 'delete', @iHandler);
INSERT INTO `sys_alerts` VALUES (NULL , 'bx_files', 'delete', @iHandler);


-- member menu
INSERT INTO 
    `sys_menu_member` 
SET
    `Name` = 'bx_events',
    `Eval` = 'return BxDolService::call(''events'', ''get_member_menu_item'', array({ID}));',
    `Type` = 'linked_item',
    `Parent` = '1';

-- privacy
INSERT INTO `sys_privacy_actions` (`module_uri`, `name`, `title`, `default_group`) VALUES
('events', 'view_event', '_bx_events_privacy_view_event', '3'),
('events', 'join', '_bx_events_privacy_join', '3'),
('events', 'comment', '_bx_events_privacy_comment', '3'),
('events', 'rate', '_bx_events_privacy_rate', '3'),
('events', 'view_participants', '_bx_events_privacy_view_participants', '3'),
('events', 'post_in_forum', '_bx_events_privacy_post_in_forum', 'p'),
('events', 'upload_photos', '_bx_events_privacy_upload_photos', 'a'),
('events', 'upload_videos', '_bx_events_privacy_upload_videos', 'a'),
('events', 'upload_sounds', '_bx_events_privacy_upload_sounds', 'a'),
('events', 'upload_files', '_bx_events_privacy_upload_files', 'a');

-- subscriptions
INSERT INTO `sys_sbs_types` (`unit`, `action`, `template`, `params`) VALUES
('bx_events', '', '', 'return BxDolService::call(''events'', ''get_subscription_params'', array($arg2, $arg3));'),
('bx_events', 'change', 'bx_events_sbs', 'return BxDolService::call(''events'', ''get_subscription_params'', array($arg2, $arg3));'),
('bx_events', 'commentPost', 'bx_events_sbs', 'return BxDolService::call(''events'', ''get_subscription_params'', array($arg2, $arg3));'),
('bx_events', 'rate', 'bx_events_sbs', 'return BxDolService::call(''events'', ''get_subscription_params'', array($arg2, $arg3));'),
('bx_events', 'join', 'bx_events_sbs', 'return BxDolService::call(''events'', ''get_subscription_params'', array($arg2, $arg3));');

