<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Persons',
    'version_from' => '8.0.7',
	'version_to' => '9.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/persons/updates/update_8.0.7_9.0.0/',
	'home_uri' => 'persons_update_807_900',

	'module_dir' => 'boonex/persons/',
	'module_uri' => 'persons',

    'db_prefix' => 'bx_persons_',
    'class_prefix' => 'BxPersons',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
    	'update_relations' => 1,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Persons',

    /**
     * Relations Section
     */
    'relations' => array(
    	'bx_notifications'
    ),

	/**
     * Files Section
     */
    'delete_files' => array(
		'classes/BxPersonsMenuViewActions.php',
		'template/images/no-picture-cover.png'
	),
);
