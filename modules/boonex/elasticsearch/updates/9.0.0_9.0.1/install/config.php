<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'ElasticSearch',
    'version_from' => '9.0.0',
	'version_to' => '9.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '9.0.0-RC2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/elasticsearch/updates/update_9.0.0_9.0.1/',
	'home_uri' => 'elasticsearch_update_900_901',

	'module_dir' => 'boonex/elasticsearch/',
	'module_uri' => 'elasticsearch',

    'db_prefix' => 'bx_els_',
    'class_prefix' => 'BxEls',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 0,
		'clear_db_cache' => 1,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'ElasticSearch',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
