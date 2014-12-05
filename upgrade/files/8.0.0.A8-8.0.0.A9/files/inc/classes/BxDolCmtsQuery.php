<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolDb');

/**
 * @see BxDolCmts
 */
class BxDolCmtsQuery extends BxDolDb
{
    protected $_oMain;

    protected $_sTable;
    protected $_sTriggerTable;
    protected $_sTriggerFieldId;
    protected $_sTriggerFieldTitle;
    protected $_sTriggerFieldComments;

    protected $_sTableImages;
    protected $_sTableImages2Entries;

    protected $_sTableIds;

    function __construct(&$oMain)
    {
        $this->_oMain = $oMain;

        $aSystem = $this->_oMain->getSystemInfo();
        $this->_sTable = $aSystem['table'];
        $this->_sTriggerTable = $aSystem['trigger_table'];
        $this->_sTriggerFieldId = $aSystem['trigger_field_id'];
        $this->_sTriggerFieldTitle = $aSystem['trigger_field_title'];
        $this->_sTriggerFieldComments = $aSystem['trigger_field_comments'];

        $this->_sTableImages = $aSystem['table_images'];
        $this->_sTableImages2Entries = $aSystem['table_images2entries'];

        $this->_sTableIds = $aSystem['table_ids'];

        parent::__construct();
    }

    function getTableName ()
    {
        return $this->_sTable;
    }

    function getCommentsCount ($iId, $iCmtVParentId = 0, $iAuthorId = 0, $sFilter = '')
    {
        $sWhereClause = '';
        if((int)$iCmtVParentId >= 0)
            $sWhereClause .= $this->prepare(" AND `{$this->_sTable}`.`cmt_vparent_id` = ?", $iCmtVParentId);

        $sJoinClause = '';
        switch($sFilter) {
        	case BX_CMT_FILTER_FRIENDS:
        	case BX_CMT_FILTER_SUBSCRIPTIONS:
	            bx_import('BxDolConnection');
	            $oConnection = BxDolConnection::getObjectInstance($this->_oMain->getConnectionObject($sFilter));
	
	            $aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sTable, 'cmt_author_id', $iAuthorId);
	            $sJoinClause .= ' ' . $aQueryParts['join'];
	            break;

        	case BX_CMT_FILTER_OTHERS:
        		$sWhereClause .= $this->prepare(" AND `{$this->_sTable}`.`cmt_author_id` <> ?", $iAuthorId);
        		break;
        }

        $sQuery = $this->prepare("SELECT
                COUNT(*)
            FROM `{$this->_sTable}`
            $sJoinClause
            WHERE `{$this->_sTable}`.`cmt_object_id` = ?" . $sWhereClause, $iId);

        return (int)$this->getOne($sQuery);
    }

    function getComments ($iId, $iCmtVParentId = 0, $iAuthorId = 0, $sFilter = '', $aOrder = array(), $iStart = 0, $iCount = -1)
    {
        $sFields = $sJoin = "";

        $oVote = $this->_oMain->getVoteObject(0);
        if($oVote !== false) {
            $aSql = $oVote->getSqlParts($this->_sTable, 'cmt_id');

            $sFields .= $aSql['fields'];
            $sJoin .= $aSql['join'];
        }

        $sWhereParent = '';
        if((int)$iCmtVParentId >= 0)
            $sWhereParent = $this->prepare(" AND `{$this->_sTable}`.`cmt_vparent_id` = ?", $iCmtVParentId);

        if(in_array($sFilter, array(BX_CMT_FILTER_FRIENDS, BX_CMT_FILTER_SUBSCRIPTIONS))) {
            bx_import('BxDolConnection');
            $oConnection = BxDolConnection::getObjectInstance($this->_oMain->getConnectionObject($sFilter));

            $aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sTable, 'cmt_author_id', $iAuthorId);
            $sJoin .= ' ' . $aQueryParts['join'];
        }

