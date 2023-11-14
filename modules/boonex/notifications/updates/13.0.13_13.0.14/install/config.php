<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Notifications',
    'version_from' => '13.0.13',
    'version_to' => '13.0.14',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.1.0-RC3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/notifications/updates/update_13.0.13_13.0.14/',
    'home_uri' => 'notifications_update_13013_13014',

    'module_dir' => 'boonex/notifications/',
    'module_uri' => 'notifications',

    'db_prefix' => 'bx_notifications_',
    'class_prefix' => 'BxNtfs',

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
    'language_category' => 'Notifications',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
