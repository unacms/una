<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Tasks',
    'version_from' => '12.0.2',
    'version_to' => '12.0.3',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.1.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/tasks/updates/update_12.0.2_12.0.3/',
    'home_uri' => 'tasks_update_1202_1203',

    'module_dir' => 'boonex/tasks/',
    'module_uri' => 'tasks',

    'db_prefix' => 'bx_tasks_',
    'class_prefix' => 'BxTasks',

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
    'language_category' => 'Tasks',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
