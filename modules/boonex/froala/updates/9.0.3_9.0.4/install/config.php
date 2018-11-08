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
    'version_from' => '9.0.3',
	'version_to' => '9.0.4',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC12'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/froala/updates/update_9.0.3_9.0.4/',
	'home_uri' => 'froala_update_903_904',

	'module_dir' => 'boonex/froala/',
	'module_uri' => 'froala',

    'db_prefix' => 'bx_froala_',
    'class_prefix' => 'BxFroala',

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
    'language_category' => 'Froala',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
