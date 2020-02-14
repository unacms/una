<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Timeline',
    'version_from' => '10.0.5',
    'version_to' => '11.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '11.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/timeline/updates/update_10.0.5_11.0.0/',
    'home_uri' => 'timeline_update_1005_1100',

    'module_dir' => 'boonex/timeline/',
    'module_uri' => 'timeline',

    'db_prefix' => 'bx_timeline_',
    'class_prefix' => 'BxTimeline',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'update_relations' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Timeline',

    /**
     * Files Section
     */
    'delete_files' => array(),

    /**
     * Connections Section
     */
    'relations' => array(
        'bx_notifications'
    )
);
