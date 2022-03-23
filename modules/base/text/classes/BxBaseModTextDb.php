<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxBaseModTextDb extends BxBaseModGeneralDb
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function searchByAuthorTerm($iAuthor, $sTerm, $iLimit)
    {
        $CNF = &$this->_oConfig->CNF;

        if (empty($CNF['FIELDS_QUICK_SEARCH']))
            return array();

		$aBindings = array(
		    'author' => $iAuthor
		);

        $sWhere = '';
        foreach ($CNF['FIELDS_QUICK_SEARCH'] as $sField) {
        	$aBindings[$sField] = '%' . $sTerm . '%';

            $sWhere .= " OR `c`.`$sField` LIKE :" . $sField;
        }

        $sOrderBy = $this->prepareAsString(" ORDER BY `c`.`added` DESC LIMIT ?", (int)$iLimit);

        $sQuery = "SELECT `c`.* FROM `" . $this->_oConfig->CNF['TABLE_ENTRIES'] . "` AS `c` WHERE `c`.`" . $CNF['FIELD_AUTHOR'] . "`=:author AND (0 $sWhere)" . $sOrderBy;
        return $this->getAll($sQuery, $aBindings);
    }
    
    /**
     * 
     * Internal Polls related methods. 
     * 
     */
    public function isPollPerformed($iPollId, $iAuthorId, $iAuthorIp = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS_ANSWERS_VOTES_TRACK']))
            return false;

        $iAuthorId = (int)$iAuthorId;

        $aBindings = array('author_id' => $iAuthorId);
        $sWhereClause = "AND `author_id`=:author_id";

        if(empty($iAuthorId)) {
            $aBindings['author_nip'] = $iAuthorIp;
            $sWhereClause .= " AND `author_nip`=:author_nip";
        }

        $aAnswers = $this->getPollAnswers(array('type' => 'poll_id_pairs', 'poll_id' => $iPollId));
        return (int)$this->getOne("SELECT `object_id` FROM `" . $CNF['TABLE_POLLS_ANSWERS_VOTES_TRACK'] . "` WHERE `object_id` IN (" . $this->implode_escape(array_keys($aAnswers)) . ") " . $sWhereClause . " LIMIT 1", $aBindings) != 0;
    }

    public function getPolls($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS']))
            return false;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sJoinClause = $sWhereClause = $sOrderByClause = "";

        $sSelectClause = "`tp`.*";
        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause .= " AND `tp`.`id`=:id";
                break;

            case 'answer_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'answer_id' => $aParams['answer_id']
                );

                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_POLLS_ANSWERS'] . "` AS `ta` ON `tp`.`" . $CNF['FIELD_POLL_ID'] . "`=`ta`.`poll_id`";
                $sWhereClause .= " AND `ta`.`id`=:answer_id";
                break;

            case 'content_id':
                $aMethod['params'][1] = array(
                    'content_id' => $aParams['content_id']
                );

                $sWhereClause .= " AND `tp`.`content_id`=:content_id";
                break;
            
            case 'content_id_ids':
                $aMethod['name'] = 'getColumn';
                $aMethod['params'][1] = array(
                    'content_id' => $aParams['content_id']
                );

                $sSelectClause = "`tp`.`" . $CNF['FIELD_POLL_ID'] . "`";
                $sWhereClause .= " AND `tp`.`content_id`=:content_id";
                break;

            case 'author_id':
                $aMethod['params'][1] = array(
                    'author_id' => $aParams['author_id']
                );

                $sWhereClause .= " AND `tp`.`author_id`=:author_id";

                if(isset($aParams['unused']) && $aParams['unused'] === true) {
                    $aMethod['params'][1]['content_id'] = 0;

                    $sWhereClause .= " AND `tp`.`content_id`=:content_id";
                }
                break;
        }

        if(!empty($sOrderByClause))
            $sOrderByClause = "ORDER BY " . $sOrderByClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_POLLS'] . "` AS `tp` " . $sJoinClause . " WHERE 1 " . $sWhereClause . $sOrderByClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getPollInfoById($iId)
    {
        return $this->getPolls(array('type' => 'id', 'id' => $iId));
    }

    public function updatePolls($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS']) || empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `" . $CNF['TABLE_POLLS'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function deletePolls($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS']))
            return false;

        $sWhereClause = $this->arrayToSQL($aParams, " AND ");
        $aPolls = $this->getAll("SELECT * FROM `" . $CNF['TABLE_POLLS'] . "` WHERE " . $sWhereClause);
        if(empty($aPolls) || !is_array($aPolls))
            return true;

        return $this->_deletePolls($aPolls);
    }

    public function deletePollsByIds($mixedId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS']))
            return false;

        $sWhereClause = "`id` IN (" . $this->implode_escape(is_array($mixedId) ? $mixedId : array($mixedId)) . ")";
        $aPolls = $this->getAll("SELECT * FROM `" . $CNF['TABLE_POLLS'] . "` WHERE " . $sWhereClause);
        if(empty($aPolls) || !is_array($aPolls))
            return true;

        return $this->_deletePolls($aPolls);
    }

    public function getPollAnswers($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS_ANSWERS']))
            return false;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sWhereClause = $sOrderByClause = "";

        $sSelectClause = "*";
        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['id']
                );

                $sWhereClause .= " AND `id`=:id";
                break;

            case 'poll_id_pairs':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'title';
                $aMethod['params'][3] = array(
                    'poll_id' => $aParams['poll_id']
                );

                $sWhereClause .= " AND `poll_id`=:poll_id";
                break;

            case 'poll_id':
                $aMethod['params'][1] = array(
                    'poll_id' => $aParams['poll_id']
                );

                $sWhereClause .= " AND `poll_id`=:poll_id";
                break;

            case 'poll_id_max_order':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                    'poll_id' => $aParams['poll_id']
                );

                $sSelectClause = "MAX(`order`)";
                $sWhereClause .= " AND `poll_id`=:poll_id";
                break;

            case 'all':
            	break;
        }

        $sOrderByClause = " ORDER BY " . (isset($aParams['order_by']) ? '`' . $aParams['order_by'] . '`' : '`order`');
        $sOrderByClause .= " " . (isset($aParams['order_way']) ? strtoupper($aParams['order_way']) : 'ASC');

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_POLLS_ANSWERS'] . "` WHERE 1 " . $sWhereClause . $sOrderByClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertPollAnswer($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS_ANSWERS']) || empty($aParamsSet))
            return false;

        return $this->query("INSERT INTO `" . $CNF['TABLE_POLLS_ANSWERS'] . "` SET " . $this->arrayToSQL($aParamsSet));
    }

    public function updatePollAnswers($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS_ANSWERS']) || empty($aParamsSet) || empty($aParamsWhere))
            return false;

        return $this->query("UPDATE `" . $CNF['TABLE_POLLS_ANSWERS'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND "));
    }

    public function deletePollAnswers($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS_ANSWERS']))
            return false;

        $sWhereClause = $this->arrayToSQL($aParams, " AND ");
        $aAnswers = $this->getAll("SELECT * FROM `" . $CNF['TABLE_POLLS_ANSWERS'] . "` WHERE " . $sWhereClause);
        if(empty($aAnswers) || !is_array($aAnswers))
            return true;

        return $this->_deletePollAnswers($aAnswers);
    }

    public function deletePollAnswersByIds($mixedId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['TABLE_POLLS_ANSWERS']))
            return false;

        $sWhereClause = "`id` IN (" . $this->implode_escape(is_array($mixedId) ? $mixedId : array($mixedId)) . ")";
        $aAnswers = $this->getAll("SELECT * FROM `" . $CNF['TABLE_POLLS_ANSWERS'] . "` WHERE " . $sWhereClause);
        if(empty($aAnswers) || !is_array($aAnswers))
            return true;

        return $this->_deletePollAnswers($aAnswers);
    }

    protected function _deletePolls(&$aPolls)
    {
        $CNF = &$this->_oConfig->CNF;

        $aAffected = array();           
        foreach($aPolls as $aPoll) {
            if(!$this->deletePollAnswers(array('poll_id' => $aPoll[$CNF['FIELD_POLL_ID']])))
                continue;

            $aAffected[] = $aPoll[$CNF['FIELD_POLL_ID']];
        }

        return $this->query("DELETE FROM `" . $CNF['TABLE_POLLS'] . "` WHERE `id` IN (" . $this->implode_escape($aAffected) . ")");
    }

    protected function _deletePollAnswers(&$aAnswers)
    {
        $CNF = &$this->_oConfig->CNF;

        $aAffected = array();
        foreach($aAnswers as $aAnswer) {
            BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_POLL_ANSWERS'], $aAnswer['id'])->onObjectDelete();

            $aAffected[] = $aAnswer['id'];
        }

        return $this->query("DELETE FROM `" . $CNF['TABLE_POLLS_ANSWERS'] . "` WHERE `id` IN (" . $this->implode_escape($aAffected) . ")");
    }
    
    //--- Link attach related methods ---//
    public function getUnusedLinks($iUserId)
    {
        return $this->getLinksBy(array(
            'type' => 'unused',
            'profile_id' => $iUserId
        ));
    }

    public function deleteUnusedLinks($iUserId, $iLinkId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        
    	$aBindings = array(
    		'profile_id' => $iUserId
    	);

        $sWhereAddon = '';
        if(!empty($iLinkId)) {
        	$aBindings['id'] = $iLinkId;

            $sWhereAddon = " AND `id`=:id";
        }

        return $this->query("DELETE FROM `" . $CNF['TABLE_LINKS'] . "` WHERE `profile_id`=:profile_id" . $sWhereAddon, $aBindings);
    }

    public function saveLink($iContentId, $iLinkId)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $aBindings = array(
            'content_id' => $iContentId,
            'link_id' => $iLinkId
        );

        $iId = $this->getOne("SELECT `id` FROM `" . $CNF['TABLE_LINKS2CONTENT'] . "` WHERE `content_id`=:content_id AND `link_id`=:link_id LIMIT 1", $aBindings);
        if(!empty($iId))
            return true;

        return (int)$this->query("INSERT INTO `" . $CNF['TABLE_LINKS2CONTENT'] . "` SET `content_id`=:content_id, `link_id`=:link_id", $aBindings) > 0;
    }

    public function deleteLink($iId)
    {
        $CNF = &$this->_oConfig->CNF;
        
        return (int)$this->query("DELETE FROM `tl`, `tle` USING `" . $CNF['TABLE_LINKS'] . "` AS `tl` LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tle` ON `tl`.`id`=`tle`.`link_id` WHERE `tl`.`id` = :id", array(
            'id' => $iId
        )) > 0;
    }

    public function deleteLinks($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        
        return (int)$this->query("DELETE FROM `tl`, `tle` USING `" . $CNF['TABLE_LINKS'] . "` AS `tl` LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tle` ON `tl`.`id`=`tle`.`link_id` WHERE `tle`.`content_id` = :content_id", array(
            'content_id' => $iContentId
        )) > 0;
    }

    public function getLinks($iContentId)
    {
        return $this->getLinksBy(array('type' => 'content_id', 'content_id' => $iContentId));
    }

    public function getLinksBy($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sSelectClause = "`tl`.*";
    	$sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tl`.`id`=:id";

                if(!empty($aParams['profile_id'])) {
                    $aMethod['params'][1]['profile_id'] = $aParams['profile_id'];

                    $sWhereClause .= " AND `tl`.`profile_id`=:profile_id";
                }
                break;

            case 'content_id':
            	$aMethod['params'][1] = array(
                    'content_id' => $aParams['content_id']
                );

                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tle` ON `tl`.`id`=`tle`.`link_id`";
                $sWhereClause = " AND `tle`.`content_id`=:content_id";
                break;

            case 'unused':
                $aBindings = array(
                    'profile_id' => $aParams['profile_id']
                );

                if(isset($aParams['short']) && $aParams['short'] === true) {
                    $aMethod['name'] = 'getPairs';
                    $aMethod['params'][1] = 'url';
                    $aMethod['params'][2] = 'id';
                    $aMethod['params'][3] = $aBindings;
                }
                else
                    $aMethod['params'][1] = $aBindings;

                $sJoinClause = "LEFT JOIN `" . $CNF['TABLE_LINKS2CONTENT'] . "` AS `tle` ON `tl`.`id`=`tle`.`link_id`";
                $sWhereClause = " AND `tl`.`profile_id`=:profile_id AND ISNULL(`tle`.`content_id`)";
                $sOrderClause = "`tl`.`added` DESC";
                break;
        }

        $sOrderClause = !empty($sOrderClause) ? "ORDER BY " . $sOrderClause : $sOrderClause;
        $sLimitClause = !empty($sLimitClause) ? "LIMIT " . $sLimitClause : $sLimitClause;

        $aMethod['params'][0] = "SELECT
                " . $sSelectClause . "
            FROM `" . $CNF['TABLE_LINKS'] . "` AS `tl` " . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
    
    protected function _getEntriesBySearchIds($aParams, &$aMethod, &$sSelectClause, &$sJoinClause, &$sWhereClause, &$sOrderClause, &$sLimitClause)
    {
        $CNF = &$this->_oConfig->CNF;

        if($CNF['FIELD_STATUS'])
            $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_STATUS'] . "`='active'";
        
        if($CNF['FIELD_STATUS_ADMIN'])
            $sWhereClause .= " AND `" . $CNF['TABLE_ENTRIES'] . "`.`" . $CNF['FIELD_STATUS_ADMIN'] . "`='active'";
        
        parent::_getEntriesBySearchIds($aParams, $aMethod, $sSelectClause, $sJoinClause, $sWhereClause, $sOrderClause, $sLimitClause);        
    }
}

/** @} */
