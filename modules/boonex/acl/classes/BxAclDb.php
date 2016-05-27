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

        $sSelectClause = "
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
			`tal`.`Order` AS `order`";

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

        $sSql = "SELECT {select} FROM `sys_acl_levels` AS `tal` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " {order} {limit}";

        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array($sSelectClause, $sOrderClause, $sLimitClause), $sSql);
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return $aItems;

		$aMethod['name'] = 'getOne';
		$aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array("COUNT(*)", "", ""), $sSql);

		return array(
			'items' => $aItems, 
			'count' => (int)call_user_func_array(array($this, $aMethod['name']), $aMethod['params'])
		);
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

        $sSelectClause = "
			`tap`.`id` AS `id`,
			`tap`.`level_id` AS `level_id`,
			`tap`.`name` AS `name`,
			`tap`.`period` AS `period`,
			`tap`.`period_unit` AS `period_unit`,
			`tap`.`price` AS `price`,
			`tap`.`order` AS `order`";

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

            case 'by_level_id_duration':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'level_id' => $aParams['level_id'],
                	'period' => $aParams['period'],
                	'period_unit' => $aParams['period_unit'],
                );

                $sWhereClause .= "AND `tap`.`level_id`=:level_id AND `tap`.`period`=:period AND `tap`.`period_unit`=:period_unit";
                break;

            case 'all_full':
                $sSelectClause .= ", `tal`.`Name` AS `level_name`, `tal`.`Description` AS `level_description`";
                $sJoinClause .= "LEFT JOIN `sys_acl_levels` AS `tal` ON `tap`.`level_id`=`tal`.`ID`";
                break;
        }

        $sSql = "SELECT {select} FROM `" . $this->_oConfig->CNF['TABLE_PRICES'] . "` AS `tap` " . $sJoinClause . " WHERE 1 " . $sWhereClause . " {order} {limit}";

        $aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array($sSelectClause, $sOrderClause, $sLimitClause), $sSql);
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return $aItems;

		$aMethod['name'] = 'getOne';
		$aMethod['params'][0] = str_replace(array('{select}', '{order}', '{limit}'), array("COUNT(*)", "", ""), $sSql);

        return array(
        	'items' => $aItems, 
        	'count' => (int)call_user_func_array(array($this, $aMethod['name']), $aMethod['params'])
        );
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
