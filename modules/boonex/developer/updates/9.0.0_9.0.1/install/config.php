<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Developer',
    'version_from' => '9.0.0',
	'version_to' => '9.0.1',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '9.0.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/developer/updates/update_9.0.0_9.0.1/',
	'home_uri' => 'developer_update_900_901',

	'module_dir' => 'boonex/developer/',
	'module_uri' => 'developer',

    'db_prefix' => 'bx_dev_',
    'class_prefix' => 'BxDev',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'BoonEx Developer',
);
