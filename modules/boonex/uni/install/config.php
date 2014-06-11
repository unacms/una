<?php 
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Uni Uni template
 * @ingroup     DolphinModules
 *
 * @{
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_TEMPLATE,
	'name' => 'bx_uni',
    'title' => 'UNI',
    'note' => 'Design template',
    'version' => '1.0.6',
    'vendor' => 'Boonex',
    'product_url' => 'http://www.boonex.com/m/{uri}',
    'update_url' => 'http://www.boonex.com/m/{uri}',

    'compatible_with' => array(
        '8.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/uni/',
    'home_uri' => 'uni',

    'db_prefix' => 'bx_uni_',
    'class_prefix' => 'BxUni',

    /**
     * Category for language keys.
     */
    'language_category' => 'BoonEx UNI',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'show_introduction' => 0,
        'change_permissions' => 0,
        'execute_sql' => 1,
        'update_languages' => 1,
        'recompile_global_paramaters' => 1,
        'clear_db_cache' => 0,
        'show_conclusion' => 0
    ),
    'uninstall' => array (
        'show_introduction' => 0,
        'change_permissions' => 0,
        'execute_sql' => 1,
        'update_languages' => 1,
        'recompile_global_paramaters' => 1,
        'clear_db_cache' => 0,
        'show_conclusion' => 0
    ),
    'enable' => array(
        'execute_sql' => 1
    ),
    'disable' => array(
        'execute_sql' => 1
    ),

    /**
     * Dependencies Section
     */
    'dependencies' => array(),
);

/** @} */
