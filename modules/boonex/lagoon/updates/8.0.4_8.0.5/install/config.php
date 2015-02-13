<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Lagoon',
    'version_from' => '8.0.4',
	'version_to' => '8.0.5',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.0.A11'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/lagoon/updates/update_8.0.4_8.0.5/',
	'home_uri' => 'lagoon_update_804_805',

	'module_dir' => 'boonex/lagoon/',
	'module_uri' => 'lagoon',

    'db_prefix' => 'bx_lagoon_',
    'class_prefix' => 'BxLagoon',

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
    'language_category' => 'BoonEx Lagoon',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
