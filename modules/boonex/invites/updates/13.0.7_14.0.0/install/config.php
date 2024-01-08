<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Invitations',
    'version_from' => '13.0.7',
    'version_to' => '14.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '14.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/invites/updates/update_13.0.7_14.0.0/',
    'home_uri' => 'invites_update_1307_1400',

    'module_dir' => 'boonex/invites/',
    'module_uri' => 'invites',

    'db_prefix' => 'bx_inv_',
    'class_prefix' => 'BxInv',

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
    'language_category' => 'Invitations',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
