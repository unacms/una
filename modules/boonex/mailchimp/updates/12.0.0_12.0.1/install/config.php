<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Mailchimp',
    'version_from' => '12.0.0',
    'version_to' => '12.0.1',
    'vendor' => 'BoonEx',

    'compatible_with' => array(
        '12.1.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/mailchimp/updates/update_12.0.0_12.0.1/',
    'home_uri' => 'mailchimp_update_1200_1201',
    
    'module_dir' => 'boonex/mailchimp/',
    'module_uri' => 'mailchimp',

    'db_prefix' => 'bx_mailchimp_',
    'class_prefix' => 'BxMailchimp',

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
    'language_category' => 'Mailchimp',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
