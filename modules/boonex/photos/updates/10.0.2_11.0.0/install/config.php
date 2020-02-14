<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Photos',
    'version_from' => '10.0.2',
    'version_to' => '11.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '11.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/photos/updates/update_10.0.2_11.0.0/',
    'home_uri' => 'photos_update_1002_1100',

    'module_dir' => 'boonex/photos/',
    'module_uri' => 'photos',

    'db_prefix' => 'bx_photos_',
    'class_prefix' => 'BxPhotos',

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
    'language_category' => 'Photos',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
