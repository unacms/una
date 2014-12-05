<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

$aConfig = array(
    /**
     * Main Section.
     */
    'title' => 'SMTP Mailer',
    'version_from' => '8.0.0',
	'version_to' => '8.0.1',
    'vendor' => 'BoonEx',

	'compatible_with' => array(
        '8.0.0.A9'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/smtpmailer/updates/update_8.0.0_8.0.1/',
	'home_uri' => 'smtpmailer_update_800_801',

	'module_dir' => 'boonex/smtpmailer/',
	'module_uri' => 'smtpmailer',

    'db_prefix' => 'bx_smtp_',
    'class_prefix' => 'BxSMTP',

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
    'language_category' => 'SMTP Mailer',
);
