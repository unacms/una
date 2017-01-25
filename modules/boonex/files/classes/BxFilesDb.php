<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxFilesDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getFileTitle($iFileId)
    {
        $sQuery = $this->prepare ("SELECT `c`.`title` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` INNER JOIN `sys_storage_ghosts` AS `g` ON(`g`.`object` = ? AND `g`.`content_id` = `c`.`id`) WHERE `g`.`id` = ?", $this->_oConfig->CNF['TABLE_FILES'], $iFileId);
        return $this->getOne($sQuery);
    }

    public function updateFileId ($iContentId, $iFileId)
    {
        $sQuery = $this->prepare ("UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `file_id` = ?, `data` = '', `data_processed` = 0 WHERE `id` = ?", $iFileId, $iContentId);
        return $this->query($sQuery);
    }
    
    public function updateFileData ($iContentId, $sData, $iDataProcessed = 1)
    {
        $sQuery = $this->prepare ("UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET `data` = ?, `data_processed` = ? WHERE `id` = ?", $sData, $iDataProcessed, $iContentId);
        return $this->query($sQuery);
    }

    public function getNotProcessedFiles ($iLimit)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` WHERE `data_processed` = 0 ORDER BY `added` ASC LIMIT ?", $iLimit);
        return $this->getAll($sQuery);
    }
}

/** @} */
