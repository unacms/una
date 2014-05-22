<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Sites',
    'version_from' => '1.0.0',
	'version_to' => '2.0.0',
    'vendor' => 'BoonEx',

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/sites/updates/update_100_200/',
	'home_uri' => 'sites_update_100_200',

	'module_dir' => 'boonex/sites/',
	'module_uri' => 'sites',

    'db_prefix' => 'bx_sites_',
    'class_prefix' => 'BxSites',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'show_introduction' => 0,
		'change_permissions' => 0,
		'execute_sql' => 1,
        'update_files' => 0,
        'update_languages' => 1,
		'recompile_global_paramaters' => 0,
        'recompile_main_menu' => 0,
        'recompile_member_menu' => 0,
        'recompile_site_stats' => 0,
        'recompile_page_builder' => 0,
        'recompile_profile_fields' => 0,
        'recompile_comments' => 0,
        'recompile_member_actions' => 0,
        'recompile_votes' => 0,
        'recompile_search' => 0,
        'recompile_injections' => 0,
        'recompile_permalinks' => 0,
        'recompile_alerts' => 0,
		'clear_db_cache' => 0,
        'show_conclusion' => 0
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'Sites',

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