        $sOder = " ORDER BY `{$this->_sTable}`.`cmt_time` ASC";
        if(isset($aOrder['by']) && isset($aOrder['way'])) {
            $aOrder['way'] = strtoupper(in_array($aOrder['way'], array(BX_CMT_ORDER_WAY_ASC, BX_CMT_ORDER_WAY_DESC)) ? $aOrder['way'] : BX_CMT_ORDER_WAY_ASC);

            switch($aOrder['by']) {
                case BX_CMT_ORDER_BY_DATE:
                    $sOder = " ORDER BY `{$this->_sTable}`.`cmt_time` " . $aOrder['way'];
                    break;

                case BX_CMT_ORDER_BY_POPULAR:
                    $sOder = " ORDER BY `{$this->_sTable}`.`cmt_rate` " . $aOrder['way'];
                    break;
            }
        }

        $sLimit = $iCount != -1 ? $this->prepare(" LIMIT ?, ?", (int)$iStart, (int)$iCount) : '';

        $sQuery = $this->prepare("SELECT
                `{$this->_sTable}`.`cmt_id`,
                `{$this->_sTable}`.`cmt_parent_id`,
                `{$this->_sTable}`.`cmt_vparent_id`,
                `{$this->_sTable}`.`cmt_object_id`,
                `{$this->_sTable}`.`cmt_author_id`,
                `{$this->_sTable}`.`cmt_level`,
                `{$this->_sTable}`.`cmt_text`,
                `{$this->_sTable}`.`cmt_replies`,
                `{$this->_sTable}`.`cmt_time`
                $sFields
            FROM `{$this->_sTable}`
            $sJoin
            WHERE `{$this->_sTable}`.`cmt_object_id` = ?" . $sWhereParent . $sOder . $sLimit, $iId);

        return $this->getAll($sQuery);
    }

	function getCommentsBy($aParams = array())
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        $sSelectClause = "
        	`{$this->_sTable}`.`cmt_id`,
            `{$this->_sTable}`.`cmt_parent_id`,
            `{$this->_sTable}`.`cmt_vparent_id`,
            `{$this->_sTable}`.`cmt_object_id`,
            `{$this->_sTable}`.`cmt_author_id`,
            `{$this->_sTable}`.`cmt_level`,
            `{$this->_sTable}`.`cmt_text`,
            `{$this->_sTable}`.`cmt_replies`,
            `{$this->_sTable}`.`cmt_time`";

        if(isset($aParams['object_id']))
        	$sWhereClause .= $this->prepare(" AND `{$this->_sTable}`.`cmt_object_id` = ?" , (int)$aParams['object_id']);

        switch($aParams['type']) {
            case 'latest_ids':
            	$aMethod['name'] = 'getColumn';
				$sSelectClause = "`{$this->_sTable}`.`cmt_id`";
                $sOrderClause = "ORDER BY `{$this->_sTable}`.`cmt_time` DESC";
                $sLimitClause = isset($aParams['per_page']) ? "LIMIT " . $aParams['start'] . ", " . $aParams['per_page'] : "";
                break;
        }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . "
            FROM `{$this->_sTable}` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;

