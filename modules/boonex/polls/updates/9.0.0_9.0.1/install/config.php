<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Polls',
    'version_from' => '9.0.0',
	'version_to' => '9.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/polls/updates/update_9.0.0_9.0.1/',
	'home_uri' => 'polls_update_900_901',

	'module_dir' => 'boonex/polls/',
	'module_uri' => 'polls',

    'db_prefix' => 'bx_polls_',
    'class_prefix' => 'BxPolls',

	/**
     * Transcoders.
     */
    'transcoders' => array(
    	'bx_polls_cover'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
		'register_transcoders' => 1,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Polls',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
