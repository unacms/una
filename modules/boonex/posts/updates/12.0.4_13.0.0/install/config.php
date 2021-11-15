<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Posts',
    'version_from' => '12.0.4',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/posts/updates/update_12.0.4_13.0.0/',
    'home_uri' => 'posts_update_1204_1300',

    'module_dir' => 'boonex/posts/',
    'module_uri' => 'posts',

    'db_prefix' => 'bx_posts_',
    'class_prefix' => 'BxPosts',

    /**
     * Transcoders.
     */
    'transcoders' => array(
        'bx_posts_view_photos',
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'register_transcoders' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Posts',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/css/forms.css',
        'template/css/main.css',
        'template/css/polls.css',
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
