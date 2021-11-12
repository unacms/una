<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Discussions',
    'version_from' => '12.0.4',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/forum/updates/update_12.0.4_13.0.0/',
    'home_uri' => 'forum_update_1204_1300',

    'module_dir' => 'boonex/forum/',
    'module_uri' => 'forum',

    'db_prefix' => 'bx_forum_',
    'class_prefix' => 'BxForum',

    /**
     * Transcoders.
     */
    'transcoders' => array(
        'bx_forum_preview_photos',
        'bx_forum_gallery_photos',
        'bx_forum_view_photos',

        'bx_forum_videos_poster',
        'bx_forum_videos_poster_preview',
        'bx_forum_videos_mp4',
        'bx_forum_videos_mp4_hd',
        
        'bx_forum_preview_files',
        'bx_forum_gallery_files',
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
    'language_category' => 'Discussions',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/css/polls.css',
        'template/unit.html',
        'template/unit_full.html',
        'template/unit_full_private.html',
        'template/unit_gallery.html',
        'template/unit_gallery_private.html',
        'template/unit_live_search.html',
        'template/unit_private.html',
        'template/unit_showcase.html',
    ),
);
