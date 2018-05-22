<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Videos',
    'version_from' => '9.0.1',
	'version_to' => '9.0.2',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC7'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/videos/updates/update_9.0.1_9.0.2/',
	'home_uri' => 'videos_update_901_902',

	'module_dir' => 'boonex/videos/',
	'module_uri' => 'videos',

    'db_prefix' => 'bx_videos_',
    'class_prefix' => 'BxVideos',

	/**
     * List of menu triggers.
     */
    'menu_triggers' => array (
    	'trigger_group_view_submenu'
    ),

	/**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_videos_poster'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
		'process_menu_triggers' => 1,
		'register_transcoders' => 1,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Videos',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
