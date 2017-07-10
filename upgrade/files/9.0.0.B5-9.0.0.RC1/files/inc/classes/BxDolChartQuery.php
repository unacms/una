<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolChartQuery extends BxDolDb
{
    public function __construct($aObject = array())
    {
        parent::__construct();
    }

    static public function getChartObject($sObject)
    {
        $oDb = BxDolDb::getInstance();

        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_chart` WHERE `object` = ?", $sObject);

        $aObject = $oDb->getRow($sQuery);
        if(!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getChartObjects()
    {
        $oDb = BxDolDb::getInstance();

        $aObjects = $oDb->getAll("SELECT * FROM `sys_objects_chart` WHERE `active` = '1' ORDER BY `order` ASC");
        if(!$aObjects || !is_array($aObjects))
            return false;

        return $aObjects;
    }
}

/** @} */
