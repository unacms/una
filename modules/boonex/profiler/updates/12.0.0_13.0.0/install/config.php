<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Profiler',
    'version_from' => '12.0.0',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-B4'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/profiler/updates/update_12.0.0_13.0.0/',
    'home_uri' => 'profiler_update_1200_1300',

    'module_dir' => 'boonex/profiler/',
    'module_uri' => 'profiler',

    'db_prefix' => 'bx_profiler_',
    'class_prefix' => 'BxProfiler',

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
    'language_category' => 'Boonex Profiler',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
