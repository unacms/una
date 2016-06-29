<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Persons Persons
 * @ingroup     TridentModules
 *
 * @{
 */

$aConfig = array(

    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_persons',
    'title' => 'Persons',
    'note' => 'Basic person profiles functionality.',
    'version' => '9.0.1.DEV',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/persons/',
    'home_uri' => 'persons',

    'db_prefix' => 'bx_persons_',
    'class_prefix' => 'BxPersons',

    /**
     * Category for language keys.
     */
    'language_category' => 'Persons',

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
    	'trigger_profile_view_actions',
    ),

	/**
     * Page triggers.
     */
    'page_triggers' => array (
    	'trigger_page_profile_view_entry', 
    ),

    /**
     * Storages.
     */
    'storages' => array(
    	'bx_persons_pictures'
    ),

	/**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_persons_icon',
    	'bx_persons_thumb',
    	'bx_persons_avatar',
    	'bx_persons_picture',
    	'bx_persons_cover',
    	'bx_persons_cover_thumb'
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
        'bx_notifications',
    ),
);

/** @} */
