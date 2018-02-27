<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxPhotosDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function updateEntries($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `" . $CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql);
    }
    
    public function getFileTitle($iFileId)
    {
        $sQuery = $this->prepare ("SELECT `c`.`title` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `sys_storage_ghosts` AS `g` ON(`g`.`object` = ? AND `g`.`content_id` = `c`.`id`) WHERE `g`.`id` = ?", $this->_oConfig->CNF['TABLE_FILES'], $iFileId);
        return $this->getOne($sQuery);
    }
}

/** @} */
