<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for BxDolQueue object.
 * @see BxDolQueue
 */
class BxDolQueueQuery extends BxDolDb
{
    protected $_sTable;

    public function __construct()
    {
        parent::__construct();
    }

    public function getItems($aParams = array())
    {
        if(empty($this->_sTable))
            return array();

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sSelectClause = '*';
    	$sWhereClause = '';
    	$sLimitClause = isset($aParams['start']) && !empty($aParams['per_page']) ? "LIMIT " . $aParams['start'] . ", " . $aParams['per_page'] : "";

    	if(!empty($aParams['type']))
            switch($aParams['type']) {
                case 'to_send':
                    $aMethod['name'] = 'getAllWithKey';
                    $aMethod['params'][1] = 0;
                    $aMethod['params'][2] = array();
                    $aMethod['params'][3] = PDO::FETCH_NUM;
                    break;
            }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $this->_sTable . "` WHERE 1 " . $sWhereClause . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertItem($aValues)
    {
        if(empty($this->_sTable))
            return false;

        if(empty($aValues) || !is_array($aValues))
            return false;

        return $this->query("INSERT INTO `" . $this->_sTable . "` SET " . $this->arrayToSQL($aValues));
    }

	public function updateItem($aValues, $aWhere)
    {
        if(empty($this->_sTable))
            return false;

        if(empty($aValues) || !is_array($aValues) || empty($aWhere) || !is_array($aWhere))
            return false;

        return (int)$this->query("UPDATE `" . $this->_sTable . "` SET " . $this->arrayToSQL($aValues) . " WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }

    public function deleteItem($mixedId)
    {
        if(empty($this->_sTable))
            return false;

    	if(!is_array($mixedId))
    		$mixedId = array($mixedId);

        return (int)$this->query("DELETE FROM `" . $this->_sTable . "` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")") > 0;
    }
}

/** @} */
