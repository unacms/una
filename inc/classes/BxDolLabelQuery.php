<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * @see BxDolLabel
 */
class BxDolLabelQuery extends BxDolDb
{
    protected $_sTableLabels;

    public function __construct()
    {
    	parent::__construct();

        $this->_sTableLabels = 'sys_labels';
    }

    public function getLabels($aParams = array())
    {
        $aMethod = array('name' => 'getAll', 'params' => array(0 => 'query'));

        $sSelectClause = "`tl`.*";
        $sJoinClause = $sWhereClause = $sGroupClause = "";
        $sOrderClause = "`tl`.`order` ASC";

        if(isset($aParams['count_only']) && $aParams['count_only'] === true) {
            $aMethod['name'] = 'getOne';
            $sSelectClause = "COUNT(`tl`.`id`)";
        }

        switch($aParams['type']) {
            case 'id':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'id' => $aParams['id']
                );

                $sWhereClause = " AND `tl`.`id`=:id";
                break;

            case 'value':
                $aMethod['name'] = 'getRow';
                $aMethod['params'][1] = array(
                    'value' => mb_strtoupper($aParams['value'])
                );

                $sWhereClause = " AND UPPER(`tl`.`value`)=:value";
                break;

            case 'values':
                $aMethod['name'] = 'getColumn';

                $sSelectClause = "`tl`.`value`";
                break;

            case 'parent':
                $aMethod['params'][1] = array(
                    'parent' => $aParams['parent']
                );

                $sWhereClause = " AND `tl`.`parent`=:parent";

                if(!empty($aParams['exclude']) && is_array($aParams['exclude']))
                    $sWhereClause .= " AND `tl`.`id` NOT IN(" . $this->implode_escape($aParams['exclude']) . ")";
                break;

            case 'term':
                $aMethod['params'][1] = array(
                    'term' => '%' . $aParams['term'] . '%'
                );

                $sWhereClause = " AND `tl`.`value` LIKE :term";
                break;

            case 'parent_order':
                $aMethod['name'] = 'getOne';
                $aMethod['params'][1] = array(
                    'parent' => $aParams['parent']
                );

                $sSelectClause = "MAX(`tl`.`order`)";
                $sWhereClause = " AND `tl`.`parent`=:parent";
                break;
        }

        if(!empty($sGroupClause))
            $sGroupClause = "GROUP BY " . $sGroupClause;

        if(!empty($sOrderClause))
            $sOrderClause = "ORDER BY " . $sOrderClause;

        $aMethod['params'][0] = "SELECT " . $sSelectClause . " 
            FROM `" . $this->_sTableLabels . "` AS `tl`" . $sJoinClause . " 
            WHERE 1" . $sWhereClause . " " . $sGroupClause . " " . $sOrderClause;

        return call_user_func_array(array($this, $aMethod['name']), $aMethod['params']);
    }   
}

/** @} */
