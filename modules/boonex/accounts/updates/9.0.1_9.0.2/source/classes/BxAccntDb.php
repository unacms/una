<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAccntDb extends BxBaseModGeneralDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function updateAccount($aSet, $aWhere)
    {
    	return (int)$this->query("UPDATE `sys_accounts` SET " . $this->arrayToSQL($aSet) . " WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }
}

/** @} */
