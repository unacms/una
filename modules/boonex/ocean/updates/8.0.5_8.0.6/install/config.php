<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Ocean',
    'version_from' => '8.0.5',
	'version_to' => '8.0.6',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/ocean/updates/update_8.0.5_8.0.6/',
	'home_uri' => 'ocean_update_805_806',

	'module_dir' => 'boonex/ocean/',
	'module_uri' => 'ocean',

    'db_prefix' => 'bx_ocean_',
    'class_prefix' => 'BxOcean',

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
    'language_category' => 'BoonEx Ocean',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
