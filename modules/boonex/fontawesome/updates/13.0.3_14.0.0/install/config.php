<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Font Awesome Pro',
    'version_from' => '13.0.3',
    'version_to' => '14.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '14.0.0'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/fontawesome/updates/update_13.0.3_14.0.0/',
    'home_uri' => 'fontawesome_update_1303_1400',

    'module_dir' => 'boonex/fontawesome/',
    'module_uri' => 'fontawesome',

    'db_prefix' => 'bx_fontawesome_',
    'class_prefix' => 'BxFontAwesome',

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
    'language_category' => 'FontAwesome',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
