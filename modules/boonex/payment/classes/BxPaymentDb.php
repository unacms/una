<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPaymentDb extends BxBaseModPaymentDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    /**
     * Payment details methods
     */
    public function getForm()
    {
        $sQuery = "SELECT
                `tp`.`id` AS `provider_id`,
                `tp`.`name` AS `provider_name`,
                `tp`.`caption` AS `provider_caption`,
                `tp`.`description` AS `provider_description`,
                `tp`.`option_prefix` AS `provider_option_prefix`,
                `tpo`.`id` AS `id`,
                `tpo`.`name` AS `name`,
                `tpo`.`type` AS `type`,
                `tpo`.`caption` AS `caption`,
                `tpo`.`description` AS `description`,
                `tpo`.`extra` AS `extra`,
                `tpo`.`check_type` AS `check_type`,
                `tpo`.`check_params` AS `check_params`,
                `tpo`.`check_error` AS `check_error`
            FROM `" . $this->_sPrefix . "providers` AS `tp`
            LEFT JOIN `" . $this->_sPrefix . "providers_options` AS `tpo` ON `tp`.`id`=`tpo`.`provider_id`
            ORDER BY `tp`.`id` ASC, `tpo`.`order` ASC";

        return $this->getAll($sQuery);
    }

	public function getProviders($aParams = array())
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sWhereClause = "";
        if(!empty($aParams['type']))
	        switch($aParams['type']) {
	        	case 'by_name':
	        		$aMethod['name'] = 'getRow';
	        		$aMethod['params'][1] = array(
	                	'name' => $aParams['name']
	                );
	
	        		$sWhereClause = " AND `tp`.`name`=:name";
	        		break;
	
				case 'for_single':
					$aMethod['name'] = 'getAllWithKey';
					$aMethod['params'][1] = 'name';
	
					$sWhereClause = " AND `tp`.`for_single`='1'";
	        		break;
	
				case 'for_recurring':
					$aMethod['name'] = 'getAllWithKey';
					$aMethod['params'][1] = 'name';
	
					$sWhereClause = " AND `tp`.`for_recurring`='1'";
	        		break;

	        	case 'all':
					$aMethod['name'] = 'getAllWithKey';
					$aMethod['params'][1] = 'name';
	        		break;
	        }          

        $aMethod['params'][0] = "SELECT
                `tp`.`id` AS `id`,
                `tp`.`name` AS `name`,
                `tp`.`caption` AS `caption`,
                `tp`.`description` AS `description`,
                `tp`.`option_prefix` AS `option_prefix`,
                `tp`.`for_visitor` AS `for_visitor`,
                `tp`.`for_single` AS `for_single`,
                `tp`.`for_recurring` AS `for_recurring`,
                `tp`.`class_name` AS `class_name`,
                `tp`.`class_file` AS `class_file`
            FROM `" . $this->_sPrefix . "providers` AS `tp`
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function getOptions($iUserId = BX_PAYMENT_EMPTY_ID, $iProviderId = 0)
    {
    	$aBinding = array(
    		'user_id' => $iUserId
    	);

        if($iUserId == BX_PAYMENT_EMPTY_ID && empty($iProviderId))
           return $this->getAll("SELECT `id`, `name`, `type` FROM `" . $this->_sPrefix . "providers_options`");

        $sWhereAddon = "";
        if(!empty($iProviderId)) {
        	$aBinding['provider_id'] = $iProviderId;

            $sWhereAddon = " AND `tpo`.`provider_id`=:provider_id";
        }

        $sQuery = "SELECT
               `tpo`.`name` AS `name`,
               `tuv`.`value` AS `value`
            FROM `" . $this->_sPrefix . "providers_options` AS `tpo`
            LEFT JOIN `" . $this->_sPrefix . "user_values` AS `tuv` ON `tpo`.`id`=`tuv`.`option_id`
            WHERE 1" . $sWhereAddon . " AND `tuv`.`user_id`=:user_id";

        return $this->getAllWithKey($sQuery, 'name', $aBinding);
    }

    public function updateOption($iUserId, $iOptionId, $sValue)
    {
        $sQuery = $this->prepare("REPLACE INTO `" . $this->_sPrefix . "user_values` SET `user_id`=?, `option_id`=?, `value`=?", $iUserId, $iOptionId, $sValue);
        return $this->query($sQuery);
    }


    /**
     * Shopping cart methods.
     */
    public function getCartItems($iId)
    {
    	$sQuery = $this->prepare("SELECT `items` FROM `" . $this->_sPrefix . "cart` WHERE `client_id`=? LIMIT 1", $iId);
        return $this->getOne($sQuery);
    }

    public function getCartContent($iId)
    {
    	return $this->getRow("SELECT * FROM `" . $this->_sPrefix . "cart` WHERE `client_id`=:client_id LIMIT 1", array(
    		'client_id' => $iId
    	));
    }

    public function setCartItems($iId, $sItems, $aCustoms = array())
    {
        $sItems = trim($sItems, ":");
        if(empty($sItems))
            return $this->query("DELETE FROM `" . $this->_sPrefix . "cart` WHERE `client_id`=:client_id LIMIT 1", array(
                'client_id' => $iId
            ));

        return $this->query("REPLACE INTO `" . $this->_sPrefix . "cart` SET `client_id`=:client_id, `items`=:items, `customs`=:customs", array(
            'client_id' => $iId,
            'items' => $sItems,
            'customs' => !empty($aCustoms) && is_array($aCustoms) ? serialize($aCustoms) : ''
        ));
    }

	public function getVendorInfoProvidersSingle($iVendorId)
    {
    	return $this->getVendorInfoProviders($iVendorId, array('type' => 'for_single'));
    }

	public function getVendorInfoProvidersRecurring($iVendorId)
    {
    	return $this->getVendorInfoProviders($iVendorId, array('type' => 'for_recurring'));
    }

    public function getVendorInfoProviders($iVendorId, $aParams = array())
    {
    	if(empty($aParams))
    		$aParams = array('type' => 'all');

		$aProviders = $this->getProviders($aParams);
		$aOptions = $this->getOptions($iVendorId);

		$aResult = array();
		foreach($aProviders as $sProvider => $aProvider)
			if(isset($aOptions[$aProvider['option_prefix'] . 'active']) && $aOptions[$aProvider['option_prefix'] . 'active']['value'] == 'on') {
				foreach($aOptions as $sName => $aOption)
					if(strpos($sName, $aProvider['option_prefix']) !== false)
						$aProvider['options'][$sName] = $aOption;
				$aResult[$sProvider] = $aProvider;
			}

		return $aResult;
	}

    public function getAdminsIds()
    {
        $sQuery = "SELECT 
        		`tp`.`id` AS `id`,
        		`tp`.`type` AS `type`  
        	FROM `sys_profiles` AS `tp` 
        	INNER JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` AND `ta`.`role`&" . BX_DOL_ROLE_ADMIN . " 
        	WHERE 
        		`tp`.`type`<>'system' AND `tp`.`status`='active' 
        	ORDER BY `tp`.`id` ASC";

        $aAdmins = $this->getAllWithKey($sQuery, 'id');
        foreach($aAdmins as $iId => $aAdmin)
            if(!BxDolService::call($aAdmin['type'], 'act_as_profile'))
                unset($aAdmins[$iId]);

        return array_keys($aAdmins);
    }


	/*
     * Pending Orders methods
     */
    public function getOrderPending($aParams)
    {
    	$aMethod = array('name' => 'getRow', 'params' => array(0 => 'query'));

    	$sWhereClause = $sLimitClause = '';
        switch($aParams['type']) {
            case 'id':
            	$aMethod['params'][1] = array(
                	'id' => $aParams['id']
                );

                $sWhereClause = " AND `id`=:id";
                $sLimitClause = " LIMIT 1";
                break;

            case 'order':
            	$aMethod['params'][1] = array(
                	'order' => $aParams['order']
                );

            	$sWhereClause = " AND `order`=:order";
                $sLimitClause = " LIMIT 1";
                break;

            case 'mixed':
                $aMethod['name'] = 'getAll';

                $sWhereClause = ' AND ' . $this->arrayToSQL($aParams['conditions'], ' AND ');
                break;
        }

        $aMethod['params'][0] = "SELECT * FROM `" . $this->_sPrefix . "transactions_pending` WHERE 1 " . $sWhereClause . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertOrderPending($iClientId, $sType, $sProvider, $aCartInfo, $aCustom = array())
    {
        $sItems = "";
        foreach($aCartInfo['items'] as $aItem)
            $sItems .= $this->_oConfig->descriptorA2S(array($aCartInfo['vendor_id'], $aItem['module_id'], $aItem['id'], $aItem['quantity'])) . ':';

        return (int)$this->query("INSERT INTO `" . $this->_sPrefix . "transactions_pending` SET `client_id`=:client_id, `seller_id`=:seller_id, `type`=:type, `provider`=:provider, `items`=:items, `customs`=:customs, `amount`=:amount, `date`=UNIX_TIMESTAMP()", array(
            'client_id' => $iClientId,
            'seller_id' => $aCartInfo['vendor_id'],
            'type' => $sType, 
            'provider' => $sProvider,
            'items' => trim($sItems, ':'),
            'customs' => !empty($aCustom) && is_array($aCustom) ? serialize($aCustom) : '',
            'amount' => $aCartInfo['items_price']
        )) > 0 ? $this->lastId() : 0;
    }

    public function updateOrderPending($iId, $aValues)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->_sPrefix . "transactions_pending` SET " . $this->arrayToSQL($aValues) . " WHERE `id`=?", $iId);
        return (int)$this->query($sQuery) > 0;
    }

    public function deleteOrderPending($mixedId)
    {
    	if(!is_array($mixedId))
    		$mixedId = array($mixedId);

        return (int)$this->query("DELETE FROM `" . $this->_sPrefix . "transactions_pending` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")") > 0;
    }

    /*
     * Processed Orders
     */
    public function getOrderProcessed($aParams)
    {
    	$aMethod = array('name' => 'getRow', 'params' => array(0 => 'query'));

        $sWhereClause = "";
        switch($aParams['type']) {
            case 'id':
            	$aMethod['params'][1] = array(
                	'id' => $aParams['id']
                );

                $sWhereClause = " AND `tt`.`id`=:id";
                break;

            case 'new':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array(
                	'seller_id' => $aParams['seller_id']
                );

                $sWhereClause = " AND `tt`.`seller_id`=:seller_id AND `tt`.`new`='1'";
                break;

			case 'pending_id':
                if(empty($aParams['with_key'])) {
                	$aMethod['name'] = 'getAll';
                	$aMethod['params'][1] = array(
						'pending_id' => $aParams['pending_id']
					);
                }
                else {
                	$aMethod['name'] = 'getAllWithKey';
					$aMethod['params'][1] = $aParams['with_key'];
					$aMethod['params'][2] = array(
						'pending_id' => $aParams['pending_id']
					);
                }

                $sWhereClause = " AND `tt`.`pending_id`=:pending_id";
                break;

            case 'license':
                $aMethod['name'] = 'getAll';
                $aMethod['params'][1] = array(
                	'license' => $aParams['license']
                );

                $sWhereClause = " AND `tt`.`license`=:license";
                break;

            case 'mixed':
                $aMethod['name'] = 'getAll';

                $sWhereClause = ' AND ' . $this->arrayToSQL($aParams['conditions'], ' AND ');
                break;

        }

        $aMethod['params'][0] = "SELECT
        		`tt`.`id`,
                `tt`.`license`,
                `ttp`.`type`,
                `tt`.`client_id`,
                `tt`.`seller_id`,
                `tt`.`module_id`,
                `tt`.`item_id`,
                `tt`.`item_count`,
                `tt`.`amount`,
                `tt`.`date`,
                `ttp`.`order`,
                `ttp`.`error_msg`,
                `ttp`.`provider`
            FROM `" . $this->_sPrefix . "transactions` AS `tt`
            LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
            WHERE 1" . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertOrderProcessed($aValues)
    {
        return $this->query("INSERT INTO `" . $this->_sPrefix . "transactions` SET " . $this->arrayToSQL($aValues) . ", `date`=UNIX_TIMESTAMP()");
    }

	public function updateOrderProcessed($iId, $aValues)
    {        
        return $this->updateOrdersProcessed($aValues, array('id' => $iId));
    }

    public function updateOrdersProcessed($aValues, $aWhere)
    {
        if(empty($aValues) || !is_array($aValues) || empty($aWhere) || !is_array($aWhere))
            return false;

        return (int)$this->query("UPDATE `" . $this->_sPrefix . "transactions` SET " . $this->arrayToSQL($aValues) . " WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }

    public function deleteOrderProcessed($mixedId)
    {
    	if(!is_array($mixedId))
    		$mixedId = array($mixedId);

        return (int)$this->query("DELETE FROM `" . $this->_sPrefix . "transactions` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")") > 0;
    }

    public function getOrderHistory($aParams)
    {
        return $this->getOrderProcessed($aParams);
    }

	/*
     * Subscriptions methods
     */
    public function getOrderSubscription($aParams)
    {
        return $this->getOrderPending($aParams);
    }

    public function getSubscription($aParams)
    {
    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

    	$sWhereClause = $sLimitClause = '';
        switch($aParams['type']) {
            case 'pending_id':
                $aMethod['name'] = 'getRow';
            	$aMethod['params'][1] = array(
                	'pending_id' => $aParams['pending_id']
                );

                $sWhereClause = " AND `pending_id`=:pending_id";
                $sLimitClause = " LIMIT 1";
                break;
        }

        $aMethod['params'][0] = "SELECT * FROM `" . $this->_sPrefix . "subscriptions` WHERE 1 " . $sWhereClause . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertSubscription($aValues)
    {
        if(empty($aValues) || !is_array($aValues))
            return false;

        return $this->query("INSERT INTO `" . $this->_sPrefix . "subscriptions` SET " . $this->arrayToSQL($aValues) . ", `date`=UNIX_TIMESTAMP()");
    }

	public function updateSubscription($aValues, $aWhere)
    {
        if(empty($aValues) || !is_array($aValues) || empty($aWhere) || !is_array($aWhere))
            return false;

        return (int)$this->query("UPDATE `" . $this->_sPrefix . "subscriptions` SET " . $this->arrayToSQL($aValues) . " WHERE " . $this->arrayToSQL($aWhere, ' AND ')) > 0;
    }

    public function deleteSubscription($mixedId, $sReason)
    {
    	if(!is_array($mixedId))
    		$mixedId = array($mixedId);

        //--- Move to deleted subscriptions table.   
    	$sQuery = "INSERT IGNORE INTO `" . $this->_sPrefix . "subscriptions_deleted` SELECT *, :reason AS `reason`, UNIX_TIMESTAMP() AS `deleted` FROM `" . $this->_sPrefix . "subscriptions` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")";
		$this->query($sQuery, array(
		    'reason' => $sReason
		));

        return (int)$this->query("DELETE FROM `" . $this->_sPrefix . "subscriptions` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")") > 0;
    }

    //--- Order Administration ---//
    public function onProfileDelete($iId)
    {
    	$sQuery = $this->prepare("DELETE FROM `" . $this->_sPrefix . "cart` WHERE `client_id`=?", $iId);
    	$this->query($sQuery);

    	$sQuery = $this->prepare("DELETE FROM `" . $this->_sPrefix . "user_values` WHERE `user_id`=?", $iId);
    	$this->query($sQuery);
    }
}

/** @} */
