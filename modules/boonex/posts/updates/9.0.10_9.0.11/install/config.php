<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Posts',
    'version_from' => '9.0.10',
	'version_to' => '9.0.11',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC13'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/posts/updates/update_9.0.10_9.0.11/',
	'home_uri' => 'posts_update_9010_9011',

	'module_dir' => 'boonex/posts/',
	'module_uri' => 'posts',

    'db_prefix' => 'bx_posts_',
    'class_prefix' => 'BxPosts',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 0,
        'clear_db_cache' => 0,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Posts',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
