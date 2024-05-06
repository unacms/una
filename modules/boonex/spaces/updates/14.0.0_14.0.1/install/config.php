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
    'version_from' => '14.0.0',
    'version_to' => '14.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '14.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/spaces/updates/update_14.0.0_14.0.1/',
    'home_uri' => 'spaces_update_1400_1401',

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
        'update_languages' => 0,
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
