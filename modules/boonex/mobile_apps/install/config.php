<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MobileApps Mobile Apps
 * @ingroup     UnaModules
 *
 * @{
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_mobileapps',
    'title' => 'Mobile Apps',
    'note' => 'Mobile Apps.',
    'version' => '9.0.0.DEV',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.una.io/?section={module_name}',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/mobile_apps/',
    'home_uri' => 'mobileapps',

    'db_prefix' => 'bx_mobileapps_',
    'class_prefix' => 'BxMobileApps',

    /**
     * Category for language keys.
     */
    'language_category' => 'Mobile Apps',
    
    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_languages' => 1,
    ),
    'uninstall' => array (
        'execute_sql' => 1,
        'update_languages' => 1,
    ),
    'enable' => array(
        'execute_sql' => 1,
        'clear_db_cache' => 1,
    ),
    'disable' => array(
        'execute_sql' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Dependencies Section
     */
    'dependencies' => array(),
);

/** @} */
