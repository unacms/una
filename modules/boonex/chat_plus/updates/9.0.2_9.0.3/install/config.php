<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'RocketChat',
    'version_from' => '9.0.2',
	'version_to' => '9.0.3',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-B5'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/chat_plus/updates/update_9.0.2_9.0.3/',
	'home_uri' => 'chat_plus_update_902_903',

	'module_dir' => 'boonex/chat_plus/',
	'module_uri' => 'chat_plus',

    'db_prefix' => 'bx_chat_plus_',
    'class_prefix' => 'BxChatPlus',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Chat+',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
