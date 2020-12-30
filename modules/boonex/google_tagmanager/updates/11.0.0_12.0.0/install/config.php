<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Google Tag Manager',
    'version_from' => '11.0.0',
    'version_to' => '12.0.0',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/google_tagmanager/updates/update_11.0.0_12.0.0/',
    'home_uri' => 'google_tagmanager_update_1100_1200',

    'module_dir' => 'boonex/google_tagmanager/',
    'module_uri' => 'google_tagmanager',

    'db_prefix' => 'bx_googletagman_',
    'class_prefix' => 'BxGoogleTagMan',

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
    'language_category' => 'Google Tag Manager',
    
    /**
     * Files Section
     */
    'delete_files' => array(),
);