		return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    function getComment ($iId, $iCmtId)
    {
        $sFields = $sJoin = "";

        $oVote = $this->_oMain->getVoteObject($iCmtId);
        if($oVote !== false) {
            $aSql = $oVote->getSqlParts($this->_sTable, 'cmt_id');

            $sFields .= $aSql['fields'];
            $sJoin .= $aSql['join'];
        }

        $sQuery = $this->prepare("SELECT
                `{$this->_sTable}`.`cmt_id`,
                `{$this->_sTable}`.`cmt_parent_id`,
                `{$this->_sTable}`.`cmt_vparent_id`,
                `{$this->_sTable}`.`cmt_object_id`,
                `{$this->_sTable}`.`cmt_author_id`,
                `{$this->_sTable}`.`cmt_level`,
                `{$this->_sTable}`.`cmt_text`,
                `{$this->_sTable}`.`cmt_replies`,
                `{$this->_sTable}`.`cmt_time`
                $sFields
            FROM `{$this->_sTable}`
            $sJoin
            WHERE `{$this->_sTable}`.`cmt_object_id` = ? AND `{$this->_sTable}`.`cmt_id` = ?
            LIMIT 1", $iId, $iCmtId);
        return $this->getRow($sQuery);
    }

    function getCommentSimple ($iId, $iCmtId)
    {
        $sQuery = $this->prepare("SELECT * FROM {$this->_sTable} AS `c` WHERE `cmt_object_id` = ? AND `cmt_id` = ? LIMIT 1", $iId, $iCmtId);
        return $this->getRow($sQuery);
    }

    function removeComment ($iId, $iCmtId, $iCmtParentId)
    {
        $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `cmt_object_id` = ? AND `cmt_id` = ? LIMIT 1", $iId, $iCmtId);
        if (!$this->query($sQuery))
            return false;

        if($iCmtParentId)
            $this->updateRepliesCount($iCmtParentId, -1);

        return true;
    }

    function saveImages($iSystemId, $iCmtId, $iImageId)
    {
        $sQuery = $this->prepare("INSERT IGNORE INTO `{$this->_sTableImages2Entries}` SET `system_id`=?, `cmt_id`=?, `image_id`=?", $iSystemId, $iCmtId, $iImageId);
        return (int)$this->query($sQuery) > 0;
    }

    function getImages($iSystemId, $iCmtId, $iId = false)
    {
        $sJoin = '';
        $sWhere = $this->prepare(" AND `i`.`system_id` = ? ", $iSystemId);

        if (false !== $iCmtId) {
            $sWhere .= $this->prepare(" AND `i`.`cmt_id` = ? ", $iCmtId);
        }

        if (false !== $iId) {
            $sWhere .= $this->prepare(" AND `c`.`cmt_object_id` = ?", $iId);
            $sJoin .= " INNER JOIN `{$this->_sTable}` AS `c` ON (`i`.`cmt_id` = `c`.`cmt_id`) ";
        }

        return $this->getAll("SELECT * FROM `{$this->_sTableImages2Entries}` AS `i` " . $sJoin . " WHERE 1 " . $sWhere);
    }

    function deleteImages($iSystemId, $iCmtId)
    {
        $sWhereAddon = '';
        if (false !== $iCmtId)
            $sWhereAddon = $this->prepare(" AND `cmt_id` = ? ", $iCmtId);

        $sQuery = $this->prepare("DELETE FROM `{$this->_sTableImages2Entries}` WHERE `system_id` = ?", $iSystemId);
        $sQuery .= $sWhereAddon;

        return $this->query($sQuery);
    }

    function updateRepliesCount($iCmtId, $iCount)
    {
        $sQuery = $this->prepare("UPDATE `{$this->_sTable}` SET `cmt_replies`=`cmt_replies`+? WHERE `cmt_id`=? LIMIT 1", $iCount, $iCmtId);
        return $this->query($sQuery);
    }

    function deleteAuthorComments ($iAuthorId, &$aFiles = null, &$aCmtIds = null)
    {
        $aSystem = $this->_oMain->getSystemInfo();

        $isDelOccured = 0;
        $sQuery = $this->prepare("SELECT `cmt_id`, `cmt_parent_id` FROM {$this->_sTable} WHERE `cmt_author_id` = ? AND `cmt_replies` = 0", $iAuthorId);
        $a = $this->getAll ($sQuery);
        for ( reset($a) ; list (, $r) = each ($a) ; ) {
            $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `cmt_id` = ?", $r['cmt_id']);
            $this->query ($sQuery);

            $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_replies` = `cmt_replies` - 1 WHERE `cmt_id` = ?", $r['cmt_parent_id']);
            $this->query ($sQuery);

            $aFilesMore = $this->convertImagesArray($this->getImages($aSystem['system_id'], $r['cmt_id']));
            $this->deleteImages($aSystem['system_id'], $r['cmt_id']);
            if ($aFilesMore && null !== $aFiles)
                $aFiles = array_merge($aFiles, $aFilesMore);

            if (null !== $aCmtIds)
                $aCmtIds[] = $r['cmt_id'];

            $isDelOccured = 1;
        }
        $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_author_id` = 0 WHERE `cmt_author_id` = ? AND `cmt_replies` != 0", $iAuthorId);
        $this->query ($sQuery);
        if ($isDelOccured)
            $this->query ("OPTIMIZE TABLE {$this->_sTable}");
    }

    function deleteObjectComments ($iObjectId, &$aFilesReturn = null, &$aCmtIds = null)
    {
        $aSystem = $this->_oMain->getSystemInfo();
        $aFiles = $this->convertImagesArray($this->getImages($aSystem['system_id'], false, $iObjectId));

        if ($aFiles) {
            $sQuery = $this->prepare("DELETE FROM {$this->_sTableImages2Entries} WHERE `system_id` = ? ", $aSystem['system_id']);
            $sQuery .= " AND `image_id` IN(" . $this->implode_escape($aFiles) . ") ";
            $this->query ($sQuery);
        }

        if (null !== $aCmtIds) {
            $sQuery = $this->prepare("SELECT `cmt_id` FROM {$this->_sTable} WHERE `cmt_object_id` = ?", $iObjectId);
            $aCmtIds = $this->getColumn ($sQuery);
        }

        $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `cmt_object_id` = ?", $iObjectId);
        $this->query ($sQuery);
        $this->query ("OPTIMIZE TABLE {$this->_sTable}");

        if (null !== $aFilesReturn)
            $aFilesReturn = $aFiles;
    }

    function deleteAll ($iSystemId, &$aFiles = null, &$aCmtIds = null)
    {
        // get files
        if (null !== $aFiles)
            $aFiles = $this->convertImagesArray($this->getImages($iSystemId, false));

        // delete files
        $this->deleteImages($iSystemId, false);

        if (null !== $aCmtIds)
            $aCmtIds = $this->getColumn ("SELECT `cmt_id` FROM {$this->_sTable}");

        // delete comments
        $sQuery = $this->prepare("TRUNCATE TABLE {$this->_sTable}");
        $this->query ($sQuery);
    }

    function deleteCmtIds ($iSystemId, $iCmtId)
    {
        $sQuery = $this->prepare("DELETE FROM {$this->_sTableIds} WHERE `system_id` = ? AND `cmt_id` = ?", $iSystemId, $iCmtId);
        return $this->query ($sQuery);
    }

    function getObjectTitle($iId)
    {
        $sQuery = $this->prepare("SELECT `{$this->_sTriggerFieldTitle}` FROM `{$this->_sTriggerTable}` WHERE `{$this->_sTriggerFieldId}` = ? LIMIT 1", $iId);
        return $this->getOne($sQuery);
    }

    function updateTriggerTable($iId, $iCount)
    {
        $sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldComments}` = ? WHERE `{$this->_sTriggerFieldId}` = ? LIMIT 1", $iCount, $iId);
        return $this->query($sQuery);
    }

    function getUniqId($iSystemId, $iCmtId)
    {
        $sQuery = $this->prepare("SELECT `id` FROM `{$this->_sTableIds}` WHERE `system_id` = ? AND `cmt_id` = ?", $iSystemId, $iCmtId);
        if ($iUniqId = $this->getOne($sQuery))
            return $iUniqId;
            
        $sQuery = $this->prepare("INSERT INTO `{$this->_sTableIds}` SET `system_id` = ?, `cmt_id` = ?", $iSystemId, $iCmtId);
        if (!$this->query($sQuery))
            return false;

        return $this->lastId();
    }

    protected function convertImagesArray($a)
    {
        if (!$a || !is_array($a))
            return array();

        $aFiles = array ();
        foreach ($a as $aFile)
            $aFiles[] = $aFile['image_id'];
        return $aFiles;
    }
}

/** @} */
