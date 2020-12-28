<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Google connect',
    'version_from' => '10.0.1',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/google_connect/updates/update_10.0.1_12.0.0/',
    'home_uri' => 'googlecon_update_1001_1200',

    'module_dir' => 'boonex/google_connect/',
    'module_uri' => 'googlecon',

    'db_prefix' => 'bx_googlecon_',
    'class_prefix' => 'BxGoogleCon',

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
    'language_category' => 'Google Connect',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
