
	--
	-- Table structure for table `bx_data_migration_transfers`
	--
	
	CREATE TABLE `[db_prefix]transfers` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `module` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `status` enum('not_started','started','finished','error') COLLATE utf8_unicode_ci NOT NULL,
	  `status_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

	--
	-- Table structure for table `bx_data_migration_config`
	--
	
	CREATE TABLE `[db_prefix]config` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `value` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

    --
    -- Admin menu ;
    --

    INSERT INTO 
        `sys_menu_admin` 
    SET
        `name`          = 'Data migration',
        `title`         = '_bx_data_migration', 
        `url`           = '{siteUrl}modules/?r=data_migration/administration/',
        `description`   = 'Migration all data from previous Dolphin version',
        `icon`          = 'modules/boonex/data_migration/|data_migration.gif',
        `parent_id`     = 2;

    --
    -- Settings
    --

    INSERT INTO 
        `sys_options` 
    (`Name`, `VALUE`, `kateg`, `desc`, `Type`, `check`, `err_text`, `order_in_kateg`, `AvailableValues`) 
        VALUES
    ('bx_data_migration_permalinks', 'on', 26, 'Enable friendly permalinks in data migration module', 'checkbox', '', '', '0', '');

    INSERT INTO
        `sys_permalinks`
    SET
        `standard`  = 'modules/?r=data_migration/',
        `permalink` = 'm/data_migration/',
        `check`     = 'bx_data_migration_permalinks';
 
  	--
    -- Dumping data for table `sys_cron_jobs`
    --

    INSERT INTO 
        `sys_cron_jobs` 
    (`name`, `time`, `class`, `file`)
        VALUES
    ('bx_data_migration', '*/5 * * * *', 'BxDataMigrationCron', 'modules/boonex/data_migration/classes/BxDataMigrationCron.php');