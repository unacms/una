<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAntispamDb extends BxDolModuleDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    function setCommentStatus($iCmtId, $sStatus) {
        $sTableName = BxDolCmts::$sTableIds;
        $this->query("UPDATE `{$sTableName}` SET `status_admin` = :status WHERE `id` = :id", ['id' => $iCmtId, 'status' => $sStatus]);
    }
}

/** @} */
