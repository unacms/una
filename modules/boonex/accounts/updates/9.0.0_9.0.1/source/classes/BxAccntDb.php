<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Accounts Accounts
 * @ingroup     DolphinModules
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
