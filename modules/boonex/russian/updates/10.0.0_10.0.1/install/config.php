<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Russian',
    'version_from' => '10.0.0',
    'version_to' => '10.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '10.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_10.0.0_10.0.1/',
    'home_uri' => 'ru_update_1000_1001',

    'module_dir' => 'boonex/russian/',
    'module_uri' => 'ru',

    'db_prefix' => 'bx_rsn_',
    'class_prefix' => 'BxRsn',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 1,
        'restore_languages' => 0,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => array(
        array('name' => 'Albums', 'path' => 'bx_albums/'),
        array('name' => 'Files', 'path' => 'bx_files/'),
        array('name' => 'Discussions', 'path' => 'bx_forum/'),
        array('name' => 'Glossary', 'path' => 'bx_glossary/'),
        array('name' => 'Market', 'path' => 'bx_market/'),
        array('name' => 'Photos', 'path' => 'bx_photos/'),
        array('name' => 'Polls', 'path' => 'bx_polls/'),
        array('name' => 'Posts', 'path' => 'bx_posts/'),
        array('name' => 'Shopify', 'path' => 'bx_shopify/'),
        array('name' => 'Snipcart', 'path' => 'bx_snipcart/'),
        array('name' => 'Timeline', 'path' => 'bx_timeline/'),
        array('name' => 'Videos', 'path' => 'bx_videos/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

    /**
     * Files Section
     */
    'delete_files' => array(),
);
