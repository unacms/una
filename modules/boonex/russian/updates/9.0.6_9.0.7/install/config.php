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
    'version_from' => '9.0.6',
	'version_to' => '9.0.7',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_9.0.6_9.0.7/',
	'home_uri' => 'ru_update_906_907',

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
		array('name' => 'RocketChat', 'path' => 'bx_chat_plus/'),
		array('name' => 'Contact', 'path' => 'bx_contact/'),
		array('name' => 'Conversations', 'path' => 'bx_convos/'),
		array('name' => 'Decorous', 'path' => 'bx_decorous/'),
		array('name' => 'Developer', 'path' => 'bx_developer/'),
		array('name' => 'Dolphin connect', 'path' => 'bx_dolcon/'),
		array('name' => 'Events', 'path' => 'bx_events/'),
		array('name' => 'Files', 'path' => 'bx_files/'),
		array('name' => 'Discussions', 'path' => 'bx_forum/'),
		array('name' => 'Google connect', 'path' => 'bx_googlecon/'),
		array('name' => 'Groups', 'path' => 'bx_groups/'),
		array('name' => 'LinkedIn connect', 'path' => 'bx_linkedin/'),
		array('name' => 'Market', 'path' => 'bx_market/'),
		array('name' => 'Notifications', 'path' => 'bx_notifications/'),
		array('name' => 'OAuth2 Server', 'path' => 'bx_oauth/'),
		array('name' => 'Organizations', 'path' => 'bx_organizations/'),
		array('name' => 'Payment', 'path' => 'bx_payment/'),
		array('name' => 'Persons', 'path' => 'bx_persons/'),
		array('name' => 'Polls', 'path' => 'bx_polls/'),
		array('name' => 'Posts', 'path' => 'bx_posts/'),
		array('name' => 'Protean', 'path' => 'bx_protean/'),
		array('name' => 'SocialEngine Migration', 'path' => 'bx_se_migration/'),
		array('name' => 'Shopify', 'path' => 'bx_shopify/'),
		array('name' => 'SMTP Mailer', 'path' => 'bx_smtp/'),
		array('name' => 'Snipcart', 'path' => 'bx_snipcart/'),
		array('name' => 'Timeline', 'path' => 'bx_timeline/'),
		array('name' => 'Twitter connect', 'path' => 'bx_twitter/'),
		array('name' => 'System', 'path' => 'system/'),
    ),

	/**
     * Files Section
     */
    'delete_files' => array(),
);
