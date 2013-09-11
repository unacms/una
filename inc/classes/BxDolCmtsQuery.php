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
    protected $_sTableTrack;
    protected $_sTriggerTable;
    protected $_sTriggerFieldId;
    protected $_sTriggerFieldTitle;
    protected $_sTriggerFieldComments;

    function BxDolCmtsQuery(&$oMain)
    {
    	$this->_oMain = $oMain;

    	$aSystem = $this->_oMain->getSystemInfo();
        $this->_sTable = $aSystem['table_cmts'];
        $this->_sTableTrack = $aSystem['table_track'];
        $this->_sTriggerTable = $aSystem['trigger_table'];
        $this->_sTriggerFieldId = $aSystem['trigger_field_id'];
        $this->_sTriggerFieldTitle = $aSystem['trigger_field_title'];
        $this->_sTriggerFieldComments = $aSystem['trigger_field_comments'];

        parent::BxDolDb();
    }

    function getTableName ()
    {
        return $this->_sTable;
    }

	function getCommentsCount ($iId, $iCmtVParentId = 0) {
		$sWhereParent = '';
        if((int)$iCmtVParentId >= 0)
        	$sWhereParent = $this->prepare(" AND `cmt_vparent_id` = ?", $iCmtVParentId);

		$sQuery = $this->prepare("SELECT COUNT(*) FROM `{$this->_sTable}` WHERE `cmt_object_id` = ?" . $sWhereParent, $iId);
		return (int)$this->getOne($sQuery);
	}

    function getComments ($iId, $iCmtVParentId = 0, $iAuthorId = 0, $aOrder = array(), $iStart = 0, $iCount = -1)
    {
        global $sHomeUrl;
        $iTimestamp = time();
        $sFields = "'' AS `cmt_rated`,";

        $sJoin = "";
        if ($iAuthorId) {
            $sFields = "`r`.`cmt_rate` AS `cmt_rated`,";
            $sJoin = $this->prepare(" LEFT JOIN `{$this->_sTableTrack}` AS `r` ON (`r`.`cmt_system_id` = ? AND `r`.`cmt_id` = `c`.`cmt_id` AND `r`.`cmt_rate_author_id` = ?)", $this->_oMain->getSystemId(), $iAuthorId);
        }

        $sWhereParent = '';
        if((int)$iCmtVParentId >= 0)
        	$sWhereParent = $this->prepare(" AND `c`.`cmt_vparent_id` = ?", $iCmtVParentId);

        $sOder = " ORDER BY `c`.`cmt_time` ASC";
        if(isset($aOrder['by']) && isset($aOrder['way'])) {
        	$aOrder['way'] = strtoupper(in_array($aOrder['way'], array(BX_CMT_ORDER_WAY_ASC, BX_CMT_ORDER_WAY_DESC)) ? $aOrder['way'] : BX_CMT_ORDER_WAY_ASC);

        	switch($aOrder['by']) {
        		case BX_CMT_ORDER_BY_DATE:
        			$sOder = " ORDER BY `c`.`cmt_time` " . $aOrder['way'];
        			break;

        		case BX_CMT_ORDER_BY_POPULAR:
        			$sOder = " ORDER BY `c`.`cmt_rate` " . $aOrder['way'];
        			break;

        		case BX_CMT_ORDER_BY_CONNECTION:
        			$sFields .= " IF(NOT ISNULL(`tcc`.`id`), 1, 0) AS `cmt_author_contact`,";
        			$sFields .= " IF(NOT ISNULL(`tcf`.`id`), 1, 0) AS `cmt_author_friend`,";

        			$sJoin .= $this->prepare(" LEFT JOIN `sys_profiles_conn_contacts` AS `tcc` ON ((`c`.`cmt_author_id`=`tcc`.`initiator` AND `tcc`.`content`=?) OR (`c`.`cmt_author_id`=`tcc`.`content` AND `tcc`.`initiator`=?))", $iAuthorId, $iAuthorId);
        			$sJoin .= $this->prepare(" LEFT JOIN `sys_profiles_conn_friends` AS `tcf` ON ((`c`.`cmt_author_id`=`tcf`.`initiator` AND `tcf`.`content`=?) OR (`c`.`cmt_author_id`=`tcf`.`content` AND `tcf`.`initiator`=?))", $iAuthorId, $iAuthorId);

        			$sOder = " ORDER BY `cmt_author_friend` " . $aOrder['way'] . ",`cmt_author_contact` " . $aOrder['way'] . ",`c`.`cmt_time` ASC";
        			break;
	        }
        }
         
        $sLimit = $iCount != -1 ? $this->prepare(" LIMIT ?, ?", (int)$iStart, (int)$iCount) : '';

        $sQuery = $this->prepare("SELECT
                $sFields
                `c`.`cmt_id`,
                `c`.`cmt_parent_id`,
                `c`.`cmt_vparent_id`,
                `c`.`cmt_object_id`,
                `c`.`cmt_author_id`,
                `c`.`cmt_level`,
                `c`.`cmt_text`,
                `c`.`cmt_mood`,
                `c`.`cmt_rate`,
                `c`.`cmt_rate_count`,
                `c`.`cmt_replies`,
                (? - UNIX_TIMESTAMP(`c`.`cmt_time`)) AS `cmt_secs_ago`
            FROM `{$this->_sTable}` AS `c`
            $sJoin
            WHERE `c`.`cmt_object_id` = ?" . $sWhereParent . $sOder . $sLimit, $iTimestamp, $iId);

        $a = $this->getAll($sQuery);

        for(reset($a) ; list ($k) = each ($a) ; ) {
            $a[$k]['cmt_text'] = str_replace("[ray_url]", $sHomeUrl, $a[$k]['cmt_text']);
             $a[$k]['cmt_ago'] = _format_when ($a[$k]['cmt_secs_ago']);
        }

        return $a;
    }

    function getComment ($iId, $iCmtId, $iAuthorId = 0)
    {
        global $sHomeUrl;

        $iTimestamp = time();
        $sFields = "'' AS `cmt_rated`,";
        $sJoin = '';
        if ($iAuthorId) {
            $sFields = '`r`.`cmt_rate` AS `cmt_rated`,';
            $sJoin = $this->prepare("LEFT JOIN {$this->_sTableTrack} AS `r` ON (`r`.`cmt_system_id` = ? AND `r`.`cmt_id` = `c`.`cmt_id` AND `r`.`cmt_rate_author_id` = ?)", $this->_oMain->getSystemId(), $iAuthorId);
        }
        $sQuery = $this->prepare("SELECT
                $sFields
                `c`.`cmt_id`,
                `c`.`cmt_parent_id`,
                `c`.`cmt_vparent_id`,
                `c`.`cmt_object_id`,
                `c`.`cmt_author_id`,
                `c`.`cmt_level`,
                `c`.`cmt_text`,
                `c`.`cmt_mood`,
                `c`.`cmt_rate`,
                `c`.`cmt_rate_count`,
                `c`.`cmt_replies`,
                ($iTimestamp - UNIX_TIMESTAMP(`c`.`cmt_time`)) AS `cmt_secs_ago`
            FROM {$this->_sTable} AS `c`
            $sJoin
            WHERE `c`.`cmt_object_id` = ? AND `c`.`cmt_id` = ?
            LIMIT 1", $iId, $iCmtId);
        $aComment = $this->getRow($sQuery);

        $aComment['cmt_text'] = str_replace("[ray_url]", $sHomeUrl, $aComment['cmt_text']);
        $aComment['cmt_ago'] = _format_when($aComment['cmt_secs_ago']);

        return $aComment;
    }

    function getCommentSimple ($iId, $iCmtId)
    {
        $iTimestamp = time();
        $sQuery = $this->prepare("
            SELECT
                *, ($iTimestamp - UNIX_TIMESTAMP(`c`.`cmt_time`)) AS `cmt_secs_ago`
            FROM {$this->_sTable} AS `c`
            WHERE `cmt_object_id` = ? AND `cmt_id` = ?
            LIMIT 1", $iId, $iCmtId);
        return $this->getRow($sQuery);
    }

    function addComment ($iId, $iCmtParentId, $iAuthorId, $sText)
    {
    	$iLevel = 0;
    	$iCmtVisualParentId = 0;
    	if((int)$iCmtParentId > 0) {
    		$sQuery = $this->prepare("SELECT `cmt_vparent_id`, `cmt_level` FROM {$this->_sTable} WHERE `cmt_id`=? LIMIT 1", $iCmtParentId);
    		$aParent = $this->getRow($sQuery);

    		$iLevel = (int)$aParent['cmt_level'] + 1;
    		$iCmtVisualParentId = $iLevel > $this->_oMain->getMaxLevel() ? $aParent['cmt_vparent_id'] : $iCmtParentId;
    	}

        $sQuery = $this->prepare("INSERT INTO {$this->_sTable} SET
            `cmt_parent_id` = ?,
            `cmt_vparent_id` = ?,
            `cmt_object_id` = ?,
            `cmt_author_id` = ?,
            `cmt_level` = ?,
            `cmt_text` = ?,
            `cmt_time` = NOW()", $iCmtParentId, $iCmtVisualParentId, $iId, $iAuthorId, $iLevel, $sText);
        if(!$this->query($sQuery))
            return false;

        $iRet = $this->lastId();

        if($iCmtParentId) {
            $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_replies` = `cmt_replies` + 1 WHERE `cmt_id` = ? LIMIT 1", $iCmtParentId);
            $this->query ($sQuery);
        }

        return $iRet;
    }

    function removeComment ($iId, $iCmtId, $iCmtParentId)
    {
        $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `cmt_object_id` = ? AND `cmt_id` = ? LIMIT 1", $iId, $iCmtId);
        if (!$this->query($sQuery))
            return false;

        $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_replies` = `cmt_replies` - 1 WHERE `cmt_id` = ? LIMIT 1", $iCmtParentId);
        $this->query ($sQuery);

        return true;
    }

    function updateComment ($iId, $iCmtId, $sText)
    {
        $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_text` = ?  WHERE `cmt_object_id` = ? AND `cmt_id` = ? LIMIT 1", $sText, $iId, $iCmtId);
        return $this->query($sQuery);
    }

    function rateComment ($iSystemId, $iCmtId, $iRate, $iAuthorId, $sAuthorIp)
    {
        $iTimestamp = time();
        $sQuery = $this->prepare("INSERT IGNORE INTO {$this->_sTableTrack} SET
            `cmt_system_id` = ?,
            `cmt_id` = ?,
            `cmt_rate` = ?,
            `cmt_rate_author_id` = ?,
            `cmt_rate_author_nip` = INET_ATON(?),
            `cmt_rate_ts` = ?", $iSystemId, $iCmtId, $iRate, $iAuthorId, $sAuthorIp, $iTimestamp);
        if ($this->query($sQuery))
        {
            $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_rate` = `cmt_rate` + ?, `cmt_rate_count` = `cmt_rate_count` + 1 WHERE `cmt_id` = ? LIMIT 1", $iRate, $iCmtId);
            $this->query($sQuery);
            return true;
        }

        return false;
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

    function getObjectCommentsCount ($iObjectId, $iParentId = -1)
    {
        $sWhere = '';
        if ($iParentId != -1)
            $sWhere = $this->prepare(" AND `cmt_parent_id` = ?", $iParentId);
        $sQuery = $this->prepare("SELECT COUNT(*) FROM `" . $this->_sTable ."` WHERE `cmt_object_id` = ? " . $sWhere, $iObjectId);
        return $this->getOne ($sQuery);
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

    function maintenance() {
        $iTimestamp = time();
        $iDeletedRecords = $this->query("DELETE FROM {$this->_sTableTrack} WHERE `cmt_rate_ts` < ($iTimestamp - " . (int)BX_OLD_CMT_VOTES . ")");
        if ($iDeletedRecords)
            $this->query("OPTIMIZE TABLE {$this->_sTableTrack}");
        return $iDeletedRecords;
    }
}

