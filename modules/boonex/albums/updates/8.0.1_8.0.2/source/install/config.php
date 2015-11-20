<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

$aConfig = array(

    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_albums',
    'title' => 'Albums',
    'note' => 'Photos & videos',
    'version' => '8.0.2',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '8.0.0-RC'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/albums/',
    'home_uri' => 'albums',

    'db_prefix' => 'bx_albums_',
    'class_prefix' => 'BxAlbums',

    /**
     * Category for language keys.
     */
    'language_category' => 'Albums',

    /**
     * Menu triggers.
     */
    'menu_triggers' => array(
    	'trigger_profile_view_submenu'
    ),

    /**
     * Storages.
     */
    'storages' => array(
    	'bx_albums_files'
    ),

    /**
     * Transcoders.
     */
    'transcoders' => array(
		'bx_albums_preview',
        'bx_albums_browse',
        'bx_albums_big',
        'bx_albums_video_poster_browse',
        'bx_albums_video_poster_preview',
        'bx_albums_video_poster_big',
        'bx_albums_video_mp4',
        'bx_albums_video_webm',
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
    	'update_relations' => 1,
        'clear_db_cache' => 1,
    ),
    'enable' => array(
        'execute_sql' => 1,
    	'update_relations' => 1,
        'clear_db_cache' => 1,
    ),
    'enable_success' => array(
    	'process_menu_triggers' => 1,
    	'register_transcoders' => 1,
        'clear_db_cache' => 1,
    ),
    'disable' => array (
        'execute_sql' => 1,
    	'update_relations' => 1,
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

    /**
     * Connections Section
     */
    'relations' => array(
    	'bx_timeline',
    	'bx_notifications'
    ),

);

/** @} */
