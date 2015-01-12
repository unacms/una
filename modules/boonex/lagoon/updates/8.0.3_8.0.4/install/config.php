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
    'version_from' => '8.0.3',
	'version_to' => '8.0.4',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/lagoon/updates/update_8.0.3_8.0.4/',
	'home_uri' => 'lagoon_update_803_804',

	'module_dir' => 'boonex/lagoon/',
	'module_uri' => 'lagoon',

    'db_prefix' => 'bx_lagoon_',
    'class_prefix' => 'BxLagoon',

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
    'language_category' => 'BoonEx Lagoon',

	/**
     * Files Section
     */
    'delete_files' => array(
		'data/template/system/scripts/BxTemplCmtsMenu.php',
    ),
);
