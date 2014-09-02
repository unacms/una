<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'English',
    'version_from' => '8.0.2',
	'version_to' => '8.0.3',
    'vendor' => 'BoonEx',

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/english/updates/update_8.0.2_8.0.3/',
	'home_uri' => 'en_update_802_803',

	'module_dir' => 'boonex/english/',
	'module_uri' => 'en',

    'db_prefix' => 'bx_eng_',
    'class_prefix' => 'BxEng',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
		'execute_sql' => 0,
        'update_files' => 1,
        'update_languages' => 1,
		'clear_db_cache' => 0,
    ),

	/**
     * Category for language keys.
     */
    'language_category' => 'BoonEx English',
);
