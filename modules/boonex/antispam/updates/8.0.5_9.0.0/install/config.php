<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Antispam',
    'version_from' => '8.0.5',
	'version_to' => '9.0.0',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '9.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/antispam/updates/update_8.0.5_9.0.0/',
	'home_uri' => 'antispam_update_805_900',

	'module_dir' => 'boonex/antispam/',
	'module_uri' => 'antispam',

    'db_prefix' => 'bx_antispam_',
    'class_prefix' => 'BxAntispam',

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
    'language_category' => 'Antispam',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
