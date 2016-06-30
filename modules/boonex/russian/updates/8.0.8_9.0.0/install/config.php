<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Russian',
    'version_from' => '8.0.8',
	'version_to' => '9.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_8.0.8_9.0.0/',
	'home_uri' => 'ru_update_808_900',

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
		array('name' => 'Conversations', 'path' => 'bx_convos/'),
		array('name' => 'Invitations', 'path' => 'bx_invites/'),
		array('name' => 'Notifications', 'path' => 'bx_notifications/'),
		array('name' => 'Organizations', 'path' => 'bx_organizations/'),
		array('name' => 'Persons', 'path' => 'bx_persons/'),
		array('name' => 'Posts', 'path' => 'bx_posts/'),
		array('name' => 'SMTP Mailer', 'path' => 'bx_smtp/'),
		array('name' => 'Timeline', 'path' => 'bx_timeline/'),
		array('name' => 'System', 'path' => 'system/'),
    ),

	/**
     * Files Section
     */
    'delete_files' => array(
		'data/langs/bx_lagoon/ru.xml',
		'data/langs/bx_lagoon/',
	),
);
