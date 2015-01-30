<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxBaseModTextDb');

/*
 * Module database queries
 */
class BxAlbumsDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function associateFileWithContent($iContentId, $iFileId, $sTitle, $sData = '')
    {
        $sQuery = $this->prepare ("SELECT MAX(`order`) FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ?", $$iContentId);
        $iOrder = 1 + (int)$this->getOne($sQuery);
        
        $sQuery = $this->prepare ("INSERT INTO `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` SET `content_id` = ?, `file_id` = ?, `title` = ?, `data` = ?, `order` = ? ON DUPLICATE KEY UPDATE `title` = ?, `data` = ?", $iContentId, $iFileId, $sTitle, $sData, $iOrder, $sTitle, $sData);
        return $this->query($sQuery);
    }

    public function deassociateFileWithContent($iContentId, $iFileId)
    {
        $sWhere = '';
        if ($iContentId)
            $sWhere .= $this->prepare (" AND `content_id` = ? ", $iContentId);

        if ($iFileId)
            $sWhere .= $this->prepare (" AND `file_id` = ? ", $iFileId);

        $sQuery = "DELETE FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE 1 ";
        return $this->query($sQuery . $sWhere);
    }

    public function getFileTitle($iFileId)
    {
        $sQuery = $this->prepare ("SELECT `title` FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `file_id` = ?", $iFileId);
        return $this->getOne($sQuery);
    }

    public function getMediaInfoById($iMediaId)
    {
        $sQuery = $this->prepare ("SELECT `f2e`.*, `f`.`added`, `e`.`views` FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` AS `f2e` INNER JOIN `" . $this->_oConfig->CNF['TABLE_FILES'] . "` AS `f` ON (`f`.`id` = `f2e`.`file_id`) INNER JOIN `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `e` ON (`e`.`id` = `f2e`.`content_id`) WHERE `f2e`.`id` = ?", $iMediaId);
        return $this->getRow($sQuery);
    }

    public function getMediaListByContentId($iContentId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ? ORDER BY `order`", $iContentId);
        return $this->getAll($sQuery);
    }

}

/** @} */
