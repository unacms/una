<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxPollsDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getContentInfoById ($iContentId)
    {
        $aContentInfo = parent::getContentInfoById($iContentId);
        if(!empty($aContentInfo) && is_array($aContentInfo))
            $aContentInfo['salt'] = $this->_oConfig->getSalt();

        return $aContentInfo;
    }

    public function isPerformed($iEntryId, $iAuthorId, $iAuthorIp)
    {
        $CNF = &$this->_oConfig->CNF;

        $iAuthorId = (int)$iAuthorId;

        $aBindings = array('author_id' => $iAuthorId);
        $sWhereClause = "AND `author_id`=:author_id";

        if(empty($iAuthorId)) {
            $aBindings['author_nip'] = $iAuthorIp;
            $sWhereClause .= " AND `author_nip`=:author_nip";
        }

        $aSubentries = $this->getSubentries(array('type' => 'entry_id_pairs', 'entry_id' => $iEntryId));
        return (int)$this->getOne("SELECT `object_id` FROM `" . $CNF['TABLE_VOTES_SUBENTRIES_TRACK'] . "` WHERE `object_id` IN (" . $this->implode_escape(array_keys($aSubentries)) . ") " . $sWhereClause . " LIMIT 1", $aBindings) != 0;
    }
    
    public function getPerformedValue($iEntryId, $iAuthorId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aBindings = ['author_id' => $iAuthorId];
        $sWhereClause = "AND `author_id`=:author_id";

        $aSubentries = $this->getSubentries([
            'type' => 'entry_id_pairs', 
            'entry_id' => $iEntryId
        ]);

        return (int)$this->getOne("SELECT `object_id` FROM `" . $CNF['TABLE_VOTES_SUBENTRIES_TRACK'] . "` WHERE `object_id` IN (" . $this->implode_escape(array_keys($aSubentries)) . ") " . $sWhereClause . " LIMIT 1", $aBindings);
    }

    public function getContentInfoBySubentryId ($iSubentryId)
    {
        $CNF = &$this->_oConfig->CNF;

        $sQuery = $this->prepare ("SELECT `te`.* FROM `" . $CNF['TABLE_ENTRIES'] . "` AS `te` LEFT JOIN `" . $CNF['TABLE_SUBENTRIES'] . "` AS `tse` ON `te`.`" . $CNF['FIELD_ID'] . "`=`tse`.`entry_id` WHERE `tse`.`id` = ?", $iSubentryId);
        return $this->getRow($sQuery);
    }

    public function getSubentries($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

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

            case 'entry_id_pairs':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'title';
                $aMethod['params'][3] = array(
                	'entry_id' => $aParams['entry_id']
                );

                $sWhereClause .= " AND `entry_id`=:entry_id";
                break;

            case 'entry_id':
                $aMethod['params'][1] = array(
                	'entry_id' => $aParams['entry_id']
                );

                $sWhereClause .= " AND `entry_id`=:entry_id";
                break;

            case 'entry_id_max_order':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                	'entry_id' => $aParams['entry_id']
                );

                $sSelectClause = "MAX(`order`)";
                $sWhereClause .= " AND `entry_id`=:entry_id";
                break;

            case 'all':
            	break;
        }

        $sOrderByClause = " ORDER BY " . (isset($aParams['order_by']) ? '`' . $aParams['order_by'] . '`' : '`order`');
        $sOrderByClause .= " " . (isset($aParams['order_way']) ? strtoupper($aParams['order_way']) : 'ASC');

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_SUBENTRIES'] . "` WHERE 1 " . $sWhereClause . $sOrderByClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertSubentry($aParamsSet)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet))
            return false;

        $sSql = "INSERT INTO `" . $CNF['TABLE_SUBENTRIES'] . "` SET " . $this->arrayToSQL($aParamsSet);
        return $this->query($sSql);
    }

    public function updateSubentry($aParamsSet, $aParamsWhere)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParamsSet) || empty($aParamsWhere))
            return false;

        $sSql = "UPDATE `" . $CNF['TABLE_SUBENTRIES'] . "` SET " . $this->arrayToSQL($aParamsSet) . " WHERE " . $this->arrayToSQL($aParamsWhere, " AND ");
        return $this->query($sSql);
    }

    public function deleteSubentry($aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        $sSql = "DELETE FROM `" . $CNF['TABLE_SUBENTRIES'] . "` WHERE " . $this->arrayToSQL($aParams, " AND ");
        return $this->query($sSql);
    }

    public function deleteSubentryById($mixedId)
    {
        $CNF = &$this->_oConfig->CNF;

        if(!is_array($mixedId))
            $mixedId = array($mixedId);

        $sSql = "DELETE FROM `" . $CNF['TABLE_SUBENTRIES'] . "` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")";
        return $this->query($sSql);
    }
}

/** @} */
