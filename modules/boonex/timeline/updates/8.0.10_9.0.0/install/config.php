<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Timeline',
    'version_from' => '8.0.10',
	'version_to' => '9.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/timeline/updates/update_8.0.10_9.0.0/',
	'home_uri' => 'timeline_update_8010_900',

	'module_dir' => 'boonex/timeline/',
	'module_uri' => 'timeline',

    'db_prefix' => 'bx_timeline_',
    'class_prefix' => 'BxTimeline',

	/**
     * List of page triggers.
     */
    'page_triggers' => array (
    	'trigger_page_profile_view_entry', 
    	'trigger_page_group_view_entry'
    ),

	/**
     * List of menu triggers.
     */
    'menu_triggers' => array (
    	'trigger_profile_view_submenu',
		'trigger_group_view_submenu'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
    	'update_relations' => 1,
		'process_page_triggers' => 1,
		'process_menu_triggers' => 1,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Timeline',

    /**
     * Relations Section
     */
    'relations' => array(
    	'bx_notifications'
    ),

	/**
     * Files Section
     */
    'delete_files' => array(),
);
