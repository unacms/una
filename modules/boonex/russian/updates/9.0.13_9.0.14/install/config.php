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
    'version_from' => '9.0.13',
	'version_to' => '9.0.14',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/russian/updates/update_9.0.13_9.0.14/',
	'home_uri' => 'ru_update_9013_9014',

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
        array('name' => 'Analytics', 'path' => 'bx_analytics/'),
        array('name' => 'Channels', 'path' => 'bx_channels/'),
        array('name' => 'Conversations', 'path' => 'bx_convos/'),
        array('name' => 'Files', 'path' => 'bx_files/'),
        array('name' => 'Mass mailer', 'path' => 'bx_massmailer/'),
        array('name' => 'Payment', 'path' => 'bx_payment/'),
        array('name' => 'Photos', 'path' => 'bx_photos/'),
        array('name' => 'Spaces', 'path' => 'bx_spaces/'),
        array('name' => 'Timeline', 'path' => 'bx_timeline/'),
        array('name' => 'System', 'path' => 'system/'),
    ),

	/**
     * Files Section
     */
    'delete_files' => array(),
);
