<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

bx_import('BxDolModuleDb');

class BxPmtDb extends BxDolModuleDb {
    var $_oConfig;
    /*
     * Constructor.
     */
    function BxPmtDb(&$oConfig) {
        parent::BxDolModuleDb($oConfig);

        $this->_oConfig = &$oConfig;
    }

    /**
     * Payment details methods
     */
    function getForm() {
        $sSql = "SELECT
                `tp`.`id` AS `provider_id`,
                `tp`.`name` AS `provider_name`,
                `tp`.`caption` AS `provider_caption`,
                `tp`.`description` AS `provider_description`,
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

        return $this->getAll($sSql);
    }
    function getFormData($iUserId) {
        $sSql = "SELECT
                `tuv`.`option_id` AS `option_id`,
                `tuv`.`value` AS `value`
            FROM `" . $this->_sPrefix . "user_values` AS `tuv`
            WHERE `tuv`.`user_id`='" . $iUserId . "'";

        return $this->getAllWithKey($sSql, 'option_id');
    }
    function updateOption($iUserId, $iOptionId, $sValue) {
        $sSql = "REPLACE INTO `" . $this->_sPrefix . "user_values` SET `user_id`='" . $iUserId . "', `option_id`='" . $iOptionId . "', `value`='" . $sValue . "'";
        return $this->query($sSql);
    }

