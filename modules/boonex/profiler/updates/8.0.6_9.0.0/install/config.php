<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Profiler',
    'version_from' => '8.0.6',
	'version_to' => '9.0.0',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '9.0.0-B1'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/profiler/updates/update_8.0.6_9.0.0/',
	'home_uri' => 'profiler_update_806_900',

	'module_dir' => 'boonex/profiler/',
	'module_uri' => 'profiler',

    'db_prefix' => 'bx_profiler_',
    'class_prefix' => 'BxProfiler',

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
    'language_category' => 'Boonex Profiler',

	/**
     * Files Section
     */
    'delete_files' => array(),
);
