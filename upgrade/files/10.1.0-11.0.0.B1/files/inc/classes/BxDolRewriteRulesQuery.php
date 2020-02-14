<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolRewriteRulesQuery extends BxDolDb
{
    public function __construct()
    {
    	parent::__construct();
    }

    static public function getActiveRules()
    {        
        return BxDolDb::getInstance()->fromCache("sys_wiki_rewrite_rules", "getAll", "SELECT * FROM `sys_rewrite_rules` WHERE `active` = 1");
    }
}

/** @} */
