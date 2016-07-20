<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Intercom Intercom integration module
 * @ingroup     TridentModules
 *
 * @{
 */

class BxIntercomDb extends BxDolModuleDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getInitialUsers($iLimit = 1000)
    {
        return $this->getColumn("SELECT `id` FROM `sys_accounts` ORDER BY `added` DESC LIMIT " . (int)$iLimit);
    }

    public function getSessionRowByAccountId($iAccountId)
    {
        return $this->getRow("SELECT * FROM `sys_sessions` WHERE `user_id` = :account ORDER BY `date` DESC LIMIT 1", array(
            'account' => $iAccountId,
        ));
    }
}

/** @} */
