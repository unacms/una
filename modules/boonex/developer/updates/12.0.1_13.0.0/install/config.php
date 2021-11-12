<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Developer',
    'version_from' => '12.0.1',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/developer/updates/update_12.0.1_13.0.0/',
    'home_uri' => 'developer_update_1201_1300',

    'module_dir' => 'boonex/developer/',
    'module_uri' => 'developer',

    'db_prefix' => 'bx_dev_',
    'class_prefix' => 'BxDev',

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
    'language_category' => 'BoonEx Developer',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/menu_main.html'
    ),
);
