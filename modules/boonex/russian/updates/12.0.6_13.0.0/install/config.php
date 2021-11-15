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
    'version_from' => '12.0.6',
    'version_to' => '13.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '13.0.0-A1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_12.0.6_13.0.0/',
    'home_uri' => 'ru_update_1206_1300',

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
        array('name' => 'Accounts', 'path' => 'bx_accounts/'),
        array('name' => 'Ads', 'path' => 'bx_ads/'),
        array('name' => 'Albums', 'path' => 'bx_albums/'),
        array('name' => 'Analytics', 'path' => 'bx_analytics/'),
        array('name' => 'Channels', 'path' => 'bx_channels/'),
        array('name' => 'Classes', 'path' => 'bx_classes/'),
        array('name' => 'Courses', 'path' => 'bx_courses/'),
        array('name' => 'Events', 'path' => 'bx_events/'),
        array('name' => 'Files', 'path' => 'bx_files/'),
        array('name' => 'Discussions', 'path' => 'bx_forum/'),
        array('name' => 'Glossary', 'path' => 'bx_glossary/'),
        array('name' => 'Groups', 'path' => 'bx_groups/'),
        array('name' => 'Market', 'path' => 'bx_market/'),
        array('name' => 'Organizations', 'path' => 'bx_organizations/'),
        array('name' => 'Payment', 'path' => 'bx_payment/'),
        array('name' => 'Persons', 'path' => 'bx_persons/'),
        array('name' => 'Photos', 'path' => 'bx_photos/'),
        array('name' => 'Polls', 'path' => 'bx_polls/'),
        array('name' => 'Posts', 'path' => 'bx_posts/'),
        array('name' => 'Shopify', 'path' => 'bx_shopify/'),
        array('name' => 'Snipcart', 'path' => 'bx_snipcart/'),
        array('name' => 'Spaces', 'path' => 'bx_spaces/'),
        array('name' => 'Tasks', 'path' => 'bx_tasks/'),
        array('name' => 'Timeline', 'path' => 'bx_timeline/'),
        array('name' => 'Videos', 'path' => 'bx_videos/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

    /**
     * Files Section
     */
    'delete_files' => array(),
);
