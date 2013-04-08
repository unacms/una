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

class BxMbpDb extends BxDolModuleDb {
    var $_oConfig;
    /*
     * Constructor.
     */
    function BxMbpDb(&$oConfig) {
        parent::BxDolModuleDb($oConfig);

        $this->_oConfig = &$oConfig;
    }

    function getMembershipsBy($aParams = array()) {
        $sMethod = "getAll";
        $sSelectClause = $sJoinClause = $sWhereClause = "";
        if(isset($aParams['type']))
            switch($aParams['type']) {
                case 'price_id':
                    $sMethod = "getRow";
                    $sSelectClause .= ", `tlp`.`id` AS `price_id`, `tlp`.`Days` AS `price_days`, `tlp`.`Price` AS `price_amount`";
                    $sJoinClause .= "LEFT JOIN `sys_acl_level_prices` AS `tlp` ON `tl`.`ID`=`tlp`.`IDLevel`";
                    $sWhereClause .= " AND `tl`.`Active`='yes' AND `tl`.`Purchasable`='yes' AND `tlp`.`id`='" . $aParams['id'] . "'";
                    break;
                case 'price_all':
                    $sSelectClause .= ", `tlp`.`id` AS `price_id`, `tlp`.`Days` AS `price_days`, `tlp`.`Price` AS `price_amount`";
                    $sJoinClause .= "INNER JOIN `sys_acl_level_prices` AS `tlp` ON `tl`.`ID`=`tlp`.`IDLevel`";
                    $sWhereClause = " AND `tl`.`Active`='yes' AND `tl`.`Purchasable`='yes'";
                    break;
                case 'level_id':
                    $sMethod = "getRow";
                    $sWhereClause .= " AND `tl`.`ID`='" . $aParams['id'] . "'";
                    break;
            }

        $sSql = "SELECT
                `tl`.`ID` AS `mem_id`,
                `tl`.`Name` AS `mem_name`,
                `tl`.`Icon` AS `mem_icon`,
                `tl`.`Description` AS `mem_description` " . $sSelectClause . "
            FROM `sys_acl_levels` AS `tl` " . $sJoinClause . "
            WHERE 1" . $sWhereClause;
       return $this->$sMethod($sSql);
    }
}
?>