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
    'version_from' => '8.0.7',
	'version_to' => '8.0.8',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '8.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_8.0.7_8.0.8/',
	'home_uri' => 'ru_update_807_808',

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
		array('name' => 'Developer', 'path' => 'bx_developer/'),
		array('name' => 'Invitations', 'path' => 'bx_invites/'),
		array('name' => 'Notifications', 'path' => 'bx_notifications/'),
		array('name' => 'Posts', 'path' => 'bx_posts/'),
		array('name' => 'Timeline', 'path' => 'bx_timeline/'),
		array('name' => 'System', 'path' => 'system/'),
    ),

	/**
     * Files Section
     */
    'delete_files' => array(),
);
