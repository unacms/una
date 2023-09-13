<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolPreloader
 */
class BxDolPreloaderQuery extends BxDolDb
{
	public function __construct()
    {
    	parent::__construct();
    }

    public function &getEntries()
    {
        $sKey = 'bx_dol_cache_memory_preloader_entries';

        if(!isset($GLOBALS[$sKey]))
            $GLOBALS[$sKey] = $this->fromCache('sys_preloader_entries', 'getAll', "SELECT * FROM `sys_preloader` WHERE `active`='1' ORDER BY `type`, `order`");

        return $GLOBALS[$sKey];
    }

    public function &getEntriesSystem()
    {
        $sKey = 'bx_dol_cache_memory_preloader_entries_system';

        if(!isset($GLOBALS[$sKey]))
            $GLOBALS[$sKey] = $this->fromCache('sys_preloader_entries_system', 'getAll', "SELECT * FROM `sys_preloader` WHERE `type` IN ('css_system', 'js_system') AND `active`='1' ORDER BY `type`, `order`");

        return $GLOBALS[$sKey];
    }
}

/** @} */
