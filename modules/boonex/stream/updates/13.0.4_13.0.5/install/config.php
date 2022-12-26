<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Stream',
    'version_from' => '13.0.4',
    'version_to' => '13.0.5',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-RC1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/stream/updates/update_13.0.4_13.0.5/',
    'home_uri' => 'stream_update_1304_1305',

    'module_dir' => 'boonex/stream/',
    'module_uri' => 'stream',

    'db_prefix' => 'bx_stream_',
    'class_prefix' => 'BxStrm',

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
    'language_category' => 'Stream',

    /**
     * Files Section
     */
    'delete_files' => array(
        'classes/BxStrmUploaderSimple.php',
        'template/css/categories.css',
        'template/css/manage_tools.css',
        'template/author.html',
        'template/author_link.html',
        'template/badges.html',
        'template/category_list_inline.html',
        'template/context.html',
        'template/entry-all-actions.html',
        'template/entry-location.html',
        'template/entry-share.html',
        'template/entry-text.html',
        'template/form_categories.html',
        'template/form_ghost_template_cover.html',
        'template/title_link.html',
        'template/uploader_button_html5_attach.html',
        'template/uploader_button_simple_attach.html',
    ),
);
