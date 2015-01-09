<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxDolDb');

/**
 * @see BxDolVote
 */
class BxDolVoteQuery extends BxDolDb
{
    protected $_oModule;

    protected $_sTable;
    protected $_sTableTrack;
    protected $_sTriggerTable;
    protected $_sTriggerFieldId;
    protected $_sTriggerFieldRate;
    protected $_sTriggerFieldCount;

    protected $_iPostTimeout;

    public function __construct(&$oModule)
    {
        parent::__construct();

        $this->_oModule = $oModule;

        $aSystem = $this->_oModule->getSystemInfo();
        $this->_sTable = $aSystem['table_main'];
        $this->_sTableTrack = $aSystem['table_track'];
        $this->_sTriggerTable = $aSystem['trigger_table'];
        $this->_sTriggerFieldId = $aSystem['trigger_field_id'];
        $this->_sTriggerFieldRate = $aSystem['trigger_field_rate'];
        $this->_sTriggerFieldCount = $aSystem['trigger_field_count'];

        $this->_iPostTimeout = (int)$aSystem['post_timeout'];
    }

    public function isPostTimeoutEnded($iObjectId, $sAuthorIp)
    {
        if($this->_iPostTimeout == 0)
            return true;

        $iDate = time() - $this->_iPostTimeout;
        $iAuthorNip = ip2long($sAuthorIp);
        $sQuery = $this->prepare("SELECT `object_id` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_nip` = ? AND `date` > ?", $iObjectId, $iAuthorNip, $iDate);
        return (int)$this->getOne($sQuery) == 0;
    }

    public function getVote($iObjectId)
    {
        $sQuery = $this->prepare("SELECT `count` as `count`, `sum` as `sum`, ROUND(`sum` / `count`, 2) AS `rate` FROM {$this->_sTable} WHERE `object_id` = ? LIMIT 1", $iObjectId);

        $aResult = $this->getRow($sQuery);
        if(empty($aResult) || !is_array($aResult))
            $aResult = array('count' => 0, 'sum' => 0, 'rate' => 0);

        return $aResult;
    }

    public function isVoted($iObjectId, $iAuthorId)
    {
        $sQuery = $this->prepare("SELECT `object_id` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        return (int)$this->getOne($sQuery) != 0;
    }

    public function putVote($iObjectId, $iAuthorId, $sAuthorIp, $iValue, $bUndo = false)
    {
        $sQuery = $this->prepare("SELECT `object_id` FROM `{$this->_sTable}` WHERE `object_id` = ? LIMIT 1", $iObjectId);
        $bExists = (int)$this->getOne($sQuery) != 0;
        if(!$bExists && $bUndo)
            return false;

        if(!$bExists)
            $sQuery = $this->prepare("INSERT INTO {$this->_sTable} SET `object_id` = ?, `count` = '1', `sum` = ?", $iObjectId, $iValue);
        else
            $sQuery = $this->prepare("UPDATE `{$this->_sTable}` SET `count` = `count` " . ($bUndo ? "-" : "+") . " 1, `sum` = `sum` " . ($bUndo ? "-" : "+") . " ? WHERE `object_id` = ?", $iValue, $iObjectId);

        if((int)$this->query($sQuery) == 0)
            return false;

        if($bUndo)
            $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        else {
            $iNow = time();
            $iAuthorNip = ip2long($sAuthorIp);
            $sQuery = $this->prepare("INSERT INTO `{$this->_sTableTrack}` SET `object_id` = ?, `author_id` = ?, `author_nip` = ?, `value` = ?, `date` = ?", $iObjectId, $iAuthorId, $iAuthorNip, $iValue, $iNow);
        }

        return (int)$this->query($sQuery) > 0;
    }

    public function getSqlParts($sMainTable, $sMainField)
    {
        if(empty($sMainTable) || empty($sMainField))
            return array();

        return array (
            'fields' => ",`{$this->_sTable}`.`count` as `vote_count`, (`{$this->_sTable}`.`sum` / `{$this->_sTable}`.`count`) AS `vote_rate` ",
            'join' => " LEFT JOIN `{$this->_sTable}` ON (`{$this->_sTable}`.`object_id` = `{$sMainTable}`.`{$sMainField}`) ",
        );
    }

    public function getVotedBy($iObjectId)
    {
        $sQuery = $this->prepare("SELECT `author_id` FROM `{$this->_sTableTrack}` WHERE `object_id`=?", $iObjectId);
        return $this->getColumn($sQuery);
    }

    public function deleteObjectVotes($iObjectId)
    {
        $sQuery = $this->prepare("DELETE FROM {$this->_sTable} WHERE `object_id` = ?", $iObjectId);
        $this->query ($sQuery);

        $this->query ("OPTIMIZE TABLE {$this->_sTable}");

        $sQuery = $this->prepare("DELETE FROM {$this->_sTableTrack} WHERE `object_id` = ?", $iObjectId);
        $this->query ($sQuery);

        $this->query ("OPTIMIZE TABLE {$this->_sTableTrack}");
    }

    public function updateTriggerTable($iObjectId)
    {
        $aVote = $this->getVote($iObjectId);
        if(empty($aVote) || !is_array($aVote))
            return false;

        $sQuery = $this->prepare("UPDATE `{$this->_sTriggerTable}` SET `{$this->_sTriggerFieldCount}` = ?, `{$this->_sTriggerFieldRate}` = ? WHERE `{$this->_sTriggerFieldId}` = ?", $aVote['count'], $aVote['rate'], $iObjectId);
        return (int)$this->query($sQuery) > 0;
    }
}

/** @} */
