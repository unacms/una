<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Snipcart',
    'version_from' => '9.0.3',
	'version_to' => '9.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC7'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/snipcart/updates/update_9.0.3_9.0.4/',
	'home_uri' => 'snipcart_update_903_904',

	'module_dir' => 'boonex/snipcart/',
	'module_uri' => 'snipcart',

    'db_prefix' => 'bx_snipcart_',
    'class_prefix' => 'BxSnipcart',

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
    'language_category' => 'Snipcart',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
