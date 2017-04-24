<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Shopify Shopify
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Module database queries
 */
class BxShopifyDb extends BxBaseModTextDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    public function getSettings($aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

    	$aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));
        $sWhereClause = $sOrderByClause = "";

        $sSelectClause = "*";
        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'id' => $aParams['id']
                );

                $sWhereClause .= " AND `id`=:id";
                break;

            case 'author':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                	'author' => $aParams['author']
                );

                $sWhereClause .= " AND `author`=:author";
                break;

            case 'all':
            	break;
        }

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " FROM `" . $CNF['TABLE_SETTINGS'] . "` WHERE 1 " . $sWhereClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }
}

/** @} */