    /**
     * Shopping cart methods.
     */
    function getCartItems($iId) {
        return $this->getOne("SELECT `items` FROM `" . $this->_sPrefix . "cart` WHERE `client_id`='" . $iId . "' LIMIT 1");
    }
    function setCartItems($iId, $sItems) {
        $sItems = trim($sItems, ":");
        if(empty($sItems))
            $sSql = "DELETE FROM `" . $this->_sPrefix . "cart` WHERE `client_id`='" . $iId . "' LIMIT 1";
        else
            $sSql = "REPLACE INTO `" . $this->_sPrefix . "cart` SET `client_id`='" . $iId . "', `items`='" . $sItems . "'";

        return $this->query($sSql);
    }
    function getVendorId($sUsername){
        if(empty($sUsername))
           return BX_PMT_EMPTY_ID;

        return (int)$this->getOne("SELECT `ID` FROM `Profiles` WHERE `NickName`='" . $sUsername . "' LIMIT 1");
    }
    function getVendorInfoProfile($iId) {
        $sCurrencyCode = $this->_oConfig->getCurrencyCode();
        $sCurrencySign = $this->_oConfig->getCurrencySign();

        if($iId == BX_PMT_ADMINISTRATOR_ID)
            return array(
                'id' => BX_PMT_ADMINISTRATOR_ID,
                'username' => _t('_payment_txt_username_' . BX_PMT_ADMINISTRATOR_USERNAME),
                'profile_url' => '',
                'status' => 'Active',
                'currency_code' => $sCurrencyCode,
                'currency_sign' => $sCurrencySign
            );

        $sSql = "SELECT
               `tp`.`ID` AS `id`,
               `tp`.`NickName` AS `username`,
               '' AS `profile_url`,
               `tp`.`Status` AS `status`,
               '" . $sCurrencyCode . "' AS `currency_code`,
               '" . $sCurrencySign . "' AS `currency_sign`
            FROM `Profiles` AS `tp`
            WHERE `tp`.`ID`='" . $iId . "'
            LIMIT 1";
        $aVendor = $this->getRow($sSql);

        if(!empty($aVendor))
            $aVendor['profile_url'] = getProfileLink($aVendor['id']);

        return $aVendor;
    }
    function getVendorInfoProviders($iId, $sProvider = '') {
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
    function getFirstAdminId() {
        return (int)$this->getOne("SELECT `ID` FROM `Profiles` WHERE `Role`='3' AND `Status`='Active' ORDER BY `ID` ASC LIMIT 1");
    }

    /**
     * Process payment methods
     */
    function getProviders($sName = '') {

        $sWhereClause = "1";
        if(!empty($sName))
            $sWhereClause = "`tp`.`name`='" . $sName . "'";

        $sSql = "SELECT
                `tp`.`id` AS `id`,
                `tp`.`name` AS `name`,
                `tp`.`caption` AS `caption`,
                `tp`.`description` AS `description`,
                `tp`.`option_prefix` AS `option_prefix`,
                `tp`.`class_name` AS `class_name`
            FROM `" . $this->_sPrefix . "providers` AS `tp`
            WHERE " . $sWhereClause;
        return !empty($sName) ? $this->getRow($sSql) : $this->getAll($sSql);
    }
    function getOptions($iUserId = BX_PMT_EMPTY_ID, $iProviderId = 0) {
        if($iUserId == BX_PMT_EMPTY_ID && empty($iProviderId))
           return $this->getAll("SELECT `id`, `name`, `type` FROM `" . $this->_sPrefix . "providers_options`");

        $sWhereAddon = "";
        if(!empty($iProviderId))
            $sWhereAddon = " AND `tpo`.`provider_id`='" . $iProviderId . "'";

        $sSql = "SELECT
               `tpo`.`name` AS `name`,
               `tuv`.`value` AS `value`
            FROM `" . $this->_sPrefix . "providers_options` AS `tpo`
            LEFT JOIN `" . $this->_sPrefix . "user_values` AS `tuv` ON `tpo`.`id`=`tuv`.`option_id`
            WHERE 1" . $sWhereAddon . " AND `tuv`.`user_id`='" . $iUserId . "'";

        return $this->getAllWithKey($sSql, 'name');
    }

    function getPending($aParams) {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sMethodName = 'getRow';
        switch($aParams['type']) {
            case 'id':
                $sWhereClause = " AND `id`='" . $aParams['id'] . "'";
                break;
        }
        $sSql = "SELECT
                    `id`,
                    `client_id`,
                    `seller_id`,
                    `items`,
                    `amount`,
                    `order`,
                    `error_code`,
                    `error_msg`,
                    `provider`,
                    `date`,
                    DATE_FORMAT(FROM_UNIXTIME(`date`), '" . $sDateFormat . "') AS `date_uf`
                FROM `" . $this->_sPrefix . "transactions_pending`
                WHERE 1 " . $sWhereClause . "
                LIMIT 1";
        return $this->$sMethodName($sSql);
    }
    function insertPending($iClientId, $sProviderName, $aCartInfo) {
        $sItems = "";
        foreach($aCartInfo['items'] as $aItem)
            $sItems .= $aCartInfo['vendor_id'] . '_' . $aItem['module_id'] . '_' . $aItem['id'] . '_' . $aItem['quantity'] . ':';

        $sSql = "INSERT INTO `" . $this->_sPrefix . "transactions_pending` SET
                    `client_id`='" . $iClientId . "',
                    `seller_id`='" . $aCartInfo['vendor_id'] . "',
                    `items`='" . trim($sItems, ':') . "',
                    `amount`='" . $aCartInfo['items_price'] . "',
                    `provider`='" . $sProviderName . "',
                    `date`=UNIX_TIMESTAMP()";

        return (int)$this->query($sSql) > 0 ? $this->lastId() : 0;
    }
    function updatePending($iId, $aValues) {
        $sUpdateClause = "";
        foreach($aValues as $sName => $sValue)
            $sUpdateClause .= "`" . $sName . "`='" . $sValue . "', ";

        $sSql = "UPDATE `" . $this->_sPrefix . "transactions_pending`
                SET " . $sUpdateClause . "`reported`='0'
                WHERE `id`='" . $iId . "'";
        return (int)$this->query($sSql) > 0;
    }
    function insertTransaction($aInfo) {
        $sSetClause = "";
        foreach($aInfo as $sKey => $sValue)
            $sSetClause .= "`" . $sKey . "`='" . $sValue . "', ";

        return $this->query("INSERT INTO `" . $this->_sPrefix . "transactions` SET " . $sSetClause . "`date`=UNIX_TIMESTAMP(), `reported`='0'");
    }
    function getProcessed($aParams){
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sMethodName = 'getRow';
        $sWhereClause = "";
        switch($aParams['type']) {
            case 'id':
                $sWhereClause = " AND `tt`.`id`='" . $aParams['id'] . "'";
                break;
            case 'order_id':
                $sMethodName = 'getAll';
                $sWhereClause = " AND `tt`.`order_id`='" . $aParams['order_id'] . "'";
                break;
            case 'mixed':
                $sMethodName = 'getAll';
                foreach($aParams['conditions'] as $sKey => $sValue)
                    $sWhereClause .= " AND `tt`.`" . $sKey . "`='" . $sValue . "'";
                break;

        }
        $sSql = "SELECT
                `tt`.`order_id` AS `order_id`,
                `tt`.`client_id` AS `client_id`,
                `tt`.`seller_id` AS `seller_id`,
                `tt`.`module_id` AS `module_id`,
                `tt`.`item_id` AS `item_id`,
                `tt`.`item_count` AS `item_count`,
                `tt`.`amount` AS `amount`,
                `tt`.`date` AS `date`,
                DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') AS `date_uf`,
                `ttp`.`order` AS `order`,
                `ttp`.`error_msg` AS `error_msg`,
                `ttp`.`provider` AS `provider`
            FROM `" . $this->_sPrefix . "transactions` AS `tt`
            LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
            WHERE 1" . $sWhereClause;

        return $this->$sMethodName($sSql);
    }
    function getHistory($aParams) {
        return $this->getProcessed($aParams);
    }
    //--- Order Administration ---//
    function userExists($sName) {
        $iId = (int)$this->getOne("SELECT `ID` FROM `Profiles` WHERE `NickName`='" . $sName . "' LIMIT 1");
        return $iId > 0 ? $iId : false;
    }
    function getModules() {
        $sSql = "SELECT
            `tsm`.`id` AS `id`,
            `tsm`.`uri` AS `uri`,
            `tsm`.`title` AS `title`
            FROM `" . $this->_sPrefix . "modules` AS `tm`
            LEFT JOIN `sys_modules` AS `tsm` ON `tm`.`uri`=`tsm`.`uri`
            ORDER BY `tsm`.`date`";

        return $this->getAll($sSql);
    }
    function getPendingOrders($aParams) {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = "";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`ttp`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               `ttp`.`id` AS `id`,
               `ttp`.`order` AS `order`,
               `ttp`.`amount` AS `amount`,
               '-' AS `license`,
               `ttp`.`items` AS `items`,
               `ttp`.`date` AS `date`,
               DATE_FORMAT(FROM_UNIXTIME(`ttp`.`date`), '" . $sDateFormat . "') AS `date_uf`,
               `tp`.`ID` AS `user_id`,
               `tp`.`NickName` AS `user_name`
           FROM `" . $this->_sPrefix . "transactions_pending` AS `ttp`
           LEFT JOIN `Profiles` AS `tp` ON `ttp`.`client_id`=`tp`.`ID`
           WHERE `ttp`.`seller_id`='" . $aParams['seller_id'] . "' AND (ISNULL(`ttp`.`order`) OR (NOT ISNULL(`ttp`.`order`) AND `ttp`.`error_code`<>'1')) " . $sFilterAddon . "
           ORDER BY `ttp`.`date` DESC
           LIMIT " . $aParams['start'] . ", " . $aParams['per_page'];
        $aOrders = $this->getAll($sSql);

        foreach($aOrders as $iKey => $aOrder) {
            $aProducts = BxPmtCart::items2array($aOrder['items']);
            $aOrders[$iKey]['products'] = count($aProducts);

            $iItems = 0;
            foreach($aProducts as $aProduct)
                $iItems += (int)$aProduct['item_count'];
            $aOrders[$iKey]['items'] = $iItems;
        }
        return $aOrders;
    }
    function getPendingOrdersCount($aParams) {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = "";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`ttp`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               COUNT(`ttp`.`id`)
           FROM `" . $this->_sPrefix . "transactions_pending` AS `ttp`
           LEFT JOIN `Profiles` AS `tp` ON `ttp`.`client_id`=`tp`.`ID`
           WHERE `ttp`.`seller_id`='" . $aParams['seller_id'] . "' AND (ISNULL(`ttp`.`order`) OR (NOT ISNULL(`ttp`.`order`) AND `ttp`.`error_code`<>'0')) " . $sFilterAddon . "
           LIMIT 1";

        return (int)$this->getOne($sSql);
    }
    function getProcessedOrders($aParams) {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = "";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tt`.`order_id` LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               `tt`.`id` AS `id`,
               `ttp`.`order` AS `order`,
               `tt`.`amount` AS `amount`,
               `tt`.`order_id` AS `license`,
               `tt`.`date` AS `date`,
               DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') AS `date_uf`,
               '1' AS `products`,
               `tt`.`item_count` AS `items`,
               `tp`.`ID` AS `user_id`,
               `tp`.`NickName` AS `user_name`
           FROM `" . $this->_sPrefix . "transactions` AS `tt`
           LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
           LEFT JOIN `Profiles` AS `tp` ON `tt`.`client_id`=`tp`.`ID`
           WHERE `tt`.`seller_id`='" . $aParams['seller_id'] . "' " . $sFilterAddon . "
           ORDER BY `tt`.`date` DESC
           LIMIT " . $aParams['start'] . ", " . $aParams['per_page'];

        return $this->getAll($sSql);
    }
    function getProcessedOrdersCount($aParams) {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = "";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tt`.`order_id` LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               COUNT(`tt`.`id`)
           FROM `" . $this->_sPrefix . "transactions` AS `tt`
           LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
           LEFT JOIN `Profiles` AS `tp` ON `tt`.`client_id`=`tp`.`ID`
           WHERE `tt`.`seller_id`='" . $aParams['seller_id'] . "' " . $sFilterAddon . "
           LIMIT 1";

        return (int)$this->getOne($sSql);
    }
    function getHistoryOrders($aParams) {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = $aParams['seller_id'] != BX_PMT_EMPTY_ID ? " AND `tt`.`seller_id`='" . $aParams['seller_id'] . "'" : " AND `tt`.`seller_id`<>'" . BX_PMT_ADMINISTRATOR_ID . "'";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tt`.`order_id` LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               `tt`.`id` AS `id`,
               `ttp`.`order` AS `order`,
               `tt`.`amount` AS `amount`,
               `tt`.`order_id` AS `license`,
               `tt`.`date` AS `date`,
               DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') AS `date_uf`,
               '1' AS `products`,
               `tt`.`item_count` AS `items`,
               `tp`.`ID` AS `user_id`,
               `tp`.`NickName` AS `user_name`
           FROM `" . $this->_sPrefix . "transactions` AS `tt`
           LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
           LEFT JOIN `Profiles` AS `tp` ON `tt`.`seller_id`=`tp`.`ID`
           WHERE `tt`.`client_id`='" . $aParams['user_id'] . "' " . $sFilterAddon . "
           ORDER BY `tt`.`date` DESC
           LIMIT " . $aParams['start'] . ", " . $aParams['per_page'];

        return $this->getAll($sSql);
    }
    function getHistoryOrdersCount($aParams) {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = !empty($aParams['seller_id']) ? " AND `tt`.`seller_id`='" . $aParams['seller_id'] . "'" : " AND `tt`.`seller_id`<>'-1'";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tt`.`order_id` LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               COUNT(`tt`.`id`)
           FROM `" . $this->_sPrefix . "transactions` AS `tt`
           LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
           LEFT JOIN `Profiles` AS `tp` ON `tt`.`seller_id`=`tp`.`ID`
           WHERE `tt`.`client_id`='" . $aParams['user_id'] . "' " . $sFilterAddon . "
           LIMIT 1";

        return (int)$this->getOne($sSql);
    }
    function reportPendingOrders($aOrders) {
        $mixedResult = $this->query("UPDATE `" . $this->_sPrefix . "transactions_pending` SET `reported`=`reported`+'1' WHERE `id` IN ('" . implode("','", $aOrders) . "')");
        return (int)$mixedResult > 0;
    }
    function reportProcessedOrders($aOrders) {
        $mixedResult = $this->query("UPDATE `" . $this->_sPrefix . "transactions` SET `reported`=`reported`+'1' WHERE `id` IN ('" . implode("','", $aOrders) . "')");
        return (int)$mixedResult > 0;
    }
    function cancelPendingOrders($aOrders) {
        $mixedResult = $this->query("DELETE FROM `" . $this->_sPrefix . "transactions_pending` WHERE `id` IN ('" . implode("','", $aOrders) . "')");
        return (int)$mixedResult > 0;
    }
    function cancelProcessedOrders($aOrders) {
        $mixedResult = $this->query("DELETE FROM `" . $this->_sPrefix . "transactions` WHERE `id` IN ('" . implode("','", $aOrders) . "')");
        return (int)$mixedResult > 0;
    }
}
?>