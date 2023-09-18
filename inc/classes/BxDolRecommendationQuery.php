<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolRecommendationQuery extends BxDolDb
{
    public static $sTableObjects = 'sys_objects_recommendation';
    public static $sTableCriteria = 'sys_recommendation_criteria';
    public static $sTableData = 'sys_recommendation_data';
            
    protected $_aObject;

    public function __construct()
    {
        parent::__construct();

        $this->_aObject = [];
    }

    static public function getObjects($bActiveOnly = true)
    {
        $sWhereClause = "";
        if($bActiveOnly)
            $sWhereClause = " AND `active` = 1";

        return BxDolDb::getInstance()->getAllWithKey("SELECT * FROM `" . self::$sTableObjects . "` WHERE 1" . $sWhereClause, 'name');
    }

    static public function getObject($sName)
    {
        $oDb = BxDolDb::getInstance();

        $aObject = $oDb->getRow("SELECT * FROM `" . self::$sTableObjects . "` WHERE `name` = :name", ['name' => $sName]);
        if(!$aObject || !is_array($aObject))
            return false;
        
        $aCriteria = $oDb->getAllWithKey("SELECT * FROM `" . self::$sTableCriteria . "` WHERE `object_id` = :object_id AND `weight` > 0 AND `active` = 1 ORDER BY `weight` DESC", 'name', ['object_id' => $aObject['id']]);
        if(!$aCriteria || !is_array($aCriteria))
            return false;

        $aObject['weights'] = $oDb->getPairs("SELECT `name`, `weight` FROM `" . self::$sTableCriteria . "` WHERE `object_id` = :object_id AND `weight` > 0 AND `active` = 1 ORDER BY `weight` DESC", 'name', 'weight', ['object_id' => $aObject['id']]);

        return [
            'object' => $aObject,
            'criteria' => $aCriteria
        ];
    }

    public function init($aObject = [])
    {
        if(empty($aObject) || !is_array($aObject)) 
            return;

        $this->_aObject = $aObject;
    }

    public function clean($iProfileId, $iObjectId, $bAll = false)
    {
        $sWhereClause = "";
        if(!$bAll)
            $sWhereClause = " AND `item_reducer` = 0";

        return $this->query("DELETE FROM `" . self::$sTableData . "` WHERE `profile_id` = :profile_id AND `object_id` = :object_id" . $sWhereClause, [
            'profile_id' => $iProfileId,
            'object_id' => $iObjectId
        ]);
    }

    public function add($iProfileId, $iObjectId, $iItemId, $sItemType, $iItemValue)
    {
        return $this->query("INSERT INTO `" . self::$sTableData . "` (`profile_id`, `object_id`, `item_id`, `item_type`, `item_value`) VALUES (:profile_id, :object_id, :item_id, :item_type, :item_value) ON DUPLICATE KEY UPDATE `item_value` = :item_value", [
            'profile_id' => $iProfileId,
            'object_id' => $iObjectId,
            'item_id' => $iItemId, 
            'item_type' => $sItemType,
            'item_value' => $iItemValue
        ]) !== false;
    }
    
    public function update($iProfileId, $iObjectId, $iItemId, $aSet)
    {
        if(empty($aSet) || !is_array($aSet))
            return false;

        return $this->query("UPDATE `" . self::$sTableData . "` SET " . $this->arrayToSQL($aSet) . " WHERE `profile_id` = :profile_id AND `object_id` = :object_id AND `item_id` = :item_id ", [
            'profile_id' => $iProfileId,
            'object_id' => $iObjectId,
            'item_id' => $iItemId
        ]) !== false;
    }

    public function delete($iProfileId, $iObjectId, $iItemId)
    {
        return $this->query("DELETE FROM `" . self::$sTableData . "` WHERE `profile_id` = :profile_id AND `object_id` = :object_id AND `item_id` = :item_id ", [
            'profile_id' => $iProfileId,
            'object_id' => $iObjectId,
            'item_id' => $iItemId
        ]) !== false;
    }

    public function get($iProfileId, $iObjectId, $aParams = [])
    {
        $sWhereClause = "";
        if(!empty($aParams['type']))
            $sWhereClause = " AND `item_type` IN (" . $this->implode_escape(is_array($aParams['type']) ? $aParams['type'] : [$aParams['type']]) . ")";

        $sLimitClause = "";
        if(isset($aParams['start']) && !empty($aParams['per_page']))
            $sLimitClause = " LIMIT " . $aParams['start'] . ", " . $aParams['per_page'];

        return $this->getPairs("SELECT `item_id` AS `id`, (`item_value` - `item_reducer`) AS `value` FROM `" . self::$sTableData . "` WHERE `profile_id` = :profile_id AND `object_id` = :object_id AND (`item_value` - `item_reducer`) >= 0" . $sWhereClause . " ORDER BY `value` DESC" . $sLimitClause, 'id', 'value', [
            'profile_id' => $iProfileId,
            'object_id' => $iObjectId
        ]);
    }
    
    public function getBy($aParams)
    {
        $aMethod = ['name' => 'getAll', 'params' => [0 => 'query']];

    	$sFieldsClause = "*"; 
    	$sJoinClause = $sWhereClause = $sGroupClause = $sLimitClause = $sOrderClause = "";

    	switch($aParams['type']) {              
            case 'profile_object_ids':
                $aMethod['params'][1] = [
                    'profile_id' => $aParams['profile_id'],
                    'object_id' => $aParams['object_id']
                ];

                $sWhereClause = " AND `profile_id`=:profile_id AND `object_id`=:object_id";
                break;

            case 'all':
                break;
    	}

        $sOrderClause = $sOrderClause ? "ORDER BY " . $sOrderClause : "";
        $sLimitClause = $sLimitClause ? "LIMIT " . $sLimitClause : "";

        $aMethod['params'][0] = "SELECT
            " . $sFieldsClause . "
            FROM `" . self::$sTableData . "` " . $sJoinClause . "
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;

        return call_user_func_array([$this, $aMethod['name']], $aMethod['params']);
    }

    public function getItem($iProfileId, $iObjectId, $iItemId)
    {
        return $this->getRow("SELECT * FROM `" . self::$sTableData . "` WHERE `profile_id` = :profile_id AND `object_id` = :object_id AND `item_id` = :item_id", [
            'profile_id' => $iProfileId,
            'object_id' => $iObjectId,
            'item_id' => $iItemId
        ]);
    }
}

/** @} */
