<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
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

    public function getFormData($iUserId)
    {
        $sQuery = $this->prepare("SELECT
                `tuv`.`option_id` AS `option_id`,
                `tuv`.`value` AS `value`
            FROM `" . $this->_sPrefix . "user_values` AS `tuv`
            WHERE `tuv`.`user_id`=?", $iUserId);

        return $this->getAllWithKey($sQuery, 'option_id');
    }

	public function getProviders($sName = '')
    {
        $sWhereClause = "1";
        if(!empty($sName))
            $sWhereClause = $this->prepare("`tp`.`name`=?", $sName);

        $sQuery = "SELECT
                `tp`.`id` AS `id`,
                `tp`.`name` AS `name`,
                `tp`.`caption` AS `caption`,
                `tp`.`description` AS `description`,
                `tp`.`option_prefix` AS `option_prefix`,
                `tp`.`for_visitor` AS `for_visitor`,
                `tp`.`class_name` AS `class_name`,
                `tp`.`class_file` AS `class_file`
            FROM `" . $this->_sPrefix . "providers` AS `tp`
            WHERE " . $sWhereClause;

        return !empty($sName) ? $this->getRow($sQuery) : $this->getAll($sQuery);
    }

    public function getOptions($iUserId = BX_PAYMENT_EMPTY_ID, $iProviderId = 0)
    {
        if($iUserId == BX_PAYMENT_EMPTY_ID && empty($iProviderId))
           return $this->getAll("SELECT `id`, `name`, `type` FROM `" . $this->_sPrefix . "providers_options`");

        $sWhereAddon = "";
        if(!empty($iProviderId))
            $sWhereAddon = $this->prepare(" AND `tpo`.`provider_id`=?", $iProviderId);

        $sQuery = $this->prepare("SELECT
               `tpo`.`name` AS `name`,
               `tuv`.`value` AS `value`
            FROM `" . $this->_sPrefix . "providers_options` AS `tpo`
            LEFT JOIN `" . $this->_sPrefix . "user_values` AS `tuv` ON `tpo`.`id`=`tuv`.`option_id`
            WHERE 1" . $sWhereAddon . " AND `tuv`.`user_id`=?", $iUserId);

        return $this->getAllWithKey($sQuery, 'name');
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
    public function setCartItems($iId, $sItems)
    {
        $sItems = trim($sItems, ":");
        if(empty($sItems))
            $sQuery = $this->prepare("DELETE FROM `" . $this->_sPrefix . "cart` WHERE `client_id`=? LIMIT 1", $iId);
        else
            $sQuery = $this->prepare("REPLACE INTO `" . $this->_sPrefix . "cart` SET `client_id`=?, `items`=?", $iId, $sItems);

        return $this->query($sQuery);
    }
    public function getVendorInfoProviders($iId, $sProvider = '')
    {
        $aResult = array();

        //--- Get specified payment provider if it's available ---//
        if(!empty($sProvider)) {
            $aProvider = $this->getProviders($sProvider);
            $aOptions = $this->getOptions($iId, $aProvider['id']);

            if(isset($aOptions[$aProvider['option_prefix'] . 'active']) && $aOptions[$aProvider['option_prefix'] . 'active']['value'] == 'on') {
                $aProvider['options'] = $aOptions;
                $aResult = $aProvider;
            }
        }
        //--- Get all available payment providers ---//
        else{
            $aProviders = $this->getProviders();
            $aOptions = $this->getOptions($iId);

            foreach($aProviders as $aProvider)
               if(isset($aOptions[$aProvider['option_prefix'] . 'active']) && $aOptions[$aProvider['option_prefix'] . 'active']['value'] == 'on') {
                   foreach($aOptions as $sName => $aOption)
                       if(strpos($sName, $aProvider['option_prefix']) !== false)
                           $aProvider['options'][$sName] = $aOption;
                   $aResult[] = $aProvider;
               }
        }

        return $aResult;
    }
    public function getFirstAdminId()
    {
    	$sQuery = $this->prepare("SELECT `profile_id` FROM `sys_accounts` WHERE `role`&" . BX_DOL_ROLE_ADMIN . " ORDER BY `profile_id` ASC LIMIT 1");
        return (int)$this->getOne($sQuery);
    }
    public function getAdminsIds()
    {
    	$sQuery = $this->prepare("SELECT `profile_id` FROM `sys_accounts` WHERE `role`&" . BX_DOL_ROLE_ADMIN . " ORDER BY `profile_id` ASC");
        return $this->getColumn($sQuery);
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
                $sWhereClause = $this->prepare(" AND `id`=?", $aParams['id']);
                $sLimitClause = " LIMIT 1";
                break;
        }

        $aMethod['params'][0] = "SELECT * FROM `" . $this->_sPrefix . "transactions_pending` WHERE 1 " . $sWhereClause . $sLimitClause;
        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }

    public function insertOrderPending($iClientId, $sProviderName, $aCartInfo)
    {
        $sItems = "";
        foreach($aCartInfo['items'] as $aItem)
            $sItems .= $aCartInfo['vendor_id'] . '_' . $aItem['module_id'] . '_' . $aItem['id'] . '_' . $aItem['quantity'] . ':';

        $sQuery = $this->prepare("INSERT INTO `" . $this->_sPrefix . "transactions_pending` SET
                    `client_id`=?,
                    `seller_id`=?,
                    `items`=?,
                    `amount`=?,
                    `provider`=?,
                    `date`=UNIX_TIMESTAMP()", $iClientId, $aCartInfo['vendor_id'], trim($sItems, ':'), $aCartInfo['items_price'], $sProviderName);

        return (int)$this->query($sQuery) > 0 ? $this->lastId() : 0;
    }

    public function updateOrderPending($iId, $aValues)
    {
        $sQuery = $this->prepare("UPDATE `" . $this->_sPrefix . "transactions_pending` SET " . $this->arrayToSQL($aValues) . " WHERE `id`=?", $iId);
        return (int)$this->query($sQuery) > 0;
    }

    public function cancelOrderPending($mixedId)
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
                $sWhereClause = $this->prepare(" AND `tt`.`id`=?", $aParams['id']);
                break;

			case 'pending_id':
                $aMethod['name'] = 'getAll';
                if(!empty($aParams['with_key'])) {
					$aMethod['name'] = 'getAllWithKey';
					$aMethod['params'][1] = $aParams['with_key'];
                }
                $sWhereClause = $this->prepare(" AND `tt`.`pending_id`=?", $aParams['pending_id']);
                break;

            case 'order_id':
                $aMethod['name'] = 'getAll';
                $sWhereClause = $this->prepare(" AND `tt`.`order_id`=?", $aParams['order_id']);
                break;

            case 'mixed':
                $aMethod['name'] = 'getAll';
                foreach($aParams['conditions'] as $sKey => $sValue)
                    $sWhereClause .= $this->prepare(" AND `tt`.`" . $sKey . "`=?", $sValue);
                break;

        }

        $aMethod['params'][0] = "SELECT
        		`tt`.`id`,
                `tt`.`order_id`,
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
        $sQuery = $this->prepare("UPDATE `" . $this->_sPrefix . "transactions` SET " . $this->arrayToSQL($aValues) . " WHERE `id`=?", $iId);
        return (int)$this->query($sQuery) > 0;
    }

    public function cancelOrderProcessed($mixedId)
    {
    	if(!is_array($mixedId))
    		$mixedId = array($mixedId);

        return (int)$this->query("DELETE FROM `" . $this->_sPrefix . "transactions` WHERE `id` IN (" . $this->implode_escape($mixedId) . ")") > 0;
    }

    public function getOrderHistory($aParams)
    {
        return $this->getOrderProcessed($aParams);
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
