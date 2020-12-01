<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLocationMapQuery extends BxDolDb
{

    public function __construct($aObject = array())
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getLocationMapObject($sObject)
    {
        $oDb = BxDolDb::getInstance();

        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_location_map` WHERE `object` = ?", $sObject);

        $aObject = $oDb->getRow($sQuery);
        if(!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }
}

/** @} */
