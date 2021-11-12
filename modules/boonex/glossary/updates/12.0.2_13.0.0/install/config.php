<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Glossary',
    'version_from' => '12.0.2',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/glossary/updates/update_12.0.2_13.0.0/',
    'home_uri' => 'glossary_update_1202_1300',

    'module_dir' => 'boonex/glossary/',
    'module_uri' => 'glossary',

    'db_prefix' => 'bx_glossary_',
    'class_prefix' => 'BxGlsr',

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
    'language_category' => 'Glossary',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/css/forms.css',
        'template/css/main.css',
        'template/unit.html',
        'template/unit_full.html',
        'template/unit_full_private.html',
        'template/unit_gallery.html',
        'template/unit_gallery_private.html',
        'template/unit_live_search.html',
        'template/unit_meta_item.html',
        'template/unit_private.html',
        'template/unit_showcase.html',
    ),
);
