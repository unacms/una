<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Intercom Intercom integration module
 * @ingroup     UnaModules
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
