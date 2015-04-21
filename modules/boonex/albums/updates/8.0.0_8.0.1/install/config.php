<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Albums',
    'version_from' => '8.0.0',
	'version_to' => '8.0.1',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/albums/updates/update_8.0.0_8.0.1/',
	'home_uri' => 'albums_update_800_801',

	'module_dir' => 'boonex/albums/',
	'module_uri' => 'albums',

    'db_prefix' => 'bx_albums_',
    'class_prefix' => 'BxAlbums',

	/**
     * List of menu triggers.
     */
    'menu_triggers' => array (
    	'trigger_profile_view_submenu'
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
    'language_category' => 'Albums',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
