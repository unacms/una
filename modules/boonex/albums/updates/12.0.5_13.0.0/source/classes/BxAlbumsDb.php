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
        $CNF = &$this->_oConfig->CNF;

        $sSelectClause = "`f2e`.*, `f`.`added`";
        $sJoinClause = "INNER JOIN `" . $CNF['TABLE_FILES'] . "` AS `f` ON (`f`.`id` = `f2e`.`file_id`) INNER JOIN `" . $CNF['TABLE_ENTRIES'] . "` AS `e` ON (`e`.`id` = `f2e`.`content_id`)";

        if($CNF['PARAM_ORDER_BY_GHOSTS']) {
            $sSelectClause .= ", `g`.`order` as `gorder`";
            $sJoinClause .= $this->prepareAsString("INNER JOIN `sys_storage_ghosts` AS `g` ON `g`.`id`=`f2e`.`file_id` AND `g`.`content_id`=`f2e`.`content_id` AND `g`.`object`=?", $CNF['OBJECT_STORAGE']);
        }

        $sQuery = $this->prepare("SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_FILES2ENTRIES'] . "` AS `f2e` " . $sJoinClause . " WHERE `f2e`.`id` = ?", $iMediaId);
        return $this->getRow($sQuery);
    }

    public function getMediaInfoSimpleByFileId($iFileId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `file_id` = ?", $iFileId);
        return $this->getRow($sQuery);
    }

    public function getMediaCountByContentId($iContentId)
    {
        $sQuery = $this->prepare ("SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = ? LIMIT 1", $iContentId);
        return $this->getOne($sQuery);
    }

    public function getMediaListByContentId($iContentId, $iLimit = false)
    {
        $sLimitQuery = '';
        $aBindings = array('id' => $iContentId);
        if ((int)$iLimit) {
            $sLimitQuery = ' LIMIT :limit';
            $aBindings = array_merge($aBindings, array('limit' => (int)$iLimit));
        }
            
        $sQuery = "SELECT * FROM `" . $this->_oConfig->CNF['TABLE_FILES2ENTRIES'] . "` WHERE `content_id` = :id ORDER BY `order`" . $sLimitQuery;
        return $this->getAll($sQuery, $aBindings);
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

            case 'search_ids':
                $aMethod['name'] = 'getColumn';
                
                $sSelectClause = "`{$CNF['TABLE_FILES2ENTRIES']}`.`id`";

                if (!empty($aParams['start']) && !empty($aParams['per_page']))
                    $sLimitClause = $this->prepareAsString("?, ?", $aParams['start'], $aParams['per_page']);
                elseif (!empty($aParams['per_page']))
                    $sLimitClause = $this->prepareAsString("?", $aParams['per_page']);

                $sWhereConditions = "1";
                foreach($aParams['search_params'] as $sSearchParam => $aSearchParam) {
                    $sSearchValue = "";
                    switch ($aSearchParam['operator']) {
                        case 'like':
                            $sSearchValue = " LIKE " . $this->escape("%" . $aSearchParam['value'] . "%");
                            break;

                        case 'in':
                            $sSearchValue = " IN (" . $this->implode_escape($aSearchParam['value']) . ")";
                            break;

                        case 'and':
                            $iResult = 0;
                            if (is_array($aSearchParam['value']))
                                foreach ($aSearchParam['value'] as $iValue)
                                    $iResult |= pow (2, $iValue - 1);
                            else 
                                $iResult = (int)$aSearchParam['value'];

                            $sSearchValue = " & " . $iResult . "";
                            break;

                        default:
                             $sSearchValue = " " . $aSearchParam['operator'] . " :" . $sSearchParam;
                             $aMethod['params'][1][$sSearchParam] = $aSearchParam['value'];                             
                    }

                    $sWhereConditions .= " AND `{$CNF['TABLE_FILES2ENTRIES']}`.`" . $sSearchParam . "`" . $sSearchValue;
                }

                $sWhereClause .= " AND (" . $sWhereConditions . ")"; 

                $sOrderClause .=  "`{$CNF['TABLE_FILES2ENTRIES']}`.`id` ASC";
                break;

            case 'all_ids':
                $aMethod['name'] = 'getColumn';

                $sSelectClause = "`{$CNF['TABLE_FILES2ENTRIES']}`.`id`";
                $sOrderClause .=  "`{$CNF['TABLE_FILES2ENTRIES']}`.`id` ASC";
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
    
    public function updateMedia($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `" . $CNF['TABLE_FILES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql);
    }
}

/** @} */
