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

    public function updateLastCommentTimeProfile($iConversationId, $iProfileId, $iCommentId, $iTimestamp)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->getPrefix() . "discussions` SET `last_reply_profile_id` = ?, `last_reply_timestamp` = ?, `last_reply_comment_id` = ? WHERE `id` = ?", $iProfileId, $iTimestamp, $iCommentId, $iConversationId);
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
            WHERE `te`.`author`=? AND `te`.`last_reply_profile_id`<>?", $iProfileId, $iProfileId);

		return $this->getOne($sQuery);
    }

    public function getMessagesPreviews ($iProfileId, $iStart = 0, $iLimit = 4)
    {
    	/*
        $sQuery = $this->prepare("SELECT `c`.`id`, `c`.`text`, `c`.`author`, `cmts`.`cmt_text`, `c`.`last_reply_profile_id`, `c`.`comments`, (`c`.`comments` - `f`.`read_comments`) AS `unread_messages`, `last_reply_timestamp`
            FROM `" . $this->getPrefix() . "discussion2folder` as `f`
            INNER JOIN `" . $this->getPrefix() . "discussions` AS `c` ON (`c`.`id` = `f`.`discussion_id`)
            LEFT JOIN `" . $this->getPrefix() . "cmts` AS `cmts` ON (`cmts`.`cmt_id` = `c`.`last_reply_comment_id`)
            WHERE `f`.`collaborator` = ? AND `f`.`folder_id` = ?
            ORDER BY `c`.`last_reply_timestamp` DESC
            LIMIT ?, ?", $iProfileId, $iFolderId, $iStart, $iLimit);
        return $this->getAll($sQuery);
        */
    	return array();
    }
}

/** @} */
