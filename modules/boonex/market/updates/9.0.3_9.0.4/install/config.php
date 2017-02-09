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
    'version_from' => '9.0.3',
	'version_to' => '9.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-B4'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/market/updates/update_9.0.3_9.0.4/',
	'home_uri' => 'market_update_903_904',

	'module_dir' => 'boonex/market/',
	'module_uri' => 'market',

    'db_prefix' => 'bx_market_',
    'class_prefix' => 'BxMarket',

	/**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_market_icon',
		'bx_market_thumb'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
		'register_transcoders' => 1,
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
