<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Posts Posts
 * @ingroup     TridentModules
 *
 * @{
 */

$aConfig = array(

    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_posts',
    'title' => 'Posts',
    'note' => 'Basic blogging module.',
    'version' => '8.0.6',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '8.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/posts/',
    'home_uri' => 'posts',

    'db_prefix' => 'bx_posts_',
    'class_prefix' => 'BxPosts',

    /**
     * Category for language keys.
     */
    'language_category' => 'Posts',

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
    	'bx_posts_files'
    ),

    /**
     * Transcoders.
     */
    'transcoders' => array(
		'bx_posts_preview',
        'bx_posts_gallery',
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
