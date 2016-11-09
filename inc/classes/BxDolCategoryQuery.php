<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Database queries for category objects.
 * @see BxDolCategory
 */
class BxDolCategoryQuery extends BxDolDb
{
    protected $_aObject;

    public function __construct($aObject)
    {
        parent::__construct();
        $this->_aObject = $aObject;
    }

    static public function getCategoryObject ($sObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_category` WHERE `object` = ?", $sObject);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getCategoryObjectByFormAndList ($sObjectForm, $sListName)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_category` WHERE `form_object` = ? AND `list_name` = ?", $sObjectForm, $sListName);
        $aObject = $oDb->getRow($sQuery);
        if (!$aObject || !is_array($aObject))
            return false;

        return $aObject;
    }

    static public function getItemsNumInCategory ($aObject, $sCategoryValue)
    {
        $oDb = BxDolDb::getInstance();
        $sQuery = $oDb->prepare("SELECT COUNT(*) FROM `" . $aObject['table'] . "` " . $aObject['join'] . " WHERE `" . $aObject['field'] . "` = ? " . $aObject['where'], $sCategoryValue);
        return $oDb->getOne($sQuery);
    }
}

/** @} */
