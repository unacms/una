<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Antispam',
    'version_from' => '9.0.1',
	'version_to' => '9.0.2',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '9.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/antispam/updates/update_9.0.1_9.0.2/',
	'home_uri' => 'antispam_update_901_902',

	'module_dir' => 'boonex/antispam/',
	'module_uri' => 'antispam',

    'db_prefix' => 'bx_antispam_',
    'class_prefix' => 'BxAntispam',

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
    'language_category' => 'Antispam',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
