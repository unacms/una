<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Protean',
    'version_from' => '9.0.11',
    'version_to' => '10.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '10.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/protean/updates/update_9.0.11_10.0.0/',
    'home_uri' => 'protean_update_9011_1000',

    'module_dir' => 'boonex/protean/',
    'module_uri' => 'protean',

    'db_prefix' => 'bx_protean_',
    'class_prefix' => 'BxProtean',

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
    'language_category' => 'Boonex Protean Template',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
