<?php

/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/payment/classes/BxPmtDb.php');

class BxPfwDb extends BxPmtDb
{
    function BxPfwDb(&$oConfig)
    {
        parent::BxPmtDb($oConfig);
    }

	function getSubscription($aParams)
    {
        return $this->getProcessed($aParams);
    }

	function getProcessed($aParams)
    {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sMethodName = 'getRow';
        $sWhereClause = "";
        switch($aParams['type']) {
            case 'id':
                $sWhereClause = " AND `tt`.`id`='" . $aParams['id'] . "'";
                break;
            case 'order_id':
                $sWhereClause = " AND `tt`.`order_id`='" . $aParams['order_id'] . "'";
                break;
			case 'order_profile':
                $sWhereClause = " AND `ttp`.`order_profile`='" . $aParams['order_profile'] . "'";
                break;
            case 'mixed':
                $sMethodName = 'getAll';
                foreach($aParams['conditions'] as $sKey => $sValue)
                    $sWhereClause .= " AND `tt`.`" . $sKey . "`='" . $sValue . "'";
                break;

        }

        $sSql = "SELECT
        		`tt`.`pending_id` AS `pending_id`,
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
                `ttp`.`order_ref` AS `order_ref`,
                `ttp`.`order_profile` AS `order_profile`,
                `ttp`.`error_msg` AS `error_msg`,
                `ttp`.`provider` AS `provider`
            FROM `" . $this->_sPrefix . "transactions` AS `tt`
            LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
            WHERE 1" . $sWhereClause;

        return $this->$sMethodName($sSql);
    }
	function getSubscriptionOrders($aParams)
    {
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
               `tt`.`client_id` AS `user_id`,
               `tp`.`NickName` AS `user_name`
           FROM `" . $this->_sPrefix . "transactions` AS `tt`
           LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
           LEFT JOIN `Profiles` AS `tp` ON `tt`.`client_id`=`tp`.`ID`
           WHERE `tt`.`seller_id`='" . $aParams['seller_id'] . "' AND `ttp`.`order_profile`<>'' " . $sFilterAddon . "
           ORDER BY `tt`.`date` DESC
           LIMIT " . $aParams['start'] . ", " . $aParams['per_page'];

        return $this->getAll($sSql);
    }
    function getSubscriptionOrdersCount($aParams)
    {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = "";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tt`.`order_id` LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               COUNT(`tt`.`id`)
           FROM `" . $this->_sPrefix . "transactions` AS `tt`
           LEFT JOIN `" . $this->_sPrefix . "transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id`
           LEFT JOIN `Profiles` AS `tp` ON `tt`.`client_id`=`tp`.`ID`
           WHERE `tt`.`seller_id`='" . $aParams['seller_id'] . "' AND `ttp`.`order_profile`<>'' " . $sFilterAddon . "
           LIMIT 1";

        return (int)$this->getOne($sSql);
    }
	function getHistoryOrders($aParams)
    {
        $sDateFormat = $this->_oConfig->getDateFormat('orders');

        $sFilterAddon = $aParams['seller_id'] != BX_PMT_EMPTY_ID ? " AND `tt`.`seller_id`='" . $aParams['seller_id'] . "'" : " AND `tt`.`seller_id`<>'" . BX_PMT_ADMINISTRATOR_ID . "'";
        if(!empty($aParams['filter']))
            $sFilterAddon = " AND (DATE_FORMAT(FROM_UNIXTIME(`tt`.`date`), '" . $sDateFormat . "') LIKE '%" . $aParams['filter'] . "%' OR `tt`.`order_id` LIKE '%" . $aParams['filter'] . "%' OR `tp`.`NickName` LIKE '%" . $aParams['filter'] . "%' OR `ttp`.`order` LIKE '%" . $aParams['filter'] . "%')";

        $sSql = "SELECT
               `tt`.`id` AS `id`,
               `ttp`.`order` AS `order`,
               `ttp`.`order_profile` AS `order_profile`,
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
}
