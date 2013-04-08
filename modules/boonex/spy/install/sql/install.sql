
    --
    -- Table structure for table `[db_prefix]handlers`
    --

    CREATE TABLE IF NOT EXISTS `[db_prefix]handlers` (
      `id` int(11) NOT NULL auto_increment,
      `alert_unit` varchar(64) NOT NULL default '',
      `alert_action` varchar(64) NOT NULL default '',
      `module_uri` varchar(64) NOT NULL default '',
      `module_class` varchar(64) NOT NULL default '',
      `module_method` varchar(64) NOT NULL default '',
      PRIMARY KEY  (`id`),
      UNIQUE `handler` (`alert_unit`, `alert_action`, `module_uri`, `module_class`, `module_method`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
    
    --
    -- Table structure for table `bx_spy_data`
    --

    CREATE TABLE `[db_prefix]data` (
      `id` int(10) unsigned NOT NULL auto_increment,
      `sender_id` int(11) NOT NULL,
      `recipient_id` int(11) NOT NULL,
      `lang_key` varchar(100) collate utf8_unicode_ci NOT NULL,
      `params` text collate utf8_unicode_ci NOT NULL,
      `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
      `type` enum('content_activity','profiles_activity') collate utf8_unicode_ci NOT NULL,
      `viewed` tinyint(1) NOT NULL,
      PRIMARY KEY  (`id`),
      KEY `recipient_id` (`recipient_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    --
    -- Table structure for table `[db_prefix]friends_events`
    --

    CREATE TABLE IF NOT EXISTS `[db_prefix]friends_data` (
      `id` int(10) unsigned NOT NULL auto_increment,
      `sender_id` int(11) NOT NULL,
      `friend_id` int(11) NOT NULL,
      `event_id` int(11) NOT NULL,
      PRIMARY KEY  (`id`),
      KEY `event_id` (`event_id`),
      KEY `friend_id` (`friend_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

    --
    -- Dumping data for table `sys_alerts_handlers`
    --

    INSERT INTO 
        `sys_alerts_handlers`
    SET
        `name`      = 'bx_spy_content_activity', 
        `class`     = '', 
        `file`      = '', 
        `eval`      = 'BxDolService::call(\'spy\', \'response\', array($this));';

    INSERT INTO 
        `sys_alerts_handlers`
    SET
        `name`      = 'bx_spy_profiles_activity', 
        `class`     = 'BxSpyProfilesActivity', 
        `file`      = 'modules/boonex/spy/classes/BxSpyProfilesActivity.php', 
        `eval`      = '';

    SET @iLastHandler := ( SELECT LAST_INSERT_ID() );

    --
    -- Dumping data for table `sys_alerts`
    --

    INSERT INTO `sys_alerts` SET `unit` = 'profile', `action` = 'commentPost', `handler_id` = @iLastHandler;
    INSERT INTO `sys_alerts` SET `unit` = 'profile', `action` = 'rate', `handler_id` = @iLastHandler;
    INSERT INTO `sys_alerts` SET `unit` = 'profile', `action` = 'view', `handler_id` = @iLastHandler;
    INSERT INTO `sys_alerts` SET `unit` = 'profile', `action` = 'join', `handler_id` = @iLastHandler;
    INSERT INTO `sys_alerts` SET `unit` = 'profile', `action` = 'edit', `handler_id` = @iLastHandler;
    INSERT INTO `sys_alerts` SET `unit` = 'friend',  `action` = 'request', `handler_id` = @iLastHandler;
    INSERT INTO `sys_alerts` SET `unit` = 'friend',  `action` = 'accept', `handler_id` = @iLastHandler;

    --     
    -- Top menu ;
    -- 

    INSERT INTO 
        `sys_menu_top` 
    SET
        `Name`       = 'Spy', 
        `Caption`    = '_bx_spy', 
        `Link`       = 'modules/?r=spy/', 
        `Order`      = 5, 
        `Visible`    = 'non,memb', 
        `Editable`   = 1, 
        `Deletable`  = 1, 
        `Active`     = 1, 
        `Type`       = 'top', 
        `Picture`    = 'modules/boonex/spy/|spy.png', 
        `BQuickLink` = 1;

    SET @iMenuParentId := (SELECT `ID` FROM `sys_menu_top`  WHERE `Name` = 'Spy' AND `Caption` = '_bx_spy' LIMIT 1);

    INSERT INTO 
        `sys_menu_top` 
    SET
        `Parent`     = @iMenuParentId, 
        `Name`       = 'All Users', 
        `Caption`    = '_bx_spy_all',
        `Link`       = 'modules/?r=spy/', 
        `Order`      = 1, 
        `Visible`    = 'non,memb', 
        `Editable`   = 1, 
        `Deletable`  = 1, 
        `Active`     = 1, 
        `Type`       = 'custom', 
        `Picture`    = 'modules/boonex/spy/|spy.png', 
        `BQuickLink` = 0;
    
    INSERT INTO 
        `sys_menu_top` 
    SET
        `Parent`     = @iMenuParentId, 
        `Name`       = 'Friends', 
        `Caption`    = '_bx_spy_friends',
        `Link`       = 'modules/?r=spy/&mode=friends_events', 
        `Order`      = 2, 
        `Visible`    = 'memb', 
        `Editable`   = 1, 
        `Deletable`  = 1, 
        `Active`     = 1, 
        `Type`       = 'custom', 
        `Picture`    = 'modules/boonex/spy/|spy.png', 
        `BQuickLink` = 0;
   
    --
    -- Admin menu ;
    --

    INSERT INTO 
        `sys_menu_admin` 
    SET
        `name`          = 'Spy',
        `title`         = '_bx_spy', 
        `url`           = '{siteUrl}modules/?r=spy/administration/',
        `description`   = 'Some spy page settings can be found here',
        `icon`          = 'modules/boonex/spy/|spy_l.png',
        `parent_id`     = 2;

    --
    -- Dumping data for table `sys_cron_jobs`
    --

    INSERT INTO 
        `sys_cron_jobs` 
    (`name`, `time`, `class`, `file`)
        VALUES
    ('bx_spy', '1 */12 * * *', 'BxSpyCron', 'modules/boonex/spy/classes/BxSpyCron.php');

    --
    -- `sys_options_cats` ;
    --

    SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
    INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Spy', @iMaxOrder);
    SET @iKatId = (SELECT LAST_INSERT_ID());

    --
    -- `sys_options` ;
    --

    INSERT INTO 
        `sys_options` 
    SET 
        `Name` = 'bx_spy_keep_rows_days',
        `VALUE` = '30', 
        `kateg` = @iKatId, 
        `desc` = 'Number of days to keep records', 
        `Type` = 'digit';

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'bx_spy_update_time',
        `kateg` = @iKatId,
        `desc`  = 'Spy page refresh time (in milliseconds)',
        `Type`  = 'digit',
        `VALUE` = '10000',
        `check` = 'return is_numeric($arg0);';
    
    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'bx_spy_toggle_up',
        `kateg` = @iKatId,
        `desc`  = 'Speed of block restoration (in milliseconds)',
        `Type`  = 'digit',
        `VALUE` = '1500',
        `check` = 'return is_numeric($arg0);';

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'bx_spy_toggle_down',
        `kateg` = @iKatId,
        `desc`  = 'Speed of block minimization(in milliseconds)',
        `Type`  = 'digit',
        `VALUE` = '1500',
        `check` = 'return is_numeric($arg0);';

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'bx_spy_per_page',
        `kateg` = @iKatId,
        `desc`  = 'Count of events for per page',
        `Type`  = 'digit',
        `VALUE` = '10',
        `check` = 'return is_numeric($arg0);';

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'bx_spy_guest_allow',
        `kateg` = @iKatId,
        `desc`  = 'Track spy activities for guests',
        `Type`  = 'checkbox',
        `VALUE` = '';

    --
    -- Settings
    --

    INSERT INTO 
        `sys_options` 
    (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) 
        VALUES
    ('bx_spy_permalinks', 'on', 26, 'Enable friendly permalinks in spy', 'checkbox', '', '', '0', '');

    INSERT INTO
        `sys_permalinks`
    SET
        `standard`  = 'modules/?r=spy/',
        `permalink` = 'm/spy/',
        `check`     = 'bx_spy_permalinks';

    INSERT INTO 
        `sys_page_compose` 
    SET
        `Page`      = 'member', 
        `PageWidth` = '998px', 
        `Desc`      = 'Spy data here', 
        `Caption`   = '_bx_spy', 
        `Column`    = 2, 
        `Order`     = 2, 
        `Func`      = 'PHP',
        `Content`   = 'return BxDolService::call(''spy'', ''get_spy_block'', array(''member.php'', $this->iMember));',    
        `DesignBox` = 1, 
        `ColWidth`  = 66, 
        `Visible`   = 'memb';

	INSERT INTO 
		`sys_page_compose`
	(`Page`, `PageWidth`, `Desc`, `Caption`, `Column`, `Order`, `Func`, `Content`, `DesignBox`, `ColWidth`, `Visible`, `MinWidth`)
		VALUES
	('index', '998px', 'Spy data here', '_bx_spy', 2, 0, 'PHP', 'return BxDolService::call(''spy'', ''get_spy_block'');', 1, 34, 'non,memb', 0);

    INSERT INTO 
        `sys_menu_member` 
    SET
        `Caption`   = '_bx_spy_notifications', 
        `Name`      = 'Spy',
        `Icon`      = 'modules/boonex/spy/|spy_notify.png', 
        `Link`      = 'member.php#spy_block',
        `Position`  = 'top_extra',
        `Order`     = 0,
        `PopupMenu` = 'BxDolService::call(''spy'', ''get_member_menu_spy_data''); ',
        `Description` = '_bx_spy_notifications',
        `Bubble`    = '$aRetEval = BxDolService::call(''spy'', ''get_member_menu_bubbles_data'', array({iOldCount}));';
