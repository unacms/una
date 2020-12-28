<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Data Fox',
    'version_from' => '9.0.1',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/datafox/updates/update_9.0.1_12.0.0/',
    'home_uri' => 'datafox_update_901_1200',

    'module_dir' => 'boonex/datafox/',
    'module_uri' => 'datafox',

    'db_prefix' => 'bx_datafox_',
    'class_prefix' => 'BxDataFox',

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
    'language_category' => 'Data Fox',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
