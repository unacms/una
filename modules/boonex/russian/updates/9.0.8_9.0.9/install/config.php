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
    'version_from' => '9.0.8',
	'version_to' => '9.0.9',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_9.0.8_9.0.9/',
	'home_uri' => 'ru_update_908_909',

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
		array('name' => 'Conversations', 'path' => 'bx_convos/'),
		array('name' => 'Decorous', 'path' => 'bx_decorous/'),
		array('name' => 'Events', 'path' => 'bx_events/'),
		array('name' => 'Discussions', 'path' => 'bx_forum/'),
		array('name' => 'Groups', 'path' => 'bx_groups/'),
		array('name' => 'Market', 'path' => 'bx_market/'),
		array('name' => 'Notifications', 'path' => 'bx_notifications/'),
		array('name' => 'Organizations', 'path' => 'bx_organizations/'),
		array('name' => 'Payment', 'path' => 'bx_payment/'),
		array('name' => 'Persons', 'path' => 'bx_persons/'),
		array('name' => 'Polls', 'path' => 'bx_polls/'),
		array('name' => 'Protean', 'path' => 'bx_protean/'),
		array('name' => 'Timeline', 'path' => 'bx_timeline/'),
		array('name' => 'Videos', 'path' => 'bx_videos/'),
		array('name' => 'System', 'path' => 'system/'),
    ),

	/**
     * Files Section
     */
    'delete_files' => array(),
);
