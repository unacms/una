<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Organizations Organizations
 * @ingroup     TridentModules
 *
 * @{
 */

$aConfig = array(

    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_organizations',
    'title' => 'Organizations',
    'note' => 'Basic organization profiles functionality.',
    'version' => '9.0.0',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/organizations/',
    'home_uri' => 'orgs',

    'db_prefix' => 'bx_organizations_',
    'class_prefix' => 'BxOrgs',

    /**
     * Category for language keys.
     */
    'language_category' => 'Organizations',

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
    	'bx_organizations_pics'
    ),

	/**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_organizations_icon', 
    	'bx_organizations_thumb', 
    	'bx_organizations_avatar', 
    	'bx_organizations_picture', 
    	'bx_organizations_cover', 
    	'bx_organizations_cover_thumb'
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
