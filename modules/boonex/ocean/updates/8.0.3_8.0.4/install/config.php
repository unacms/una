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
    'version_from' => '8.0.3',
	'version_to' => '8.0.4',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.0.A10'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/ocean/updates/update_8.0.3_8.0.4/',
	'home_uri' => 'ocean_update_803_804',

	'module_dir' => 'boonex/ocean/',
	'module_uri' => 'ocean',

    'db_prefix' => 'bx_ocean_',
    'class_prefix' => 'BxOcean',

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
    'language_category' => 'BoonEx Ocean',

	/**
     * Files Section
     */
    'delete_files' => array(
    	'data/template/bx_convos/css/main.css',
		'data/template/bx_organizations/css/main.css',
		'data/template/bx_persons/css/main.css',
		'data/template/system/scripts/BxTemplCmtsMenu.php',
		'data/template/system/menu_custom.html'
    ),
);
