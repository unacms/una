<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Google Tag Manager',
    'version_from' => '9.0.1',
	'version_to' => '11.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '11.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/google_tagmanager/updates/update_9.0.1_11.0.0/',
	'home_uri' => 'google_tagmanager_update_901_1100',

	'module_dir' => 'boonex/google_tagmanager/',
	'module_uri' => 'google_tagmanager',

    'db_prefix' => 'bx_googletagman_',
    'class_prefix' => 'BxGoogleTagMan',

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
    'language_category' => 'Google Tag Manager',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
