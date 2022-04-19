<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Persons',
    'version_from' => '13.0.0',
    'version_to' => '13.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/persons/updates/update_13.0.0_13.0.1/',
    'home_uri' => 'persons_update_1300_1301',

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
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Persons',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
