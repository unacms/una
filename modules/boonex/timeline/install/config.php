<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

$aConfig = array(
    /**
     * Main Section.
     */
	'type' => BX_DOL_MODULE_TYPE_MODULE,
	'name' => 'bx_timeline',
    'title' => 'Timeline',
	'note' => 'Timeline module.',
    'version' => '1.0.0',
    'vendor' => 'BoonEx',
    'update_url' => '',

    'compatible_with' => array(
        '8.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/timeline/',
    'home_uri' => 'timeline',

    'db_prefix' => 'bx_timeline_',
    'class_prefix' => 'BxTimeline',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'execute_sql' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),
    'uninstall' => array (
        'execute_sql' => 1,
        'update_languages' => 1,
        'clear_db_cache' => 1,
    ),
    'enable' => array(
        'execute_sql' => 1,
        'recompile_main_menu' => 0,
        'recompile_permalinks' => 1,
        'recompile_alerts' => 1,
        'clear_db_cache' => 1,
    ),
    'disable' => array (
        'execute_sql' => 1,
        'recompile_main_menu' => 0,
        'recompile_permalinks' => 1,
        'recompile_alerts' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Dependencies Section
     */
    'dependencies' => array(),

    /**
     * Category for language keys.
     */
    'language_category' => 'Timeline',

    /**
     * Permissions Section
     */
    'install_permissions' => array(),
    'uninstall_permissions' => array(),

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
