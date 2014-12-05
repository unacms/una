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
    'version_from' => '8.0.4',
	'version_to' => '8.0.5',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '8.0.0.A9'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/timeline/updates/update_8.0.4_8.0.5/',
	'home_uri' => 'timeline_update_804_805',

	'module_dir' => 'boonex/timeline/',
	'module_uri' => 'timeline',

    'db_prefix' => 'bx_timeline_',
    'class_prefix' => 'BxTimeline',

    /**
     * List of page triggers.
     */
    'page_triggers' => array (
    	'trigger_page_persons_view_entry', 
    	'trigger_page_organizations_view_entry'
    ),

    /**
     * Storages.
     */
    'storages' => array(
    	'bx_timeline_videos'
    ),

    /**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_timeline_videos_poster',
    	'bx_timeline_videos_mp4',
    	'bx_timeline_videos_webm'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
		'process_page_triggers' => 1,
    	'register_transcoders' => 1,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Timeline',

	/**
     * Files Section
     */
    'delete_files' => array(
    	'classes/BxTimelineUploaderSimple.php',
		'template/uploader_bs.html'
    ),
);
