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
    'version_from' => '14.0.1',
    'version_to' => '14.0.2',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '14.0.0-A3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_14.0.1_14.0.2/',
    'home_uri' => 'ru_update_1401_1402',

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
        array('name' => 'Ads', 'path' => 'bx_ads/'),
        array('name' => 'Events', 'path' => 'bx_events/'),
        array('name' => 'Groups', 'path' => 'bx_groups/'),
        array('name' => 'Market', 'path' => 'bx_market/'),
        array('name' => 'Notifications', 'path' => 'bx_notifications/'),
        array('name' => 'Organizations', 'path' => 'bx_organizations/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

    /**
     * Files Section
     */
    'delete_files' => array(),
);
