<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Database queries for ACL
 * @see BxDolAcl
 */
class BxDolAclQuery extends BxDolDb implements iBxDolSingleton
{
    function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolAclQuery();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function getLevels($aParams, &$aItems, $bReturnCount = true)
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

                $sWhereClause .= "AND `tal`.`ID`=:id";
                $sLimitClause .= "LIMIT 1";
                break;

            case 'all_active':
                $sWhereClause .= "AND `tal`.`Active`='yes'";
                break;

            case 'all_active_purchasble_pair':
                $aMethod['name'] = "getPairs";
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'name';
                $sWhereClause .= "AND `tal`.`Active`='yes' AND `tal`.`Purchasable`='yes'";
                break;

            case 'all_active_pair':
                $aMethod['name'] = "getPairs";
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'name';
                $sWhereClause .= "AND `tal`.`Active`='yes'";
                break;

            case 'all_pair':
                $aMethod['name'] = "getPairs";
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = 'name';
                break;

            case 'all_order_id':
                $sOrderClause = "ORDER BY `tal`.`ID` ASC";
                break;

            case 'all':
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
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    function getActions($aParams, &$aItems, $bReturnCount = true)
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sSelectClause = $sJoinClause = $sWhereClause = $sGroupClause = $sOrderClause = $sLimitClause = "";

        if(!isset($aParams['order']) || empty($aParams['order']))
           $sOrderClause = "ORDER BY `taa`.`Title` ASC";

           
        switch($aParams['type']) {
            case 'by_names_and_module':
            	$aMethod['params'][1] = array(
                	'module' => $aParams['module']
                );

                $sWhereClause .= " AND `taa`.`Name` IN(" . $this->implode_escape($aParams['value']) . ") AND `taa`.`Module` = :module ";
                break;

            case 'by_names':
                $sWhereClause .= " AND `taa`.`Name` IN(" . $this->implode_escape($aParams['value']) . ")";
                break;

            case 'by_level_id':
            	$aMethod['params'][1] = array(
                	'level_id' => $aParams['value'],
                	'level_code' => pow(2, ($aParams['value'] - 1))
                );

                $sSelectClause .= ", `tam`.`AllowedCount` AS `allowed_count`, `tam`.`AllowedPeriodLen` AS `allowed_period_len` ";
                $sJoinClause .= "LEFT JOIN `sys_acl_matrix` AS `tam` ON `taa`.`ID`=`tam`.`IDAction` ";
                $sWhereClause .= "AND `tam`.`IDLevel`=:level_id AND (`taa`.`DisabledForLevels`='0' OR `taa`.`DisabledForLevels`&:level_code=0)";
                break;

            case 'by_level_id_key_id':
                $aMethod['name'] = 'getAllWithKey';
                $aMethod['params'][1] = 'id';
                $aMethod['params'][2] = array(
                	'level_id' => $aParams['value']
                );

                $sSelectClause .= ", `tam`.`AllowedCount` AS `allowed_count`, `tam`.`AllowedPeriodLen` AS `allowed_period_len` ";
                $sJoinClause .= "LEFT JOIN `sys_acl_matrix` AS `tam` ON `taa`.`ID`=`tam`.`IDAction` ";
                $sWhereClause .= "AND `tam`.`IDLevel`=:level_id";
                break;

            case 'counter_by_modules':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'module';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", COUNT(*) AS `counter`";
                $sGroupClause = "GROUP BY `taa`.`Module`";
                break;

            case 'counter_by_levels':
                $aMethod['name'] = 'getPairs';
                $aMethod['params'][1] = 'level_id';
                $aMethod['params'][2] = 'counter';
                $sSelectClause = ", `tam`.`IDLevel` AS `level_id`, COUNT(`tam`.`IDAction`) AS `counter`";
                $sJoinClause = "LEFT JOIN `sys_acl_matrix` AS `tam` ON `taa`.`ID`=`tam`.`IDAction` AND (`taa`.`DisabledForLevels`='0' OR `taa`.`DisabledForLevels`&POW(2, `tam`.`IDLevel`-1)=0) ";
                $sGroupClause = "GROUP BY `tam`.`IDLevel`";
                break;
        }

