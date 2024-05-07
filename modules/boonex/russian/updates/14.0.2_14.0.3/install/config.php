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
    'version_from' => '14.0.2',
    'version_to' => '14.0.3',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '14.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_14.0.2_14.0.3/',
    'home_uri' => 'ru_update_1402_1403',

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
        array('name' => 'Accounts', 'path' => 'bx_accounts/'),
        array('name' => 'Boonex Artificer Template', 'path' => 'bx_artificer/'),
        array('name' => 'Events', 'path' => 'bx_events/'),
        array('name' => 'Invitations', 'path' => 'bx_invites/'),
        array('name' => 'Organizations', 'path' => 'bx_organizations/'),
        array('name' => 'Persons', 'path' => 'bx_persons/'),
        array('name' => 'Timeline', 'path' => 'bx_timeline/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

    /**
     * Files Section
     */
    'delete_files' => array(),
);
