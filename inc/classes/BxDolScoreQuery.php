<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolScore
 */
class BxDolScoreQuery extends BxDolObjectQuery
{
    protected $_sTriggerFieldScore;
    protected $_sTriggerFieldCup;
    protected $_sTriggerFieldCdown;

    protected $_iPostTimeout;

    public function __construct(&$oModule)
    {
        parent::__construct($oModule);

        $aSystem = $this->_oModule->getSystemInfo();
        $this->_sTriggerFieldScore = $aSystem['trigger_field_score'];
        $this->_sTriggerFieldCup = $aSystem['trigger_field_cup'];
        $this->_sTriggerFieldCdown = $aSystem['trigger_field_cdown'];

        $this->_iPostTimeout = (int)$aSystem['post_timeout'];

        $this->_sMethodGetEntry = 'getScore';
    }

    public function getPerformedBy($iObjectId, $iStart = 0, $iPerPage = 0)
    {
        $sLimitClause = "";
        if(!empty($iPerPage))
            $sLimitClause = $this->prepareAsString(" LIMIT ?, ?", $iStart, $iPerPage);

        $sQuery = "SELECT 
            	`author_id` AS `id`, 
            	`type` AS `vote_type`, 
            	`date` AS `vote_date` 
            FROM `{$this->_sTableTrack}` 
            WHERE `object_id`=:object_id" . $sLimitClause;

        return $this->getAll($sQuery, array(
            'object_id' => $iObjectId
        ));
    }

    public function isPostTimeoutEnded($iObjectId, $iAuthorId, $sAuthorIp)
    {
        if($this->_iPostTimeout == 0)
            return true;

        $aBindings = array(
            'object_id' => $iObjectId,
            'date' => time() - $this->_iPostTimeout
        );
        $sWhereClause = " AND `object_id` = :object_id AND `date` > :date";

        if(!empty($iAuthorId)) {
            $aBindings['author_id'] = $iAuthorId;

            $sWhereClause .= " AND `author_id` = :author_id";
        }
        else {
            $aBindings['author_nip'] = bx_get_ip_hash($sAuthorIp);

            $sWhereClause .= " AND `author_nip` = :author_nip";
        }

        return (int)$this->getOne("SELECT `object_id` FROM `" . $this->_sTableTrack . "` WHERE 1" . $sWhereClause, $aBindings) == 0;
    }

    public function getScore($iObjectId)
    {
        $aResult = $this->getRow("SELECT `count_up`, `count_down`, `count_up` - `count_down` AS `score` FROM {$this->_sTable} WHERE `object_id` = :object_id LIMIT 1", array(
            'object_id' => $iObjectId
        ));

        if(empty($aResult) || !is_array($aResult))
            $aResult = array('count_up' => 0, 'count_down' => 0, 'score' => 0);

        return $aResult;
    }

    public function putVote($iObjectId, $iAuthorId, $sAuthorIp, $sType)
    {
        $bExists = (int)$this->getOne("SELECT `object_id` FROM `{$this->_sTable}` WHERE `object_id` = :object_id LIMIT 1", array('object_id' => $iObjectId)) != 0;

        if(!$bExists)
            $sQuery = $this->prepare("INSERT INTO {$this->_sTable} SET `object_id` = ?, `count_" . $sType . "` = '1'", $iObjectId);
        else
            $sQuery = $this->prepare("UPDATE `{$this->_sTable}` SET `count_" . $sType . "` = `count_" . $sType . "` + 1 WHERE `object_id` = ?", $iObjectId);

        if((int)$this->query($sQuery) == 0)
            return false;

        if((int)$this->query("INSERT INTO `{$this->_sTableTrack}` SET " . $this->arrayToSQL(array(
            'object_id' => $iObjectId, 
            'author_id' => $iAuthorId, 
            'author_nip' => bx_get_ip_hash($sAuthorIp), 
            'type' => $sType, 
            'date' => time()
        ))) > 0)
        	return $this->lastId();

        return false;
    }

    public function getLegend($iObjectId)
    {
    	$sQuery = $this->prepare("SELECT `type` AS `type`, COUNT(`type`) AS `count` FROM `{$this->_sTableTrack}` WHERE `object_id` = ? GROUP BY `type`", $iObjectId);

    	return $this->getAllWithKey($sQuery, 'type');
    }

    public function getSqlParts($sMainTable, $sMainField)
    {
    	$aResult = parent::getSqlParts($sMainTable, $sMainField);
        if(empty($aResult))
            return $aResult;

		$aResult['fields'] = ", `{$this->_sTable}`.`count_up` AS `score_cup`, `{$this->_sTable}`.`count_down` AS `score_cdown`, (`{$this->_sTable}`.`count_up` - `{$this->_sTable}`.`count_down`) AS `score` ";
        return $aResult;
    }

	protected function _updateTriggerTable($iObjectId, $aEntry)
    {
        $aSet = array(
            $this->_sTriggerFieldScore => $aEntry['score'],
            $this->_sTriggerFieldCup => $aEntry['count_up'],
            $this->_sTriggerFieldCdown => $aEntry['count_down']
        );

        return (int)$this->query("UPDATE `{$this->_sTriggerTable}` SET " . $this->arrayToSQL($aSet) . " WHERE `{$this->_sTriggerFieldId}` = :object_id", array(
        	'object_id' => $iObjectId
        )) > 0;
    }

    protected function _deleteAuthorEntriesTableMain($aTrack)
    {
        return $this->query("UPDATE `{$this->_sTable}` SET `count_" . $aTrack['type'] . "`=`count_" . $aTrack['type'] . "`-1 WHERE `object_id`=:object_id", array(
        	'object_id' => $aTrack['object_id']
        ));
    }

    protected function _deleteAuthorEntriesTableTrigger($aTrack)
    {
        $aScore = $this->getScore($aTrack['object_id']);

        return $this->_updateTriggerTable($aTrack['object_id'], $aScore);
    }
}

/** @} */
