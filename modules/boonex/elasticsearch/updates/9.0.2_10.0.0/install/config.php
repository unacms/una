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
    'version_from' => '9.0.2',
    'version_to' => '10.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '10.0.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/elasticsearch/updates/update_9.0.2_10.0.0/',
    'home_uri' => 'elasticsearch_update_902_1000',

    'module_dir' => 'boonex/elasticsearch/',
    'module_uri' => 'elasticsearch',

    'db_prefix' => 'bx_els_',
    'class_prefix' => 'BxEls',

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
    'language_category' => 'ElasticSearch',

    /**
     * Files Section
     */
    'delete_files' => array(
        'template/css/'
    ),
);
