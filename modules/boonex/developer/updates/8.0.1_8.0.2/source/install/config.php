<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_developer',
    'title' => 'Developer',
    'note' => 'Developer tools...',
    'version' => '8.0.2',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '8.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/developer/',
    'home_uri' => 'developer',

    'db_prefix' => 'bx_dev_',
    'class_prefix' => 'BxDev',
    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_languages' => 1,
        'recompile_global_paramaters' => 1,
        'recompile_permalinks' => 1,
        'clear_db_cache' => 1
    ),
    'uninstall' => array (
        'execute_sql' => 1,
        'update_languages' => 1,
        'recompile_global_paramaters' => 1,
        'recompile_permalinks' => 1,
        'clear_db_cache' => 1
    ),
    'enable' => array(
        'execute_sql' => 1,
    ),
    'disable' => array(
        'execute_sql' => 1,
    ),
    /**
     * Dependencies Section
     */
    'dependencies' => array(),
    /**
     * Category for language keys.
     */
    'language_category' => 'BoonEx Developer',
    /**
     * Introduction and Conclusion Section.
     */
    'install_info' => array(
        'introduction' => 'inst_intro.html',
        'conclusion' => 'inst_concl.html'
    ),
    'uninstall_info' => array(
        'introduction' => 'uninst_intro.html',
        'conclusion' => 'uninst_concl.html'
    )
);

/** @} */
