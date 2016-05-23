<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ACL ACL
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAclDb extends BxDolModuleDb
{
    protected $_oConfig;

    /*
     * Constructor.
     */
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);

        $this->_oConfig = $oConfig;
    }

	public function getLevels($aParams, $bReturnCount = false)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tal`.`Order` ASC";

        switch($aParams['type']) {
        	case 'by_id':
        		$aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause .= "AND `tal`.`id`=:id";
        		break;

            case 'for_selector':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'name';
                $sWhereClause .= "AND `tal`.`Active`='yes' AND (`tal`.`Purchasable`='yes' OR `tal`.`Removable`='yes')";
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tal`.`ID` AS `id`,
                `tal`.`Name` AS `name`,
                `tal`.`Icon` AS `icon`,
                `tal`.`Description` AS `description`,
                `tal`.`Active` AS `active`,
                `tal`.`Purchasable` AS `purchasable`,
                `tal`.`Removable` AS `removable`,
                `tal`.`QuotaSize` AS `quota_size`,
                `tal`.`QuotaNumber` AS `quota_number`,
                `tal`.`QuotaMaxFileSize` AS `quota_max_file_size`,
                `tal`.`Order` AS `order`" . $sSelectClause . "
            FROM `sys_acl_levels` AS `tal` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return $aItems;

		return array('items' => $aItems, 'count' => (int)$this->getOne("SELECT FOUND_ROWS()"));
    }

	public function updateLevels($aSet, $aWhere)
    {
    	$sSql = "UPDATE `sys_acl_levels` SET " . $this->arrayToSQL($aSet, " AND ") . " WHERE " . $this->arrayToSQL($aWhere, " AND ");
        return (int)$this->query($sSql) > 0;
    }

	public function getPrices($aParams, $bReturnCount = false)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `tap`.`Order` ASC";

        switch($aParams['type']) {
            case 'by_id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sWhereClause .= "AND `tap`.`id`=:id";
                break;

			case 'by_id_full':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['value']
                );

                $sSelectClause .= ", `tal`.`Name` AS `level_name`, `tal`.`Description` AS `level_description`";
                $sJoinClause .= "LEFT JOIN `sys_acl_levels` AS `tal` ON `tap`.`level_id`=`tal`.`ID`";
                $sWhereClause .= "AND `tap`.`id`=:id";
                break;

            case 'by_level_id':
            	$aMethod['params'][1] = array(
                	'level_id' => $aParams['value']
                );

                $sWhereClause .= "AND `tap`.`level_id`=:level_id";
                break;

            case 'by_level_id_pair':
                $aMethod['name'] = "getPairs";
                $aMethod['params'][1] = 'days';
                $aMethod['params'][2] = 'price';
                $aMethod['params'][3] = array(
                	'level_id' => $aParams['value']
                );

                $sWhereClause .= "AND `tap`.`level_id`=:level_id";
                break;

            case 'by_level_id_duration':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'level_id' => $aParams['level_id'],
                	'days' => $aParams['days']
                );

                $sWhereClause .= "AND `tap`.`level_id`=:level_id AND `tap`.`days`=:days";
                break;

            case 'counter_by_levels':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'level_id';
                $aMethod['params'][2] = 'counter';

                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `tap`.`IDLevel`";
                break;

            case 'all_full':
                $sSelectClause .= ", `tal`.`Name` AS `level_name`, `tal`.`Description` AS `level_description`";
                $sJoinClause .= "LEFT JOIN `sys_acl_levels` AS `tal` ON `tap`.`level_id`=`tal`.`ID`";
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `tap`.`id` AS `id`,
                `tap`.`level_id` AS `level_id`,
                `tap`.`name` AS `name`,
                `tap`.`days` AS `days`,
                `tap`.`price` AS `price`,
                `tap`.`order` AS `order`" . $sSelectClause . "
            FROM `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` AS `tap` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return $aItems;

        return array('items' => $aItems, 'count' => (int)$this->getOne("SELECT FOUND_ROWS()"));
    }

    public function getPriceOrderMax($iLevelId)
    {
        $sSql = $this->prepare("SELECT MAX(`order`) FROM `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` WHERE `level_id`=?", $iLevelId);
        return (int)$this->getOne($sSql);
    }

    public function deletePrices($aWhere)
    {
    	$sSql = "DELETE FROM `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` WHERE " . $this->arrayToSQL($aWhere, " AND ");
        return (int)$this->query($sSql) > 0;
    }
}

/** @} */
