<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
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
    	$sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

    	switch($aParams['type']) {
    	    case 'entry_last':
    	        $aMethod['name'] = 'getRow';
    	        $aMethod['params'][1]['cmt_object_id'] = $aParams['entry_id'];
    	        $sWhereClause = " AND `te`.`cmt_object_id` = :cmt_object_id";
    	        $sOrderClause = "`te`.`cmt_time` DESC";
    	        $sLimitClause = "1";
    	        break;

    	    case 'entries_author_search':
    			$aMethod['name'] = 'getColumn';
    			$sFieldsClause = "`te`.`cmt_object_id`"; 
    			$sWhereClause = " AND `te`.`cmt_author_id` IN (" . $this->implode_escape($aParams['author']) . ")";
    			$sGroupClause = "`te`.`cmt_object_id`";
    			break;

    		case 'entries_keyword_search':
    			$aMethod['name'] = 'getColumn';
    			$sFieldsClause = "`te`.`cmt_object_id`"; 
    			$sWhereClause = " AND `te`.`cmt_text` LIKE " . $this->escape("%" . $aParams['keyword'] . "%");
    			$sGroupClause = "`te`.`cmt_object_id`";
    			break;

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
		$sLimitClause = $sLimitClause ? "LIMIT " . $sLimitClause : "";

		$aMethod['params'][0] = "SELECT
				" . $sFieldsClause . "
            FROM `" . $this->getPrefix() . "cmts` AS `te`" . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

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

	public function insertCategory($aSet)
    {
        $sQuery = "INSERT INTO `" . $this->getPrefix() . "categories` SET " . $this->arrayToSQL($aSet);
        return (int)$this->query($sQuery) > 0;
    }

	public function getCategories($aParams)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tc`.`category` ASC";

        switch($aParams['type']) {
            case 'by_category':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'category' => $aParams['category']
                );

                $sWhereClause = " AND `tc`.`category`=:category ";
                break;

            case 'all_pairs':
            	$aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'category';
                $aMethod['params'][2] = 'visible_for_levels';
            	break;
        }

        $aMethod['params'][0] = "SELECT 
                `tc`.*" . $sSelectClause . " 
            FROM `" . $this->getPrefix() . "categories` AS `tc` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
