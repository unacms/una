<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Froala',
    'version_from' => '9.0.4',
	'version_to' => '10.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '10.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/froala/updates/update_9.0.4_10.0.0/',
	'home_uri' => 'froala_update_904_1000',

	'module_dir' => 'boonex/froala/',
	'module_uri' => 'froala',

    'db_prefix' => 'bx_froala_',
    'class_prefix' => 'BxFroala',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Froala',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
