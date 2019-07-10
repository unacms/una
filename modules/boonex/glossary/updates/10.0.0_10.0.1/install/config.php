<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Glossary',
    'version_from' => '10.0.0',
    'version_to' => '10.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '10.0.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/glossary/updates/update_10.0.0_10.0.1/',
    'home_uri' => 'glossary_update_1000_1001',

    'module_dir' => 'boonex/glossary/',
    'module_uri' => 'glossary',

    'db_prefix' => 'bx_glossary_',
    'class_prefix' => 'BxGlsr',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 0,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Glossary',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
