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
    'version_from' => '9.0.8',
	'version_to' => '9.0.9',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC9'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/posts/updates/update_9.0.8_9.0.9/',
	'home_uri' => 'posts_update_908_909',

	'module_dir' => 'boonex/posts/',
	'module_uri' => 'posts',

    'db_prefix' => 'bx_posts_',
    'class_prefix' => 'BxPosts',

	/**
     * Storage objects to automatically delete files from upon module uninstallation.
     * Note. Don't add storage objects used in transcoder objects.
     */
    'storages' => array(
    	'bx_posts_photos'
    ),

	/**
     * Transcoders.
     */
    'transcoders' => array(
        'bx_posts_preview_photos',
        'bx_posts_gallery_photos'
    ),

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
		'register_transcoders' => 1,
		'clear_db_cache' => 1,
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
