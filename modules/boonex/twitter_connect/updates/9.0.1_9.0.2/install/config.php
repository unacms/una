<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Twitter connect',
    'version_from' => '9.0.1',
	'version_to' => '9.0.2',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/twitter_connect/updates/update_9.0.1_9.0.2/',
	'home_uri' => 'twitter_update_901_902',

	'module_dir' => 'boonex/twitter_connect/',
	'module_uri' => 'twitter',

    'db_prefix' => 'bx_twitter_',
    'class_prefix' => 'BxTwitter',

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
    'language_category' => 'Twitter Connect',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
