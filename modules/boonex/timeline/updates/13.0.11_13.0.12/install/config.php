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
    'version_from' => '13.0.11',
    'version_to' => '13.0.12',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.1.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/timeline/updates/update_13.0.11_13.0.12/',
    'home_uri' => 'timeline_update_13011_13012',

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
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Timeline',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/divider.html',
    ),
);
