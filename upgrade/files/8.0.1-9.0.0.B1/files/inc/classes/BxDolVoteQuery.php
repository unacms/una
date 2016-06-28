<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * @see BxDolVote
 */
class BxDolVoteQuery extends BxDolObjectQuery
{
    protected $_iPostTimeout;

    public function __construct(&$oModule)
    {
        parent::__construct($oModule);

        $aSystem = $this->_oModule->getSystemInfo();
        $this->_sTriggerFieldRate = $aSystem['trigger_field_rate'];

        $this->_iPostTimeout = (int)$aSystem['post_timeout'];

        $this->_sMethodGetEntry = 'getVote';
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

        if($bUndo) {
        	$sQuery = $this->prepare("SELECT `id` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? AND `author_id` = ? LIMIT 1", $iObjectId, $iAuthorId);
        	$iId = (int)$this->getOne($sQuery);

            $sQuery = $this->prepare("DELETE FROM `{$this->_sTableTrack}` WHERE `id` = ? LIMIT 1", $iId);
            if((int)$this->query($sQuery) > 0)
            	return $iId;
        }
        else {
            $iNow = time();
            $iAuthorNip = ip2long($sAuthorIp);
            $sQuery = $this->prepare("INSERT INTO `{$this->_sTableTrack}` SET `object_id` = ?, `author_id` = ?, `author_nip` = ?, `value` = ?, `date` = ?", $iObjectId, $iAuthorId, $iAuthorNip, $iValue, $iNow);
            if((int)$this->query($sQuery) > 0)
            	return $this->lastId();
        }

        return false;
    }

    public function getLegend($iObjectId)
    {
    	$sQuery = $this->prepare("SELECT `value` AS `value`, COUNT(`value`) AS `count` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? GROUP BY `value`", $iObjectId);

    	return $this->getAllWithKey($sQuery, 'value');
    }

    public function getSqlParts($sMainTable, $sMainField)
    {
    	$aResult = parent::getSqlParts($sMainTable, $sMainField);
        if(empty($aResult))
            return $aResult;

		$aResult['fields'] = ",`{$this->_sTable}`.`count` as `vote_count`, (`{$this->_sTable}`.`sum` / `{$this->_sTable}`.`count`) AS `vote_rate` ";
        return $aResult;
    }
}

/** @} */
