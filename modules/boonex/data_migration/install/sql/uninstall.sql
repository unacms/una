
	DROP TABLE IF EXISTS `[db_prefix]config`;
	DROP TABLE IF EXISTS `[db_prefix]transfers`;

    --
    -- Admin menu ;
    --

    DELETE FROM
        `sys_menu_admin` 
    WHERE
        `url` = '{siteUrl}modules/?r=data_migration/administration/';

   DELETE FROM
        `sys_options` 
   WHERE
   		`Name` = 'bx_data_migration_permalinks';
 
   DELETE FROM
        `sys_permalinks`
   WHERE
        `standard`  = 'modules/?r=data_migration/'
        	AND
        `permalink` = 'm/data_migration/';

	DELETE FROM
	    `sys_cron_jobs` 
	WHERE
		`name` = 'bx_data_migration' LIMIT 1;