<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Channels',
    'version_from' => '9.0.1',
	'version_to' => '9.0.2',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC12'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/channels/updates/update_9.0.1_9.0.2/',
	'home_uri' => 'channels_update_901_902',

	'module_dir' => 'boonex/channels/',
	'module_uri' => 'channels',

    'db_prefix' => 'bx_cnl_',
    'class_prefix' => 'BxCnl',

    /**
     * Menu triggers.
     */
    'menu_triggers' => array(
        'trigger_profile_view_submenu',
    ),

/**
     * Page triggers.
     */
    'page_triggers' => array (
        'trigger_page_profile_view_entry',
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'process_menu_triggers' => 1,
        'process_page_triggers' => 1,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Channels',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
