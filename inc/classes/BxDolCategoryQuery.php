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

    static public function getItemsNumInCategory ($aObject, $sCategoryValue, $bPublicOnly = true, $aParams = [])
    {
        $oDb = BxDolDb::getInstance();
        $sWhere = '';
        if ($bPublicOnly && ($oModule = BxDolModule::getInstance($aObject['module'])) && isset($oModule->_oConfig->CNF)) {
            $CNF = &$oModule->_oConfig->CNF;

            if(isset($CNF['FIELD_ALLOW_VIEW_TO'])) {
                if (isset($aParams['context_id']) && !empty($aParams['context_id'])){
                    $sWhere .= ' AND `' . $aObject['table'] . '`.`' . $CNF['FIELD_ALLOW_VIEW_TO'] . '` IN(' . -$aParams['context_id'] . ') ';
                }
                else{
                    bx_import('BxDolPrivacy');
                    $a = isLogged() ? array(BX_DOL_PG_ALL, BX_DOL_PG_MEMBERS) : array(BX_DOL_PG_ALL);
                    $sWhere .= ' AND `' . $aObject['table'] . '`.`' . $CNF['FIELD_ALLOW_VIEW_TO'] . '` IN(' . $oDb->implode_escape($a) . ') ';
                }
            }

            if(isset($CNF['FIELD_STATUS']))
                $sWhere .= ' AND `' . $aObject['table'] . '`.`' . $CNF['FIELD_STATUS'] . '` = \'active\' ';

            if(isset($CNF['FIELD_STATUS_ADMIN']))
                $sWhere .= ' AND `' . $aObject['table'] . '`.`' . $CNF['FIELD_STATUS_ADMIN'] . '` = \'active\' ';
        }
        return $oDb->getOne("SELECT COUNT(*) FROM `" . $aObject['table'] . "` " . $aObject['join'] . " WHERE `" . $aObject['field'] . "` = :cat " . $aObject['where'] . $sWhere, array('cat' => $sCategoryValue));
    }
}

/** @} */
