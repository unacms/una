<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Help Tours',
    'version_from' => '13.0.1',
    'version_to' => '14.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '14.0.0-RC3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/help_tours/updates/update_13.0.1_14.0.0/',
    'home_uri' => 'help_tours_update_1301_1400',

    'module_dir' => 'boonex/help_tours/',
    'module_uri' => 'help_tours',

    'db_prefix' => 'bx_help_tours_',
    'class_prefix' => 'BxHelpTours',

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
    'language_category' => 'Help Tours',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