        $aMethod['params'][0] = "SELECT " . ($bReturnCount ? "SQL_CALC_FOUND_ROWS" : "") . "
                `taa`.`ID` AS `id`,
                `taa`.`Module` AS `module`,
                `taa`.`Name` AS `name`,
                `taa`.`Title` AS `title`,
                `taa`.`Countable` AS `countable`,
                `taa`.`DisabledForLevels` AS `disabled_for_levels`" . $sSelectClause . "
            FROM `sys_acl_actions` AS `taa` " . $sJoinClause . "
            WHERE 1 " . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause . " " . $sLimitClause;
        $aItems = call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);

        if(!$bReturnCount)
            return !empty($aItems);

        return (int)$this->getOne("SELECT FOUND_ROWS()");
    }

    /**
     * Fetch the last purchased/assigned membership that is still active for the given profile.
     *
     * NOTE. Don't use cache here, because it's causing an error, if a number of memberrship levels are purchased at the same time.
     * fromMemory returns the same DateExpires because setMembership (old buyMembership) function is called in cycle in the same session.
     */
    function getLevelCurrent($iProfileId, $iTime = 0)
    {
        $iTime = $iTime == 0 ? time() : (int)$iTime;

        $sSql = $this->prepare("
            SELECT  `sys_acl_levels_members`.`IDLevel` as `id`,
                    `sys_acl_levels`.`Name` AS `name`,
                    `sys_acl_levels`.`QuotaSize` AS `quota_size`,
                    `sys_acl_levels`.`QuotaNumber` AS `quota_number`,
                    `sys_acl_levels`.`QuotaMaxFileSize` AS `quota_max_file_size`,
                    UNIX_TIMESTAMP(`sys_acl_levels_members`.`DateStarts`) as `date_starts`,
                    UNIX_TIMESTAMP(`sys_acl_levels_members`.`DateExpires`) as `date_expires`,
                    `sys_acl_levels_members`.`TransactionID` AS `transaction_id`,
                    `sys_profiles`.`status`
            FROM `sys_acl_levels_members`
            RIGHT JOIN `sys_profiles` ON `sys_acl_levels_members`.IDMember = `sys_profiles`.`id`
                AND (`sys_acl_levels_members`.DateStarts IS NULL OR `sys_acl_levels_members`.DateStarts <= FROM_UNIXTIME(?))
                AND (`sys_acl_levels_members`.DateExpires IS NULL OR `sys_acl_levels_members`.DateExpires > FROM_UNIXTIME(?))
            LEFT JOIN `sys_acl_levels` ON `sys_acl_levels_members`.IDLevel = `sys_acl_levels`.ID
            WHERE `sys_profiles`.`id` = ?
            ORDER BY `sys_acl_levels_members`.DateStarts DESC
            LIMIT 1", $iTime, $iTime, $iProfileId);

        return $this->getRow($sSql);
    }

    function getLevelByIdCached($iLevel)
    {
        $sQuery = $this->prepare("SELECT
                `tal`.`ID` AS `id`,
                `tal`.`Name` AS `name`,
                `tal`.`QuotaSize` AS `quota_size`,
                `tal`.`QuotaNumber` AS `quota_number`,
                `tal`.`QuotaMaxFileSize` AS `quota_max_file_size`
            FROM `sys_acl_levels` AS `tal`
            WHERE `tal`.`ID`=?
            LIMIT 1", $iLevel);
        return $this->fromCache('sys_acl_levels' . $iLevel, 'getRow', $sQuery);
    }

    function getAction($iMembershipId, $iActionId)
    {
        $sQuery = $this->prepare("SELECT
                `tam`.`IDAction` AS `id`,
                `taa`.`Name` AS `name`,
                `taa`.`Title` AS `title`,
                `tam`.`AllowedCount` AS `allowed_count`,
                `tam`.`AllowedPeriodLen` AS `allowed_period_len`,
                UNIX_TIMESTAMP(`tam`.`AllowedPeriodStart`) as `allowed_period_start`,
                UNIX_TIMESTAMP(`tam`.`AllowedPeriodEnd`) as `allowed_period_end`,
                `tam`.`AdditionalParamValue` AS `additional_param_value`
            FROM `sys_acl_actions` AS `taa`
            LEFT JOIN `sys_acl_matrix` AS `tam` ON `tam`.`IDAction` = `taa`.`ID` AND `tam`.`IDLevel` = ?
            WHERE `taa`.`ID` = ?", $iMembershipId, $iActionId);
        return $this->getRow($sQuery);
    }

    function getActionTrack($iActionId, $iProfileId)
    {
        $sQuery = $this->prepare("SELECT
                `taat`.`ActionsLeft` AS `actions_left`,
                UNIX_TIMESTAMP(`taat`.`ValidSince`) as `valid_since`
            FROM `sys_acl_actions_track` AS `taat`
            WHERE `taat`.`IDAction`=? AND `taat`.`IDMember`=?", $iActionId, $iProfileId);
        return $this->getRow($sQuery);
    }

    function insertActionTarck($iActionId, $iProfileId, $iActionsLeft, $iValidSince)
    {
        $sQuery = $this->prepare("INSERT INTO `sys_acl_actions_track`(`IDAction`, `IDMember`, `ActionsLeft`, `ValidSince`) VALUES (?, ?, ?, FROM_UNIXTIME(?))", $iActionId, $iProfileId, $iActionsLeft, $iValidSince);
        return (int)$this->query($sQuery) > 0;
    }

    function updateActionTrack($iActionId, $iProfileId, $iActionsLeft, $iValidSince = 0)
    {
    	$aBindings = array(
    		'actions_left' => $iActionsLeft,
    		'action_id' => $iActionId,
    		'member_id' => $iProfileId
    	);

        $sUpdateAddon = "";
        if($iValidSince != 0) {
        	$aBindings['valid_since'] = $iValidSince;

            $sUpdateAddon = ", ValidSince=FROM_UNIXTIME(:valid_since)";
        }

        $sQuery = "UPDATE `sys_acl_actions_track` SET `ActionsLeft`=:actions_left" . $sUpdateAddon . " WHERE `IDAction`=:action_id AND `IDMember`=:member_id";
        return (int)$this->query($sQuery, $aBindings) > 0;
    }

    function insertLevelByProfileId($iProfileId, $iMembershipId, $iDateStarts, $aPeriod, $sTransactionId)
    {
    	$aBindings = array(
			'member_id' => $iProfileId,
			'level_id' => $iMembershipId,
			'transaction_id' => $sTransactionId,
			'date_starts' => $iDateStarts
		);

    	$sSetClause = '';
    	if((int)$aPeriod['period'] != 0) {
    		$aBindings['period'] = (int)$aPeriod['period'];

	    	switch($aPeriod['period_unit']) {
	    		case MEMBERSHIP_PERIOD_UNIT_DAY:
	    		case MEMBERSHIP_PERIOD_UNIT_WEEK:
	    			if($aPeriod['period_unit'] == MEMBERSHIP_PERIOD_UNIT_WEEK)
	    				$aBindings['period'] *= 7;

	    			$sSetClause = ", `DateExpires`=DATE_ADD(FROM_UNIXTIME(:date_starts), INTERVAL :period DAY)";
	    			break;

	    		case MEMBERSHIP_PERIOD_UNIT_MONTH:
	    			$sSetClause = ", `DateExpires`=DATE_ADD(FROM_UNIXTIME(:date_starts), INTERVAL :period MONTH)";
	    			break;

	    		case MEMBERSHIP_PERIOD_UNIT_YEAR:
	    			$sSetClause = ", `DateExpires`=DATE_ADD(FROM_UNIXTIME(:date_starts), INTERVAL :period YEAR)";
	    			break;
	    	}
    	}

        $sQuery = $this->prepare("INSERT `sys_acl_levels_members` SET `IDMember`=:member_id, `IDLevel`=:level_id, `DateStarts`=FROM_UNIXTIME(:date_starts), `TransactionID`=:transaction_id" . $sSetClause);
        return (int)$this->query($sQuery, $aBindings) > 0;
    }

    function deleteLevelByProfileId($iProfileId, $bAll = false)
    {
        $sQuery = $this->prepare("DELETE FROM `sys_acl_levels_members` WHERE `IDMember` = ? " . ($bAll ? "" : " AND (`DateExpires` IS NULL OR `DateExpires` > NOW())"), $iProfileId);
        return (int)$this->query($sQuery) > 0;
    }

	function deleteLevelBy($aWhere)
    {
    	if(empty($aWhere))
    		return false;

        $sQuery = "DELETE FROM `sys_acl_levels_members` WHERE " . $this->arrayToSQL($aWhere, ' AND ');
        return (int)$this->query($sQuery) > 0;
    }

    function maintenance($iDaysToCleanMemLevels)
    {
        $sQuery = $this->prepare("DELETE FROM `sys_acl_levels_members` WHERE `DateExpires` < NOW() - INTERVAL ? DAY", $iDaysToCleanMemLevels);
        if ($iDeleteMemLevels = $this->query($sQuery))
            $this->query("OPTIMIZE TABLE `sys_acl_levels_members`");
        return $iDeleteMemLevels;
    }

    function clearActionsTracksForMember($iMemberId)
    {
        $sQuery = $this->prepare("DELETE FROM `sys_acl_actions_track` WHERE `IDMember` = ?", (int)$iMemberId);
	    return $this->query($sQuery);
    }
}

/** @} */
