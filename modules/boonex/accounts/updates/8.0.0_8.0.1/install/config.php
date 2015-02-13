<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'Accounts Manager',
    'version_from' => '8.0.0',
	'version_to' => '8.0.1',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.0.A11'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/accounts/updates/update_8.0.0_8.0.1/',
	'home_uri' => 'accounts_update_800_801',

	'module_dir' => 'boonex/accounts/',
	'module_uri' => 'accounts',

    'db_prefix' => 'bx_accnt_',
    'class_prefix' => 'BxAccnt',

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
    'language_category' => 'Accounts',

	/**
     * Files Section
     */
    'delete_files' => array(
		'classes/BxAccntGridModeration.php'
	),
);
