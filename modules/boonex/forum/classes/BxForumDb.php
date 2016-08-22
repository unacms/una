<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxForumDb extends BxBaseModTextDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

	public function updateEntries($aSet, $aWhere)
    {
        $sQuery = "UPDATE `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` SET " . $this->arrayToSQL($aSet) . " WHERE " . (!empty($aWhere) ? $this->arrayToSQL($aWhere, ' AND ') : "1");
        return (int)$this->query($sQuery) > 0;
    }

    public function updateLastCommentTimeProfile($iConversationId, $iProfileId, $iCommentId, $iTimestamp)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->getPrefix() . "discussions` SET `lr_profile_id` = ?, `lr_timestamp` = ?, `lr_comment_id` = ? WHERE `id` = ?", $iProfileId, $iTimestamp, $iCommentId, $iConversationId);
        return $this->query($sQuery);
    }

    public function getComments($aParams)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sFieldsClause = "`te`.*"; 
    	$sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = "";

    	switch($aParams['type']) {
			case 'author_comments':
				$aMethod['name'] = 'getPairs';
				$sFieldsClause = "`te`.`cmt_author_id`, COUNT(`te`.`cmt_id`) AS `cmt_count`";
				$sWhereClause = $this->prepareAsString(" AND `te`.`cmt_object_id`=? ", $aParams['object_id']);
				$sGroupClause = "`te`.`cmt_author_id`";
				$aMethod['params'][1] = "cmt_author_id";
				$aMethod['params'][2] = "cmt_count";
				break;
    	}

    	$sGroupClause = $sGroupClause ? "GROUP BY " . $sGroupClause : "";
		$sOrderClause = $sOrderClause ? "ORDER BY " . $sOrderClause : "";

		$aMethod['params'][0] = "SELECT
				" . $sFieldsClause . "
            FROM `" . $this->getPrefix() . "cmts` AS `te`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getUnrepliedDiscussionsNum ($iProfileId)
    {
    	$CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare("SELECT COUNT(`te`.`id`)
            FROM `" . $CNF['TABLE_ENTRIES'] . "` AS `te`
            WHERE `te`.`author`=? AND `te`.`lr_profile_id`<>?", $iProfileId, $iProfileId);

		return $this->getOne($sQuery);
    }

	public function updateStatus($sAction, $aContentInfo)
	{
		$CNF = &$this->_oConfig->CNF;

		$aActions = array(
			'stick' => array($CNF['FIELD_STICK'] => 1),
			'unstick' => array($CNF['FIELD_STICK'] => 0),
			'lock' =>  array($CNF['FIELD_LOCK'] => 1),
			'unlock' =>  array($CNF['FIELD_LOCK'] => 0),
			'hide' =>  array($CNF['FIELD_STATUS_ADMIN'] => 'hidden'),
			'unhide' =>  array($CNF['FIELD_STATUS_ADMIN'] => 'active')
		);

		return $this->updateEntries($aActions[$sAction], array($CNF['FIELD_ID'] => $aContentInfo[$CNF['FIELD_ID']]));
	}
}

/** @} */
