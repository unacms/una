<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Convos Convos
 * @ingroup     UnaModules
 *
 * @{
 */

$aConfig = array(

    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_convos',
    'title' => 'Conversations',
    'note' => 'Conversations module.',
    'version' => '9.0.5',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '9.0.0-RC2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/convos/',
    'home_uri' => 'convos',

    'db_prefix' => 'bx_convos_',
    'class_prefix' => 'BxCnv',

    /**
     * Category for language keys.
     */
    'language_category' => 'Conversations',

    /**
     * Menu triggers.
     */
    'menu_triggers' => array(
        'trigger_profile_view_actions',
        'trigger_group_view_actions',
    ),

    /**
     * Storages.
     */
    'storages' => array(
    	'bx_convos_files'
    ),

	/**
     * Transcoders.
     */
    'transcoders' => array(
		'bx_convos_preview'
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
    	'process_menu_triggers' => 1,
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
