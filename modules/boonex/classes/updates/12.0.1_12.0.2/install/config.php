<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Classes',
    'version_from' => '12.0.1',
    'version_to' => '12.0.2',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.1.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/classes/updates/update_12.0.1_12.0.2/',
    'home_uri' => 'classes_update_1201_1202',

    'module_dir' => 'boonex/classes/',
    'module_uri' => 'classes',

    'db_prefix' => 'bx_classes_',
    'class_prefix' => 'BxClss',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Classes',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
