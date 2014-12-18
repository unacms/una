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

    public function associateFileWithContent($iContentId, $iFileId, $sTitle)
    {
        $sQuery = $this->prepare ("INSERT INTO `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` SET `content_id` = ?, `file_id` = ?, `title` = ? ON DUPLICATE KEY UPDATE `title` = ?", $iContentId, $iFileId, $sTitle, $sTitle);
        return $this->query($sQuery);
    }

    public function deassociateFileWithContent($iContentId, $iFileId)
    {
        $sQuery = $this->prepare ("DELETE FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ? AND `file_id` = ?", $iContentId, $iFileId);
        return $this->query($sQuery);
    }

    public function getFileTitle($iFileId)
    {
        $sQuery = $this->prepare ("SELECT `title` FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `file_id` = ?", $iFileId);
        return $this->getOne($sQuery);
    }
}

/** @} */
