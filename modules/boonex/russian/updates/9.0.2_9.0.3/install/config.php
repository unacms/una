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
    'version_from' => '9.0.2',
	'version_to' => '9.0.3',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_9.0.2_9.0.3/',
	'home_uri' => 'ru_update_902_903',

	'module_dir' => 'boonex/russian/',
	'module_uri' => 'ru',

    'db_prefix' => 'bx_rsn_',
    'class_prefix' => 'BxRsn',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'restore_languages' => 0,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => array(
		array('name' => 'Paid Levels', 'path' => 'bx_acl/'),
		array('name' => 'Albums', 'path' => 'bx_albums/'),
		array('name' => 'Antispam', 'path' => 'bx_antispam/'),
		array('name' => 'RocketChat', 'path' => 'bx_chat_plus/'),
		array('name' => 'Contact', 'path' => 'bx_contact/'),
		array('name' => 'Conversations', 'path' => 'bx_convos/'),
		array('name' => 'Events', 'path' => 'bx_events/'),
		array('name' => 'Facebook connect', 'path' => 'bx_facebook/'),
		array('name' => 'Discussions', 'path' => 'bx_forum/'),
		array('name' => 'Google connect', 'path' => 'bx_googlecon/'),
		array('name' => 'Groups', 'path' => 'bx_groups/'),
		array('name' => 'Invitations', 'path' => 'bx_invites/'),
		array('name' => 'LinkedIn connect', 'path' => 'bx_linkedin/'),
		array('name' => 'Market', 'path' => 'bx_market/'),
		array('name' => 'Notifications', 'path' => 'bx_notifications/'),
		array('name' => 'OAuth2 Server', 'path' => 'bx_oauth/'),
		array('name' => 'Organizations', 'path' => 'bx_organizations/'),
		array('name' => 'Payment', 'path' => 'bx_payment/'),
		array('name' => 'Persons', 'path' => 'bx_persons/'),
		array('name' => 'Posts', 'path' => 'bx_posts/'),
		array('name' => 'Sites', 'path' => 'bx_sites/'),
		array('name' => 'Twitter connect', 'path' => 'bx_twitter/'),
		array('name' => 'System', 'path' => 'system/'),
    ),

	/**
     * Files Section
     */
    'delete_files' => array(
		'data/langs/bx_tricon/ru.xml',
		'data/langs/bx_tricon/',
		'template/images/icons/std-mi.png',
		'template/images/icons/std-pi.png',
		'template/images/icons/std-si.png',
		'template/images/icons/std-wi.png'
	),
);
