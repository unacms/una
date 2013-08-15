<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolDb');

/**
 * Database queries for Connection objects.
 * @see BxDolConnection
 */
class BxDolConnectionQuery extends BxDolDb {
    protected $_aObject;
    protected $_sTable;
    protected $_sType;

    public function __construct($aObject) {
        parent::__construct();
        $this->_aObject = $aObject;
        $this->_sTable = $aObject['table'];
        $this->_sType = $aObject['type'];
    }

    static public function getConnectionObject ($sObject) {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_connection` WHERE `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject)) 
            return false;
        
        return $aObject;
    }

    public function getConnectedContentSQLParts ($sContentTable, $sContentField, $iInitiator, $isMutual = false) {
        $sWhere = $this->prepare(" AND `" . $this->_sTable . "`.`initiator` = ?", $iInitiator);
        if (false !== $isMutual)
            $sWhere .= $this->prepare(" AND `mutual` = ?", $isMutual);
        return array(
            'join' => "INNER JOIN `{$this->_sTable}` ON (`{$this->_sTable}`.`content` = `$sContentTable`.`$sContentField` $sWhere)",
        );
    }

    public function getConnectedInitiatorsSQLParts ($sContentTable, $sContentField, $iInitiator, $isMutual = false) {
        $sWhere = $this->prepare(" AND `" . $this->_sTable . "`.`content` = ?", $iInitiator);
        if (false !== $isMutual)
            $sWhere .= $this->prepare(" AND `mutual` = ?", $isMutual);
        return array(
            'join' => "INNER JOIN `{$this->_sTable}` ON (`{$this->_sTable}`.`initiator` = `$sContentTable`.`$sContentField` $sWhere)",
        );
    }

    public function getConnectedContent ($iInitiator, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE) {
        $sWhere = $this->prepare(" AND `initiator` = ?", $iInitiator);
        $sQuery = $this->_getConnectionsQuery($sWhere, '`content`', $isMutual, $iStart, $iLimit, $iOrder);
        return $this->getColumn($sQuery);
    }

    public function getConnectedInitiators ($iContent, $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE) {
        $sWhere = $this->prepare(" AND `content` = ?", $iContent);
        $sQuery = $this->_getConnectionsQuery($sWhere, '`initiator`', $isMutual, $iStart, $iLimit, $iOrder);
        return $this->getColumn($sQuery);
    }

    protected function _getConnectionsQuery ($sWhere, $sFields = '*', $isMutual = false, $iStart = 0, $iLimit = BX_CONNECTIONS_LIST_LIMIT, $iOrder = BX_CONNECTIONS_ORDER_NONE) {
        $sOrder = '';
        switch ($iOrder) {
        case BX_CONNECTIONS_ORDER_ADDED_ASC: 
            $sOrder = 'ORDER BY `added` ASC';
            break;
        case BX_CONNECTIONS_ORDER_ADDED_DESC: 
            $sOrder = 'ORDER BY `added` DESC';
            break;
        }

        if (false !== $isMutual)
            $sWhere .= $this->prepare(" AND `mutual` = ?", $isMutual);

        return $this->prepare("SELECT $sFields FROM `" . $this->_sTable . "` WHERE 1 $sWhere $sOrder LIMIT ?, ?", $iStart, $iLimit);
    }

    public function getConnection ($iInitiator, $iContent) {
        $sQuery = $this->prepare("SELECT * FROM `" . $this->_sTable . "` WHERE `initiator` = ? AND `content` = ?", $iInitiator, $iContent);
        return $this->getRow($sQuery);
    }

    public function addConnection ($iInitiator, $iContent, &$iMutualParam = null) {
        if ($this->getConnection($iInitiator, $iContent)) // connection already exists
            return false;

        if (BX_CONNECTIONS_TYPE_MUTUAL == $this->_sType) {
            $aConnectionMutual = $this->getConnection($iContent, $iInitiator);
            $iMutual = $aConnectionMutual ? 1 : 0;
        }

        $sQuery = $this->prepare("INSERT INTO `" . $this->_sTable . "` SET `initiator` = ?, `content` = ?, `mutual` = ?, `added` = ?", $iInitiator, $iContent, $iMutual, time());
        if (!$this->query($sQuery))
            return false;

        if ($iMutual) // in case of mutual connection update 'mutual' field
            $this->updateConnectionMutual($iContent, $iInitiator, $iMutual);

        if (null !== $iMutualParam)
            $iMutualParam = $iMutual;

        return true;
    }

    public function updateConnectionMutual ($iInitiator, $iContent, $iMutual) {
        $sQuery = $this->prepare("UPDATE `" . $this->_sTable . "` SET `mutual` = ? WHERE `initiator` = ? AND `content` = ?", $iMutual, $iInitiator, $iContent);
        return $this->query($sQuery);
    }

    public function removeConnection ($iInitiator, $iContent) {
        if (!($aConnection = $this->getConnection($iInitiator, $iContent))) // connection doesn't exist
            return false;

        $sQuery = $this->prepare("DELETE FROM `" . $this->_sTable . "` WHERE `initiator` = ? AND `content` = ?", $iInitiator, $iContent);
        if (!$this->query($sQuery))
            return false;

        if (BX_CONNECTIONS_TYPE_MUTUAL == $this->_sType && $aConnection['mutual']) {
            if (!$this->removeConnection($iContent, $iInitiator)) // in case of mutual connection - remove both connections
                return false;
        }

        return true;
    }

}

/** @} */
