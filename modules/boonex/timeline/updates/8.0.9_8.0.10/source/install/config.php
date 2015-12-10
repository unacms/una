<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     TridentModules
 *
 * @{
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_timeline',
    'title' => 'Timeline',
    'note' => 'Timeline module.',
    'version' => '8.0.10',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '8.0.0-RC'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/timeline/',
    'home_uri' => 'timeline',

    'db_prefix' => 'bx_timeline_',
    'class_prefix' => 'BxTimeline',

    /**
     * Category for language keys.
     */
    'language_category' => 'Timeline',

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
    	'bx_timeline_photos',
    	'bx_timeline_videos'
    ),

    /**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_timeline_photos_preview',
    	'bx_timeline_photos_view',
    	'bx_timeline_videos_poster',
    	'bx_timeline_videos_mp4',
    	'bx_timeline_videos_webm'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),
    'uninstall' => array (
    	'process_storages' => 1,
        'execute_sql' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),
    'enable' => array(
        'execute_sql' => 1,
        'clear_db_cache' => 1,
    ),
    'enable_success' => array(
    	'process_page_triggers' => 1,
    	'register_transcoders' => 1,
    	'clear_db_cache' => 1,
    ),
    'disable' => array (
        'execute_sql' => 1,
    	'unregister_transcoders' => 1,
        'clear_db_cache' => 1,
    ),
    'disable_failed' => array (
    	'register_transcoders' => 1,
    	'clear_db_cache' => 1,
    ),

    /**
     * Dependencies Section
     */
    'dependencies' => array(),

);

/** @} */
