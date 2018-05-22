<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Polls',
    'version_from' => '9.0.4',
	'version_to' => '9.0.5',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC7'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/polls/updates/update_9.0.4_9.0.5/',
	'home_uri' => 'polls_update_904_905',

	'module_dir' => 'boonex/polls/',
	'module_uri' => 'polls',

    'db_prefix' => 'bx_polls_',
    'class_prefix' => 'BxPolls',

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
    'language_category' => 'Polls',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
