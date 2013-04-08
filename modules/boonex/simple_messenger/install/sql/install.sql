
    INSERT INTO 
        `sys_objects_actions` 
    SET
        `Caption` = '_Simle Messenger', 
        `Eval`    = 'return BxDolService::call(''simple_messenger'', ''get_messenger_field'', array({ID}));', 
        `Type` = 'Profile';

    INSERT INTO 
        `sys_injections`
    SET
        `name`       = 'bx_simple_messenger_core_init',
        `page_index` = '0',
        `key`        = 'injection_header',
        `type`       = 'php',
        `data`       = 'return BxDolService::call(''simple_messenger'', ''get_messenger_core'');', `replace`    = '0',
        `active`     = '1';

    --
    -- Table structure for table `bx_simple_messenger_messages`
    --

    CREATE TABLE `[db_prefix]messages` (
      `ID` int(10) unsigned NOT NULL auto_increment,
      `Message` text collate utf8_unicode_ci NOT NULL,
      `SenderID` int(11) NOT NULL,
      `RecipientID` int(11) NOT NULL,
      `Date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
      `SenderStatus` enum('active','close') collate utf8_unicode_ci NOT NULL default 'active',
      `RecipientStatus` enum('active','close') collate utf8_unicode_ci NOT NULL,
      PRIMARY KEY  (`ID`),
      KEY `SenderID` (`SenderID`),
      KEY `RecipientID` (`RecipientID`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    --
    -- Table structure for table `bx_simple_messenger_privacy`
    --

    CREATE TABLE `[db_prefix]privacy` (
      `author_id` int(11) NOT NULL,
      `allow_contact_to` int(11) NOT NULL,
      PRIMARY KEY  (`author_id`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


    INSERT INTO 
        `sys_privacy_actions` 
    SET 
        `module_uri` = 'simple_messenger', 
        `name` = 'contact', 
        `title` = '_simple_messenger_privacy_settings',
        `default_group` = 3;

    --
    -- Dumping data for table `sys_menu_member`
    --

    INSERT INTO 
        `sys_menu_member` 
    SET
        `Name`   = 'bx_simple_messenger', 
        `Eval`   = 'return BxDolService::call(''simple_messenger'', ''get_privacy_link'');',
        `Type`   = 'linked_item', 
        `Parent` = 4;

    SET @iLevelStandard  := 2;
    SET @iLevelPromotion := 3;

    INSERT INTO `sys_acl_actions` VALUES (NULL, 'use simple messenger', NULL);
    SET @iAction := LAST_INSERT_ID();
    INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES 
        (@iLevelStandard, @iAction), 
        (@iLevelPromotion, @iAction);

    --
    -- `sys_options_cats` ;
    --

    SET @iMaxOrder = (SELECT `menu_order` + 1 FROM `sys_options_cats` ORDER BY `menu_order` DESC LIMIT 1);
    INSERT INTO `sys_options_cats` (`name`, `menu_order`) VALUES ('Simple messenger', @iMaxOrder);
    SET @iKategId = (SELECT LAST_INSERT_ID());

    --
    -- Dumping data for table `sys_options`;
    --

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'simple_messenger_update_time',
        `kateg` = @iKategId,
        `desc`  = 'Simple messenger update time (in milliseconds)',
        `Type`  = 'digit',
        `VALUE` = '3000',
        `check` = 'return is_numeric($arg0);',
        `order_in_kateg` = 1;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'simple_messenger_visible_messages',
        `kateg` = @iKategId,
        `desc`  = 'The amount of messages visible in a chat window',
        `Type`  = 'digit',
        `VALUE` = '25',
        `check` = 'return is_numeric($arg0);',
        `order_in_kateg` = 2;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'simple_messenger_allowed_chatbox',
        `kateg` = @iKategId,
        `desc`  = 'The amount of active chat box available for member',
        `Type`  = 'digit',
        `VALUE` = '3',
        `check` = 'return is_numeric($arg0);',
        `order_in_kateg` = 3;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'simple_messenger_procces_smiles',
        `kateg` = @iKategId,
        `desc`  = 'Allow to procces smile\'s codes',
        `Type`  = 'checkbox',
        `VALUE` = 'on',
        `order_in_kateg` = 4;

    INSERT INTO 
        `sys_options` 
    SET
        `Name` = 'simple_messenger_blink_counter',
        `kateg` = @iKategId,
        `desc`  = 'The amount of flashes (blinks) when the new messages are recieved',
        `Type`  = 'digit',
        `VALUE` = '4',
        `check` = 'return is_numeric($arg0);',
        `order_in_kateg` = 5;

        -- 
        -- `sys_menu_admin`;
        --

        INSERT INTO 
            `sys_menu_admin` 
        SET
            `name`           = 'Simple messenger',
            `title`          = '_simple_messenger_title', 
            `url`            = CONCAT('{siteAdminUrl}settings.php?cat=', @iKategId), 
            `description`    = 'Managing the simple messenger settings', 
            `icon`           = 'modules/boonex/simple_messenger/|messenger.png',
            `parent_id`      = 2;
        
    --
    -- `sys_alerts_handlers` ;
    --

    INSERT INTO
        `sys_alerts_handlers`
    SET
        `name`  = 'bx_simple_messenger',
        `class` = 'BxSimpleMessengerResponse',
        `file`  = 'modules/boonex/simple_messenger/classes/BxSimpleMessengerResponse.php';

    SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_simple_messenger'  LIMIT 1);
   
    --
    -- `sys_alerts` ;
    --

    INSERT INTO
        `sys_alerts`
    SET
        `unit`       = 'profile',
        `action`     = 'delete',
        `handler_id` = @iHandlerId;

	-- permalink

    INSERT INTO 
        `sys_permalinks` 
    SET
        `standard`  = 'modules/?r=simple_messenger/', 
        `permalink` = 'm/simple_messenger/', 
        `check`     = 'bx_simple_messenger_permalinks';

    -- settings

    INSERT INTO 
        `sys_options` 
    (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) 
        VALUES
    ('bx_simple_messenger_permalinks', 'on', 26, 'Enable friendly permalinks in simple messenger', 'checkbox', '', '', '0', '');


