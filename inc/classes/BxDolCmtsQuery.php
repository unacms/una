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
    var $_aSystem; ///< current voting system
    var $_sTable;
    var $_sTableTrack;

    function BxDolCmtsQuery(&$aSystem)
    {
        $this->_aSystem = &$aSystem;
        $this->_sTable = $this->_aSystem['table_cmts'];
        $this->_sTableTrack = $this->_aSystem['table_track'];
        parent::BxDolDb();
    }

    function getTableName ()
    {
        return $this->_sTable;
    }

    function getComments ($iId, $iCmtParentId = 0, $iAuthorId = 0, $sCmtOrder = 'ASC', $iStart = 0, $iCount = -1)
    {
        global $sHomeUrl;

        $sFields = "'' AS `cmt_rated`,";
        $sJoin = '';
        if ($iAuthorId) {
            $sFields = '`r`.`cmt_rate` AS `cmt_rated`,';
            $sJoin = $this->prepare("LEFT JOIN {$this->_sTableTrack} AS `r` ON (`r`.`cmt_system_id` = ? AND `r`.`cmt_id` = `c`.`cmt_id` AND `r`.`cmt_rate_author_id` = ?)", $this->_aSystem['system_id'], $iAuthorId);
        }
        $sQuery = $this->prepare("SELECT
                $sFields
                `c`.`cmt_id`,
                `c`.`cmt_parent_id`,
                `c`.`cmt_object_id`,
                `c`.`cmt_author_id`,
                `c`.`cmt_text`,
                `c`.`cmt_mood`,
                `c`.`cmt_rate`,
                `c`.`cmt_rate_count`,
                `c`.`cmt_replies`,
                (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`c`.`cmt_time`)) AS `cmt_secs_ago`,
                `p`.`id` AS `cmt_author_name`
            FROM {$this->_sTable} AS `c`
            LEFT JOIN `sys_profiles` AS `p` ON (`p`.`id` = `c`.`cmt_author_id`)
            $sJoin
            WHERE `c`.`cmt_object_id` = ? AND `c`.`cmt_parent_id` = ?
            ORDER BY `c`.`cmt_time` " . (strtoupper($sCmtOrder) == 'ASC' ? 'ASC' : 'DESC') . ($iCount != -1 ? ' LIMIT ' . (int)$iStart . ', ' . (int)$iCount : ''), $iId, $iCmtParentId);

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

        $sFields = "'' AS `cmt_rated`,";
        $sJoin = '';
        if ($iAuthorId) {
            $sFields = '`r`.`cmt_rate` AS `cmt_rated`,';
            $sJoin = $this->prepare("LEFT JOIN {$this->_sTableTrack} AS `r` ON (`r`.`cmt_system_id` = ? AND `r`.`cmt_id` = `c`.`cmt_id` AND `r`.`cmt_rate_author_id` = ?)", $this->_aSystem['system_id'], $iAuthorId);
        }
        $sQuery = $this->prepare("SELECT
                $sFields
                `c`.`cmt_id`,
                `c`.`cmt_parent_id`,
                `c`.`cmt_object_id`,
                `c`.`cmt_author_id`,
                `c`.`cmt_text`,
                `c`.`cmt_mood`,
                `c`.`cmt_rate`,
                `c`.`cmt_rate_count`,
                `c`.`cmt_replies`,
                (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`c`.`cmt_time`)) AS `cmt_secs_ago`,
                `p`.`NickName` AS `cmt_author_name`
            FROM {$this->_sTable} AS `c`
            LEFT JOIN `Profiles` AS `p` ON (`p`.`ID` = `c`.`cmt_author_id`)
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
        $sQuery = $this->prepare("
            SELECT
                *, (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(`c`.`cmt_time`)) AS `cmt_secs_ago`
            FROM {$this->_sTable} AS `c`
            WHERE `cmt_object_id` = ? AND `cmt_id` = ?
            LIMIT 1", $iId, $iCmtId);
        return $this->getRow($sQuery);
    }

    function addComment ($iId, $iCmtParentId, $iAuthorId, $sText, $iMood)
    {
        $sQuery = $this->prepare("INSERT INTO {$this->_sTable} SET
            `cmt_parent_id` = ?,
            `cmt_object_id` = ?,
            `cmt_author_id` = ?,
            `cmt_text` = ?,
            `cmt_mood` = ?,
            `cmt_time` = NOW()", $iCmtParentId, $iId, $iAuthorId, $sText, $iMood);
        if (!$this->query($sQuery))
        {
            return false;
        }

        $iRet = $this->lastId();

        if ($iCmtParentId) {
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

    function updateComment ($iId, $iCmtId, $sText, $iMood)
    {
        $sQuery = $this->prepare("UPDATE {$this->_sTable} SET `cmt_text` = ?, `cmt_mood` = ?  WHERE `cmt_object_id` = ? AND `cmt_id` = ? LIMIT 1", $sText, $iMood, $iId, $iCmtId);
        return $this->query($sQuery);
    }

    function rateComment ($iSystemId, $iCmtId, $iRate, $iAuthorId, $sAuthorIp)
    {
        $sQuery = $this->prepare("INSERT IGNORE INTO {$this->_sTableTrack} SET
            `cmt_system_id` = ?,
            `cmt_id` = ?,
            `cmt_rate` = ?,
            `cmt_rate_author_id` = ?,
            `cmt_rate_author_nip` = INET_ATON(?),
            `cmt_rate_ts` = UNIX_TIMESTAMP()", $iSystemId, $iCmtId, $iRate, $iAuthorId, $sAuthorIp);
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

    function updateTriggerTable($iId, $iCount)
    {
        $sQuery = $this->prepare("UPDATE `{$this->_aSystem['trigger_table']}` SET `{$this->_aSystem['trigger_field_comments']}` = ? WHERE `{$this->_aSystem['trigger_field_id']}` = ? LIMIT 1", $iCount, $iId);
        return $this->query($sQuery);
    }

    function maintenance() {
        $iDeletedRecords = $this->query("DELETE FROM {$this->_sTableTrack} WHERE `cmt_rate_ts` < (UNIX_TIMESTAMP() - " . (int)BX_OLD_CMT_VOTES . ")");
        if ($iDeletedRecords)
            $this->query("OPTIMIZE TABLE {$this->_sTableTrack}");
        return $iDeletedRecords;
    }

}

