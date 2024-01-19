<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Donations',
    'version_from' => '13.0.5',
    'version_to' => '14.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '14.0.0-A2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/donations/updates/update_13.0.5_14.0.0/',
    'home_uri' => 'donations_update_1305_1400',

    'module_dir' => 'boonex/donations/',
    'module_uri' => 'donations',

    'db_prefix' => 'bx_donations_',
    'class_prefix' => 'BxDonations',

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
    'language_category' => 'Donations',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
