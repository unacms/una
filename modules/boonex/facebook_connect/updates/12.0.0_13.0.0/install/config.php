<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Facebook connect',
    'version_from' => '12.0.0',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/facebook_connect/updates/update_12.0.0_13.0.0/',
    'home_uri' => 'facebook_connect_update_1200_1300',

    'module_dir' => 'boonex/facebook_connect/',
    'module_uri' => 'facebook_connect',

    'db_prefix' => 'bx_facebook_',
    'class_prefix' => 'BxFaceBookConnect',

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
    'language_category' => 'Facebook',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
