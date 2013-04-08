<?
/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

$aConfig = array(
    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_TEMPLATE,
	'name' => 'bx_basic',
    'title' => 'Basic',
    'note' => 'Design template',
    'version' => '1.0.6',
    'vendor' => 'Boonex',
    'product_url' => 'http://www.boonex.com/m/{uri}',
    'update_url' => 'http://www.boonex.com/m/{uri}',

    'compatible_with' => array(
        '7.0.6'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/basic/',
    'home_uri' => 'basic',

    'db_prefix' => 'bx_bsc_',
    'class_prefix' => 'BxBsc',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
        'show_introduction' => 0,
        'change_permissions' => 0,
    	'move_sources' => 1,
        'execute_sql' => 1,
        'update_languages' => 1,
        'recompile_global_paramaters' => 1,
        'clear_db_cache' => 0,
        'show_conclusion' => 0
    ),
    'uninstall' => array (
        'show_introduction' => 0,
        'change_permissions' => 0,
    	'move_sources' => 1,
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

    /**
     * Category for language keys.
     */
    'language_category' => 'BoonEx Basic',

    /**
     * Permissions Section
     */
    'install_permissions' => array(),
    'uninstall_permissions' => array(),

    /**
     * List of modules which are included in this templte(language file)
     * Usage: module uri => path
     * 
     * Note. If template(language file) supports a module but don't need to copy any specific files 
     * leave the 'path' an empty or don't specify the module at all.
     *   
     */
    'includes' => array(
        'system' => 'templates/tmpl_basic',
    ),
    
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