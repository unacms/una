<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLocationFieldQuery extends BxDolDb
{

    public function __construct($aObject = array())
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getLocationFieldObject($sObject)
    {
        $oDb = BxDolDb::getInstance();

        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_location_field` WHERE `object` = ?", $sObject);

        $aObject = $oDb->getRow($sQuery);
        if(!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getObjects ()
    {
        $aObjects = BxDolDb::getInstance()->getAll("SELECT * FROM `sys_objects_location_field`");
        if(empty($aObjects) || !is_array($aObjects))
            return array();

        return $aObjects;
    }
}

/** @} */
