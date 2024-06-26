<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Stream',
    'version_from' => '14.0.0',
    'version_to' => '14.0.1',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '14.0.0-B2'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/stream/updates/update_14.0.0_14.0.1/',
    'home_uri' => 'stream_update_1400_1401',

    'module_dir' => 'boonex/stream/',
    'module_uri' => 'stream',

    'db_prefix' => 'bx_stream_',
    'class_prefix' => 'BxStrm',

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
    'language_category' => 'Stream',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
