<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Organizations',
    'version_from' => '14.0.2',
    'version_to' => '14.0.3',
    'vendor' => 'UNA INC',

    'compatible_with' => array(
        '14.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/organizations/updates/update_14.0.2_14.0.3/',
    'home_uri' => 'orgs_update_1402_1403',

    'module_dir' => 'boonex/organizations/',
    'module_uri' => 'orgs',

    'db_prefix' => 'bx_organizations_',
    'class_prefix' => 'BxOrgs',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_files' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Category for language keys.
     */
    'language_category' => 'Organizations',

    /**
     * Files Section
     */
    'delete_files' => array(),
);
