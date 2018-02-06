<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Accounts Manager',
    'version_from' => '9.0.6',
	'version_to' => '9.0.7',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '9.0.0-RC5'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/accounts/updates/update_9.0.6_9.0.7/',
	'home_uri' => 'accounts_update_906_907',

	'module_dir' => 'boonex/accounts/',
	'module_uri' => 'accounts',

    'db_prefix' => 'bx_accnt_',
    'class_prefix' => 'BxAccnt',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 0,
		'clear_db_cache' => 0,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Accounts',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
