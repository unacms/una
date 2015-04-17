<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Contact',
    'version_from' => '8.0.3',
	'version_to' => '8.0.4',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/contact/updates/update_8.0.3_8.0.4/',
	'home_uri' => 'contact_update_803_804',

	'module_dir' => 'boonex/contact/',
	'module_uri' => 'contact',

    'db_prefix' => 'bx_contact_',
    'class_prefix' => 'BxContact',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 1,
		'clear_db_cache' => 0,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Contact',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
