<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Database queries for Connection objects.
 * @see BxDolConnection
 */
class BxDolConnectionQuery extends BxDolDb
{
    protected $_aObject;
    protected $_sTable;
    protected $_sType;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
        $this->_sTable = $aObject['table'];
        $this->_sType = $aObject['type'];
    }

    static public function getConnectionObject ($sObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_connection` WHERE `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    public function getCommonContentSQLParts ($sContentTable, $sContentField, $iInitiator1, $iInitiator2, $isMutual = false)
    {
        $sWhereJoin1 = $this->prepareAsString(" AND `c`.`initiator` = ?", $iInitiator1);
        $sWhereJoin2 = $this->prepareAsString(" AND `c2`.`initiator` = ?", $iInitiator2);
        if (false !== $isMutual) {
            $sWhereJoin1 .= $this->prepareAsString(" AND `c`.`mutual` = ?", $isMutual);
            $sWhereJoin2 .= $this->prepareAsString(" AND `c2`.`mutual` = ?", $isMutual);
        }
        return array(
            'join' => "
                INNER JOIN `{$this->_sTable}` AS `c` ON (`c`.`content` = `$sContentTable`.`$sContentField` $sWhereJoin1)
                INNER JOIN `{$this->_sTable}` AS `c2` ON (`c2`.`content` = `c`.`content` $sWhereJoin2)",
        );
    }

    public function getConnectedContentSQLParts ($sContentTable, $sContentField, $iInitiator, $isMutual = false)
    {
        $sWhere = $this->prepareAsString(" AND `c`.`initiator` = ?", $iInitiator);
        if (false !== $isMutual)
            $sWhere .= $this->prepareAsString(" AND `c`.`mutual` = ?", $isMutual);
        return array(
            'join' => "INNER JOIN `{$this->_sTable}` AS `c` ON (`c`.`content` = `$sContentTable`.`$sContentField` $sWhere)",
        );
    }

    public function getConnectedInitiatorsSQLParts ($sContentTable, $sContentField, $iInitiator, $isMutual = false)
    {
        $sWhere = $this->prepareAsString(" AND `c`.`content` = ?", $iInitiator);
        if (false !== $isMutual)
            $sWhere .= $this->prepareAsString(" AND `c`.`mutual` = ?", $isMutual);
        return array(
            'join' => "INNER JOIN `{$this->_sTable}` AS `c` ON (`c`.`initiator` = `$sContentTable`.`$sContentField` $sWhere)",
        );
    }

    public function getCommonContent($iInitiator1, $iInitiator2, $isMutual, $iStart, $iLimit, $iOrder)
    {
        $sWhereJoin = (false !== $isMutual) ? " AND `c2`.`mutual` = :mutual" : "";
        $sJoin = "INNER JOIN `" . $this->_sTable . "` AS `c2` ON (`c2`.`initiator` = :initiator2 AND `c`.`content` = `c2`.`content` $sWhereJoin)";

        $sWhere = " AND `c`.`initiator` = :initiator1";
        $sQuery = $this->_getConnectionsQuery($sWhere, $sJoin, '`c`.`content`', $isMutual, $iStart, $iLimit, $iOrder);

        return $this->getColumn($sQuery, array(
    		'mutual' => $isMutual,
    		'initiator1' => $iInitiator1,
    		'initiator2' => $iInitiator2,  
    	));
    }

    public function getConnectedContent ($iInitiator, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        $sWhere = " AND `c`.`initiator` = :initiator";
        $sQuery = $this->_getConnectionsQuery($sWhere, '', '`c`.`content`', $isMutual, $iStart, $iLimit, $iOrder);

        return $this->getColumn($sQuery, array(
        	'initiator' => $iInitiator
        ));
    }

    public function getConnectedInitiators ($iContent, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        $sWhere = " AND `c`.`content` = :content";
        $sQuery = $this->_getConnectionsQuery($sWhere, '', '`c`.`initiator`', $isMutual, $iStart, $iLimit, $iOrder);

        return $this->getColumn($sQuery, array(
        	'content' => $iContent
        ));
    }

    protected function _getConnectionsQuery ($sWhere, $sJoin = '', $sFields = '*', $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE)
    {
        $sOrder = $this->_getOrderClause($iOrder);

        $sWhere .= (false !== $isMutual) ? $this->prepareAsString(" AND `c`.`mutual` = ?", $isMutual) : '';

        return $this->prepareAsString("SELECT $sFields FROM `" . $this->_sTable . "` AS `c` $sJoin WHERE 1 $sWhere $sOrder LIMIT ?, ?", $iStart, $iLimit);
    }

    public function getConnectedContentCount ($iInitiator, $isMutual = false)
    {
        $sWhere = $this->prepareAsString(" AND `c`.`initiator` = ?", $iInitiator);
        $sQuery = $this->_getConnectionsQueryCount($sWhere, '', $isMutual);
        return $this->getOne($sQuery);
    }

    public function getConnectedInitiatorsCount ($iContent, $isMutual = false)
    {
        $sWhere = $this->prepareAsString(" AND `c`.`content` = ?", $iContent);
        $sQuery = $this->_getConnectionsQueryCount($sWhere, '', $isMutual);
        return $this->getOne($sQuery);
    }

    protected function _getConnectionsQueryCount ($sWhere, $sJoin = '', $isMutual = false)
    {
        $sWhere .= (false !== $isMutual) ? $this->prepareAsString(" AND `c`.`mutual` = ?", $isMutual) : '';
        return "SELECT COUNT(`id`) FROM `" . $this->_sTable . "` AS `c` $sJoin WHERE 1 $sWhere";
    }

    protected function _getOrderClause ($iOrder = BX_CONNECTIONS_ORDER_NONE, $sTable = '')
    {
        if ($sTable)
            $sTable .= '.';
        $sOrder = '';
        switch ($iOrder) {
        case BX_CONNECTIONS_ORDER_ADDED_ASC:
            $sOrder = "ORDER BY {$sTable}`added` ASC";
            break;
        case BX_CONNECTIONS_ORDER_ADDED_DESC:
            $sOrder = "ORDER BY {$sTable}`added` DESC";
            break;
        }
        return $sOrder;
    }

    public function getConnection ($iInitiator, $iContent)
    {
        $sQuery = $this->prepare("SELECT * FROM `" . $this->_sTable . "` WHERE `initiator` = ? AND `content` = ?", $iInitiator, $iContent);
        return $this->getRow($sQuery);
    }

    public function addConnection ($iInitiator, $iContent, &$iMutualParam = null)
    {
        if ($this->getConnection($iInitiator, $iContent)) // connection already exists
            return false;

		$aBindings = array();

        $iMutual = 0;
        $sMutualField = '';
        if (BX_CONNECTIONS_TYPE_MUTUAL == $this->_sType) {
            $aConnectionMutual = $this->getConnection($iContent, $iInitiator);
            $iMutual = $aConnectionMutual ? 1 : 0;

            $sMutualField = ", `mutual` = :mutual";
            $aBindings['mutual'] = $iMutual;
        }

        $sQuery = $this->prepare("INSERT INTO `" . $this->_sTable . "` SET `initiator` = :initiator, `content` = :content, `added` = :added" . $sMutualField);
        if (!$this->query($sQuery, array_merge($aBindings, array('initiator' => $iInitiator, 'content' => $iContent, 'added' => time()))))
            return false;

        if ($iMutual) // in case of mutual connection update 'mutual' field
            $this->updateConnectionMutual($iContent, $iInitiator, $iMutual);

        if (null !== $iMutualParam)
            $iMutualParam = $iMutual;

        return true;
    }

    public function updateConnectionMutual ($iInitiator, $iContent, $iMutual)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->_sTable . "` SET `mutual` = ? WHERE `initiator` = ? AND `content` = ?", $iMutual, $iInitiator, $iContent);
        return $this->query($sQuery);
    }

    public function removeConnection ($iInitiator, $iContent)
    {
        if (!($aConnection = $this->getConnection($iInitiator, $iContent))) // connection doesn't exist
            return true;

        $sQuery = $this->prepare("DELETE FROM `" . $this->_sTable . "` WHERE `initiator` = ? AND `content` = ?", $iInitiator, $iContent);
        if (!$this->res($sQuery))
            return false;

        if (BX_CONNECTIONS_TYPE_MUTUAL == $this->_sType && $aConnection['mutual'])
            $this->removeConnection($iContent, $iInitiator);

        return true;
    }

    public function onDelete ($iId, $sField = 'initiator')
    {
        $sQuery = $this->prepare("DELETE FROM `{$this->_sTable}` WHERE `$sField` = ?", $iId);
        return $this->query($sQuery);
    }

    public function onModuleDelete ($sTable, $sFieldId, $sField = 'initiator')
    {
        return $this->onModuleDeleteCustom ($sTable, $sFieldId, $sField);
    }

    public function onModuleProfileDelete ($sModuleName, $sField = 'initiator')
    {
        return $this->onModuleDeleteCustom ('sys_profiles', 'id', $sField, $this->prepareAsString(" AND `sys_profiles`.`type` = ? ", $sModuleName));
    }

    protected function onModuleDeleteCustom ($sTable, $sFieldId, $sField = 'initiator', $sWhere = '')
    {
        $sQuery = $this->prepare("DELETE `" . $this->_sTable . "` FROM `" . $this->_sTable . "` INNER JOIN `{$sTable}` WHERE `" . $this->_sTable . "`.`$sField` = `{$sTable}`.`{$sFieldId}` " . $sWhere);
        return $this->query($sQuery);
    }

}

/** @} */
