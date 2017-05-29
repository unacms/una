<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxAlbumsDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function associateFileWithContent($iContentId, $iFileId, $iProfileId, $sTitle, $sData = '', $sExif = '')
    {
        $sQuery = $this->prepare ("SELECT MAX(`order`) FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ?", $iContentId);
        $iOrder = 1 + (int)$this->getOne($sQuery);

        $sQuery = $this->prepare ("INSERT INTO `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` SET `content_id` = ?, `file_id` = ?, `author` = ?, `title` = ?, `data` = ?, `exif` = ?, `order` = ? ON DUPLICATE KEY UPDATE `title` = ?, `data` = ?, `exif` = ?", $iContentId, $iFileId, $iProfileId, $sTitle, $sData, $sExif, $iOrder, $sTitle, $sData, $sExif);
        return $this->res($sQuery);
    }

    public function deassociateFileWithContent($iContentId, $iFileId)
    {
    	$aBindings = array();

        $sWhere = '';
        if ($iContentId) {
        	$aBindings['content_id'] = $iContentId;

            $sWhere .= " AND `content_id` = :content_id";
        }

        if ($iFileId) {
        	$aBindings['file_id'] = $iFileId;

            $sWhere .= " AND `file_id` = :file_id";
        }

        $sQuery = "DELETE FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE 1 " . $sWhere;
        return $this->query($sQuery, $aBindings);
    }

    public function getFileTitle($iFileId)
    {
        $sQuery = $this->prepare ("SELECT `title` FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `file_id` = ?", $iFileId);
        return $this->getOne($sQuery);
    }

    public function getMediaInfoById($iMediaId)
    {
        $sQuery = $this->prepare ("SELECT `f2e`.*, `f`.`added` FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` AS `f2e` INNER JOIN `" . $this->_oConfig->CNF['TABLE_FILES'] . "` AS `f` ON (`f`.`id` = `f2e`.`file_id`) INNER JOIN `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `e` ON (`e`.`id` = `f2e`.`content_id`) WHERE `f2e`.`id` = ?", $iMediaId);
        return $this->getRow($sQuery);
    }

    public function getMediaInfoSimpleByFileId($iFileId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `file_id` = ?", $iFileId);
        return $this->getRow($sQuery);
    }

    public function getMediaListByContentId($iContentId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ? ORDER BY `order`", $iContentId);
        return $this->getAll($sQuery);
    }

    function getMediaBy($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query', 1 => array()));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$CNF['TABLE_FILES2ENTRIES']}`.*";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1]['id'] = (int)$aParams['id'];

                $sWhereClause .= " AND `{$CNF['TABLE_FILES2ENTRIES']}`.`id` = :id";
                break;

            case 'all':
                $sOrderClause .=  "`{$CNF['TABLE_FILES2ENTRIES']}`.`id` ASC";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `{$CNF['TABLE_FILES2ENTRIES']}` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
		return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
