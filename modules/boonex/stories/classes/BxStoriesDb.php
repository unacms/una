<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stories Stories
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxStoriesDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function associateFileWithContent($iContentId, $iFileId, $iProfileId, $sTitle, $sData = '')
    {
        $CNF = &$this->_oConfig->CNF;

        $iOrder = 1 + (int)$this->getOne("SELECT MAX(`order`) FROM `" . $CNF['TABLE_ENTRIES_MEDIA'] . "` WHERE `content_id` = :content_id", [
            'content_id' => $iContentId
        ]);

        return $this->query("INSERT INTO `" . $CNF['TABLE_ENTRIES_MEDIA'] . "` SET `content_id` = :content_id, `file_id` = :file_id, `author` = :author, `title` = :title, `data` = :data, `order` = :order ON DUPLICATE KEY UPDATE `title` = :title, `data` = :data", [
            'content_id' => $iContentId,
            'file_id' => $iFileId,
            'author' => $iProfileId,
            'title' => $sTitle,
            'data' => $sData,
            'order' => $iOrder
        ]);
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

        $sQuery = "DELETE FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES_MEDIA'] . "` WHERE 1 " . $sWhere;
        return $this->query($sQuery, $aBindings);
    }

    public function getFileTitle($iFileId)
    {
        $sQuery = $this->prepare ("SELECT `title` FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES_MEDIA'] . "` WHERE `file_id` = ?", $iFileId);
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

        $sQuery = $this->prepare("SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_ENTRIES_MEDIA'] . "` AS `f2e` " . $sJoinClause . " WHERE `f2e`.`id` = ?", $iMediaId);
        return $this->getRow($sQuery);
    }

    public function getMediaInfoSimpleByFileId($iFileId)
    {
        $sQuery = $this->prepare ("SELECT * FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES_MEDIA'] . "` WHERE `file_id` = ?", $iFileId);
        return $this->getRow($sQuery);
    }

    public function getMediaCountByContentId($iContentId)
    {
        $sQuery = $this->prepare ("SELECT COUNT(*) FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES_MEDIA'] . "` WHERE `content_id` = ? LIMIT 1", $iContentId);
        return $this->getOne($sQuery);
    }

    public function getMediaListByContentId($iContentId, $iLimit = false)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = ['id' => $iContentId];

        $sSelectClause = "`f2e`.*";
        $sJoinClause = "";
        $sOrderClause = "`f2e`.`order`";

        if($CNF['PARAM_ORDER_BY_GHOSTS']) {
            $sSelectClause .= ", `g`.`order` as `gorder`";
            $sJoinClause .= $this->prepareAsString("INNER JOIN `sys_storage_ghosts` AS `g` ON `g`.`id`=`f2e`.`file_id` AND `g`.`content_id`=`f2e`.`content_id` AND `g`.`object`=?", $CNF['OBJECT_STORAGE']);
            $sOrderClause = "`gorder` ASC";
        }

        if($sOrderClause)
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $sLimitQuery = '';
        if((int)$iLimit) {
            $aBindings['limit'] = (int)$iLimit;

            $sLimitQuery = ' LIMIT :limit';
        }

        return $this->getAll("SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_ENTRIES_MEDIA'] . "` AS `f2e` " . $sJoinClause . " WHERE `f2e`.`content_id` = :id " . $sOrderClause . " " . $sLimitQuery, $aBindings);
    }

    function getMediaBy($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query', 1 => array()));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "`{$CNF['TABLE_ENTRIES_MEDIA']}`.*";

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1]['id'] = (int)$aParams['id'];

                $sWhereClause .= " AND `{$CNF['TABLE_ENTRIES_MEDIA']}`.`id` = :id";
                break;

            case 'search_ids':
                $aMethod['name'] = 'getColumn';
                
                $sSelectClause = "`{$CNF['TABLE_ENTRIES_MEDIA']}`.`id`";

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

                    $sWhereConditions .= " AND `{$CNF['TABLE_ENTRIES_MEDIA']}`.`" . $sSearchParam . "`" . $sSearchValue;
                }

                $sWhereClause .= " AND (" . $sWhereConditions . ")"; 

                $sOrderClause .=  "`{$CNF['TABLE_ENTRIES_MEDIA']}`.`id` ASC";
                break;

            case 'all_ids':
                $aMethod['name'] = 'getColumn';

                $sSelectClause = "`{$CNF['TABLE_ENTRIES_MEDIA']}`.`id`";
                $sOrderClause .=  "`{$CNF['TABLE_ENTRIES_MEDIA']}`.`id` ASC";
                break;

            case 'all':
                $sOrderClause .=  "`{$CNF['TABLE_ENTRIES_MEDIA']}`.`id` ASC";
                break;
        }

        if(!empty($sOrderClause))
            $sOrderClause = 'ORDER BY ' . $sOrderClause;

        if(!empty($sLimitClause))
            $sLimitClause = 'LIMIT ' . $sLimitClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `{$CNF['TABLE_ENTRIES_MEDIA']}` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
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
