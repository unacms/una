<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxDolModuleQuery');

class BxDolStudioModulesQuery extends BxDolModuleQuery
{
    function __construct()
    {
        parent::__construct();
    }

	public function updateModule($aParamsSet, $aParamsWhere = array())
    {
        if(empty($aParamsSet))
            return false;

		$sWhereClause = !empty($aParamsWhere) ? $this->arrayToSQL($aParamsWhere, " AND ") : "1";

        $sSql = "UPDATE `sys_modules` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $sWhereClause;
        return $this->query($sSql);
    }
}

/** @} */
