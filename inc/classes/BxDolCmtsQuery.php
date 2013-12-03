<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

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

    function BxDolCmtsQuery(&$oMain)
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

        parent::BxDolDb();
    }

    function getTableName ()
    {
        return $this->_sTable;
    }

	function getCommentsCount ($iId, $iCmtVParentId = 0, $iAuthorId = 0, $sFilter = '') {
		$sWhereParent = '';
        if((int)$iCmtVParentId >= 0)
        	$sWhereParent = $this->prepare(" AND `{$this->_sTable}`.`cmt_vparent_id` = ?", $iCmtVParentId);

        $sJoin = '';
		if(in_array($sFilter, array(BX_CMT_FILTER_FRIENDS, BX_CMT_FILTER_SUBSCRIPTIONS))) {
			bx_import('BxDolConnection');
			$oConnection = BxDolConnection::getObjectInstance($this->_oMain->getConnectionObject($sFilter));
			
			$aQueryParts = $oConnection->getConnectedContentAsSQLParts($this->_sTable, 'cmt_author_id', $iAuthorId);
			$sJoin .= ' ' . $aQueryParts['join'];
		}

		$sQuery = $this->prepare("SELECT 
				COUNT(*) 
			FROM `{$this->_sTable}`
			$sJoin 
			WHERE `{$this->_sTable}`.`cmt_object_id` = ?" . $sWhereParent, $iId);

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

    function getComment ($iId, $iCmtId, $iAuthorId = 0)
    {
        $sFields = $sJoin = "";

    	$oVote = $this->_oMain->getVoteObject($iCmtId);
        if($oVote !== false) {
        	$aSql = $oVote->getSqlParts($this->_sTable, 'cmt_id');

        	$sFields .= $aSql['fields'];
        	$sJoin .= $aSql['join'];
        }

        $sQuery = $this->prepare("SELECT
                `c`.`cmt_id`,
                `c`.`cmt_parent_id`,
                `c`.`cmt_vparent_id`,
                `c`.`cmt_object_id`,
                `c`.`cmt_author_id`,
                `c`.`cmt_level`,
                `c`.`cmt_text`,
                `c`.`cmt_replies`,
                `c`.`cmt_time`
                $sFields
            FROM {$this->_sTable} AS `c`
            $sJoin
            WHERE `c`.`cmt_object_id` = ? AND `c`.`cmt_id` = ?
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

    function getImages($iSystemId, $iCmtId)
    {
    	$sQuery = $this->prepare("SELECT * FROM `{$this->_sTableImages2Entries}` WHERE `system_id`=? AND `cmt_id`=?", $iSystemId, $iCmtId);
    	return $this->getAll($sQuery);
    }

	function deleteImages($iSystemId, $iCmtId)
	{
		$sQuery = $this->prepare("DELETE FROM `{$this->_sTableImages2Entries}` WHERE `system_id`=? AND `cmt_id`=?", $iSystemId, $iCmtId);
    	return $this->query($sQuery);
	}

    function updateRepliesCount($iCmtId, $iCount)
    {
    	$sQuery = $this->prepare("UPDATE `{$this->_sTable}` SET `cmt_replies`=`cmt_replies`+? WHERE `cmt_id`=? LIMIT 1", $iCount, $iCmtId);
		return $this->query($sQuery);
    }

    function deleteAuthorComments ($iAuthorId)
    {
        $isDelOccured = 0;
        $sQuery = $this->prepare("SELECT `cmt_id`, `cmt_parent_id` FROM {$this->_sTable} WHERE `cmt_author_id` = ? AND `cmt_replies` = 0", $iAuthorId);
        $a = $this->getAll ($sQuery);
        for ( reset($a) ; list (, $r) = each ($a) ; )
        {
            $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `cmt_id` = ?", $r['cmt_id']);
            $this->query ($sQuery);
            $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_replies` = `cmt_replies` - 1 WHERE `cmt_id` = ?", $r['cmt_parent_id']);
            $this->query ($sQuery);
            $isDelOccured = 1;
        }
        $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_author_id` = 0 WHERE `cmt_author_id` = ? AND `cmt_replies` != 0", $iAuthorId);
        $this->query ($sQuery);
        if ($isDelOccured)
            $this->query ("OPTIMIZE TABLE {$this->_sTable}");
    }

    function deleteObjectComments ($iObjectId)
    {
        $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `cmt_object_id` = ?", $iObjectId);
        $this->query ($sQuery);
        $this->query ("OPTIMIZE TABLE {$this->_sTable}");
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
}
