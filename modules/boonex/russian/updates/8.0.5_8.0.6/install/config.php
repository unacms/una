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
    'version_from' => '8.0.5',
	'version_to' => '8.0.6',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '8.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_8.0.5_8.0.6/',
	'home_uri' => 'ru_update_805_806',

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
    	array('name' => 'Contact', 'path' => 'bx_contact/'),
		array('name' => 'Conversations', 'path' => 'bx_convos/'),
		array('name' => 'Organizations', 'path' => 'bx_organizations/'),
		array('name' => 'Persons', 'path' => 'bx_persons/'),
		array('name' => 'Posts', 'path' => 'bx_posts/'),
		array('name' => 'System', 'path' => 'system/'),
    ),

	/**
     * Files Section
     */
    'delete_files' => array(
		'data/langs/accounts.xml',
		'data/langs/albums.xml',
		'data/langs/antispam.xml',
		'data/langs/contact.xml',
		'data/langs/convos.xml',
		'data/langs/developer.xml',
		'data/langs/en.xml',
		'data/langs/invites.xml',
		'data/langs/lagoon.xml',
		'data/langs/notifications.xml',
		'data/langs/ocean.xml',
		'data/langs/orgs.xml',
		'data/langs/persons.xml',
		'data/langs/posts.xml',
		'data/langs/profiler.xml',
		'data/langs/ru.xml',
		'data/langs/sites.xml',
		'data/langs/smtpmailer.xml',
		'data/langs/system.xml',
		'data/langs/timeline.xml'
	),
);
