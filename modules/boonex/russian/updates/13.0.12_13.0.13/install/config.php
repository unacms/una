<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Russian',
    'version_from' => '13.0.12',
    'version_to' => '13.0.13',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.1.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_13.0.12_13.0.13/',
    'home_uri' => 'ru_update_13012_13013',

    'module_dir' => 'boonex/russian/',
    'module_uri' => 'ru',

    'db_prefix' => 'bx_rsn_',
    'class_prefix' => 'BxRsn',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 1,
        'restore_languages' => 0,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => array(
        array('name' => 'Boonex Decorous Template', 'path' => 'bx_decorous/'),
        array('name' => 'Events', 'path' => 'bx_events/'),
        array('name' => 'Groups', 'path' => 'bx_groups/'),
        array('name' => 'Market', 'path' => 'bx_market/'),
        array('name' => 'BoonEx Ocean', 'path' => 'bx_ocean/'),
        array('name' => 'Timeline', 'path' => 'bx_timeline/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

    /**
     * Files Section
     */
    'delete_files' => array(),
);
