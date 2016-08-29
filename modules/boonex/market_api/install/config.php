<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    MarketApi MarketApi
 * @ingroup     TridentModules
 *
 * @{
 */

$aConfig = array(

    /**
     * Main Section.
     */
    'type' => BX_DOL_MODULE_TYPE_MODULE,
    'name' => 'bx_market_api',
    'title' => 'Market API',
    'note' => 'Market API module.',
    'version' => '9.0.0',
    'vendor' => 'BoonEx',
	'help_url' => 'http://feed.boonex.com/?section={module_name}',

    'compatible_with' => array(
        '9.0.x'
    ),

    /**
     * 'home_dir' and 'home_uri' - should be unique. Don't use spaces in 'home_uri' and the other special chars.
     */
    'home_dir' => 'boonex/market_api/',
    'home_uri' => 'market_api',

    'db_prefix' => 'bx_market_api_',
    'class_prefix' => 'BxMarketApi',

    /**
     * Category for language keys.
     */
    'language_category' => 'Market API',

    /**
     * Installation/Uninstallation Section.
     */
    'install' => array(
    	'check_dependencies' => 1,
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
    	'check_dependencies' => 1,
        'execute_sql' => 1,
        'clear_db_cache' => 1,
    ),
    'disable' => array (
        'execute_sql' => 1,
        'clear_db_cache' => 1,
    ),

    /**
     * Dependencies Section
     */
    'dependencies' => array(
    	'market' => 'Market'
    ),
);

/** @} */
