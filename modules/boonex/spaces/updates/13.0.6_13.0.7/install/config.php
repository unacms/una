<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Spaces',
    'version_from' => '13.0.6',
    'version_to' => '13.0.7',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC6'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/spaces/updates/update_13.0.6_13.0.7/',
    'home_uri' => 'spaces_update_1306_1307',

    'module_dir' => 'boonex/spaces/',
    'module_uri' => 'spaces',

    'db_prefix' => 'bx_spaces_',
    'class_prefix' => 'BxSpaces',

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
    'language_category' => 'Spaces',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
