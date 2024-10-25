<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Groups',
    'version_from' => '14.0.3',
    'version_to' => '14.0.4',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '14.0.0-RC2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/groups/updates/update_14.0.3_14.0.4/',
    'home_uri' => 'groups_update_1403_1404',

    'module_dir' => 'boonex/groups/',
    'module_uri' => 'groups',

    'db_prefix' => 'bx_groups_',
    'class_prefix' => 'BxGroups',

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
    'language_category' => 'Groups',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
