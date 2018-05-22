<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Market',
    'version_from' => '9.0.8',
	'version_to' => '9.0.9',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC7'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/market/updates/update_9.0.8_9.0.9/',
	'home_uri' => 'market_update_908_909',

	'module_dir' => 'boonex/market/',
	'module_uri' => 'market',

    'db_prefix' => 'bx_market_',
    'class_prefix' => 'BxMarket',

	/**
     * List of menu triggers.
     */
    'menu_triggers' => array (
    	'trigger_group_view_submenu'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
		'process_menu_triggers' => 1,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Market',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
