<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Quote of the Day',
    'version_from' => '9.0.3',
	'version_to' => '9.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC11'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/quoteofday/updates/update_9.0.3_9.0.4/',
	'home_uri' => 'quoteofday_update_903_904',

	'module_dir' => 'boonex/quoteofday/',
	'module_uri' => 'quoteofday',

    'db_prefix' => 'bx_quoteofday_',
    'class_prefix' => 'BxQuoteOfDay',

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
    'language_category' => 'Quote Of Day',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
