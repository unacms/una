<?php 
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    English English language
 * @ingroup     DolphinModules
 *
 * @{
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_LANGUAGE,
	'name' => 'bx_en',
    'title' => 'English',
    'note' => 'Language file',
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
    'home_dir' => 'boonex/english/',
    'home_uri' => 'en',

    'db_prefix' => 'bx_eng_',
    'class_prefix' => 'BxEng',

    /**
     * Installation/Uninstallation Section.
     * NOTE. The sequence of actions is critical. Don't change the order. 
     */
    'install' => array(
        'execute_sql' => 1,    	
        'update_languages' => 1,
    	'install_language' => 1,
        'recompile_global_paramaters' => 1
    ),
    'uninstall' => array (
    	'update_languages' => 1,
        'execute_sql' => 1,
        'recompile_global_paramaters' => 1
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

    /**
     * Category for language keys.
     */
    'language_category' => 'BoonEx English',

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

/** @} */
