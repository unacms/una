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

            case 'all':
            	break;
        }

        $sOrderByClause = " ORDER BY " . (isset($aParams['order_by']) ? $aParams['order_by'] : '`title`');

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
}

/** @} */
