<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Protean',
    'version_from' => '13.0.0',
    'version_to' => '13.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A3'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/protean/updates/update_13.0.0_13.0.1/',
    'home_uri' => 'protean_update_1300_1301',

    'module_dir' => 'boonex/protean/',
    'module_uri' => 'protean',

    'db_prefix' => 'bx_protean_',
    'class_prefix' => 'BxProtean',

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
    'language_category' => 'Boonex Protean Template',

    /**
     * Files Section
     */
    'delete_files' => array(
        'data/template/system/menu_main_submenu_bar.html',
    ),
);
