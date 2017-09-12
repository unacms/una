<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

$aConfig = array(

    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_events',
    'title' => 'Events',
    'note' => 'Events functionality.',
    'version' => '9.0.4',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '9.0.0-RC2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/events/',
    'home_uri' => 'events',

    'db_prefix' => 'bx_events_',
    'class_prefix' => 'BxEvents',

    /**
     * Category for language keys.
     */
    'language_category' => 'Events',

    /**
     * Connections.
     */
    'connections' => array(
    	'sys_profiles_friends' => array ('type' => 'profiles'),
		'sys_profiles_subscriptions' => array ('type' => 'profiles'),
    ),

    /**
     * Menu triggers.
     */
    'menu_triggers' => array(
    	'trigger_profile_view_submenu',
    	'trigger_group_view_submenu',
        'trigger_group_view_actions',
    ),

    /**
     * Page triggers.
     */
    'page_triggers' => array (
    	'trigger_page_group_view_entry', 
    ),

    /**
     * Storages.
     */
    'storages' => array(
    	'bx_events_pics'
    ),

	/**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_events_icon', 
    	'bx_events_thumb', 
    	'bx_events_avatar', 
    	'bx_events_picture', 
    	'bx_events_cover', 
    	'bx_events_cover_thumb',
    	'bx_events_gallery'
    ),

    /**
     * Extended Search Forms.
     */
    'esearches' => array(
        'bx_events',
    	'bx_events_cmts',
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
    	'process_esearches' => 1,
        'execute_sql' => 1,
        'update_languages' => 1,
    	'process_connections' => 1,
        'process_deleted_profiles' => 1,
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
    	'process_page_triggers' => 1,
    	'process_esearches' => 1,
    	'register_transcoders' => 1,
        'clear_db_cache' => 1,
    ),
    'disable' => array (
        'execute_sql' => 1,
        'unregister_transcoders' => 1,
        'update_relations' => 1,
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
        'bx_notifications',
    ),
);

/** @} */
