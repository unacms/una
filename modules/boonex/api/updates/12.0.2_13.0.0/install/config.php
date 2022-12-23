<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'API',
    'version_from' => '12.0.2',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/api/updates/update_12.0.2_13.0.0/',
    'home_uri' => 'api_update_1202_1300',

    'module_dir' => 'boonex/api/',
    'module_uri' => 'api',

    'db_prefix' => 'bx_api_',
    'class_prefix' => 'BxApi',

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
    'language_category' => 'API',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
