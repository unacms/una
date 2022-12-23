<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Persons',
    'version_from' => '13.0.5',
    'version_to' => '13.0.6',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/persons/updates/update_13.0.5_13.0.6/',
    'home_uri' => 'persons_update_1305_1306',

    'module_dir' => 'boonex/persons/',
    'module_uri' => 'persons',

    'db_prefix' => 'bx_persons_',
    'class_prefix' => 'BxPersons',

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
    'language_category' => 'Persons',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/css/manage_tools.css',
        'template/account_link.html',
        'template/badges.html',
        'template/entry-all-actions.html',
        'template/entry-location.html',
        'template/entry-share.html',
        'template/entry-text.html',
        'template/image_popup.html',
        'template/menu_main_submenu.html',
        'template/name_link.html',
        'template/picture_preview.html',
        'template/search_extended_results.html',
        'template/set_acl_popup.html',
        'template/uploader_form_crop_cover.html',
    ),
);
