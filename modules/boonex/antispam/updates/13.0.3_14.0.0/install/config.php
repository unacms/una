<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Antispam',
    'version_from' => '13.0.3',
    'version_to' => '14.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '14.0.0-A2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/antispam/updates/update_13.0.3_14.0.0/',
    'home_uri' => 'antispam_update_1303_1400',

    'module_dir' => 'boonex/antispam/',
    'module_uri' => 'antispam',

    'db_prefix' => 'bx_antispam_',
    'class_prefix' => 'BxAntispam',

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
    'language_category' => 'Antispam',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
