<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolDb');

/**
 * @see BxDolVoting
 */
class BxDolVotingQuery extends BxDolDb
{
    var $_aSystem; ///< current voting system

    function BxDolVotingQuery(&$aSystem)
    {
        $this->_aSystem = &$aSystem;
        parent::BxDolDb();
    }

    function  getVote ($iId)
    {
        $sPre = $this->_aSystem['row_prefix'];
        $sTable = $this->_aSystem['table_rating'];

        $sQuery = $this->prepare("SELECT `{$sPre}rating_count` as `count`, (`{$sPre}rating_sum` / `{$sPre}rating_count`) AS `rate` FROM {$sTable} WHERE `{$sPre}id` = ? LIMIT 1", $iId);
        return $this->getRow($sQuery);
    }

    function  putVote ($iId, $sIp, $iRate)
    {
        $sPre = $this->_aSystem['row_prefix'];

        $sTable = $this->_aSystem['table_rating'];

        $sQuery = $this->prepare("SELECT `{$sPre}id` FROM $sTable WHERE `{$sPre}id` = ? LIMIT 1", $iId);
        if ($this->getOne($sQuery))
        {
            $sQuery = $this->prepare("UPDATE {$sTable} SET `{$sPre}rating_count` = `{$sPre}rating_count` + 1, `{$sPre}rating_sum` = `{$sPre}rating_sum` + ? WHERE `{$sPre}id` = ?", $iRate, $iId);
            $ret = $this->query ($sQuery);
        }
        else
        {
            $sQuery = $this->prepare("INSERT INTO {$sTable} SET `{$sPre}id` = ?, `{$sPre}rating_count` = '1', `{$sPre}rating_sum` = ?", $iId, $iRate);
            $ret = $this->query ($sQuery);
        }
        if (!$ret) return $ret;

        $sTable = $this->_aSystem['table_track'];
        $sQuery = $this->prepare("INSERT INTO {$sTable} SET `{$sPre}id` = ?, `{$sPre}ip` = ?, `{$sPre}date` = NOW()", $iId, $sIp);
        return $this->query ($sQuery);
    }

    function isDublicateVote ($iId, $sIp)
    {
        $sPre = $this->_aSystem['row_prefix'];
        $sTable = $this->_aSystem['table_track'];
        $iSec = $this->_aSystem['is_duplicate'];

        $sQuery = $this->prepare("SELECT `{$sPre}id` FROM {$sTable} WHERE `{$sPre}ip` = ? AND `{$sPre}id` = ? AND `{$sPre}date` > FROM_UNIXTIME(UNIX_TIMESTAMP() - ?)", $sIp, $iId, $iSec);
        return $this->getOne ($sQuery);

    }

    function getSqlParts ($sMailTable, $sMailField)
    {
        if ($sMailTable)
            $sMailTable .= '.';

        if ($sMailField)
            $sMailField = $sMailTable.$sMailField;

        $sPre = $this->_aSystem['row_prefix'];
        $sTable = $this->_aSystem['table_rating'];

        return array (
            'fields' => ",$sTable.`{$sPre}rating_count` as `voting_count`, ($sTable.`{$sPre}rating_sum` / $sTable.`{$sPre}rating_count`) AS `voting_rate` ",
            'join' => $this->prepare(" LEFT JOIN $sTable ON ({$sTable}.`{$sPre}id` = ?) ", $sMailField),
        );
    }

    function deleteVotings ($iId)
    {
        $sPre = $this->_aSystem['row_prefix'];

        $sTable = $this->_aSystem['table_track'];
        $sQuery = $this->prepare("DELETE FROM {$sTable} WHERE `{$sPre}id` = ?", $iId);
        $this->query ($sQuery);

        $sTable = $this->_aSystem['table_rating'];
        $sQuery = $this->prepare("DELETE FROM {$sTable} WHERE `{$sPre}id` = ?", $iId);
        return $this->query ($sQuery);
    }

    function getTopVotedItem ($iDays, $sJoinTable = '', $sJoinField = '', $sWhere = '')
    {
        $sPre = $this->_aSystem['row_prefix'];
        $sTable = $this->_aSystem['table_track'];

        $sJoin = $sJoinTable && $sJoinField ? " INNER JOIN $sJoinTable ON ({$sJoinTable}.{$sJoinField} = $sTable.`{$sPre}id`) " : '';

        $sQuery = $this->prepare("SELECT $sTable.`{$sPre}id`, COUNT($sTable.`{$sPre}id`) AS `voting_count` FROM {$sTable} $sJoin WHERE $sTable.`{$sPre}date` >= DATE_SUB(NOW(), INTERVAL ? DAY) $sWhere GROUP BY $sTable.`{$sPre}id` HAVING `voting_count` > 2 ORDER BY `voting_count` DESC LIMIT 1", $iDays);
        return $this->getOne ($sQuery);
    }

    function getVotedItems ($sIp)
    {
        $sPre = $this->_aSystem['row_prefix'];
        $sTable = $this->_aSystem['table_track'];
        $iSec = $this->_aSystem['is_duplicate'];
        $sQuery = $this->prepare("SELECT `{$sPre}id` FROM {$sTable} WHERE `{$sPre}ip` = ? AND `{$sPre}date` > FROM_UNIXTIME(UNIX_TIMESTAMP() - ?) ORDER BY `{$sPre}date` DESC", $sIp, $iSec);
        return $this->getAll ($sQuery);
    }

    function updateTriggerTable($iId, $fRate, $iCount) {
        $sQuery = $this->prepare("UPDATE `{$this->_aSystem['trigger_table']}` SET `{$this->_aSystem['trigger_field_rate']}` = ?, `{$this->_aSystem['trigger_field_rate_count']}` = ? WHERE `{$this->_aSystem['trigger_field_id']}` = ?", $fRate, $iCount, $iId);
        return $this->query($sQuery);
    }
}

