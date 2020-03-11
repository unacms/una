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
