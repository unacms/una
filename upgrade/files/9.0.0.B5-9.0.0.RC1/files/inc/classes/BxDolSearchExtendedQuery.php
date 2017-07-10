<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolSearchExtendedQuery extends BxDolDb
{
    public function __construct($aObject = array())
    {
        parent::__construct();
    }

    static public function getSearchObject($sObject)
    {
        $oDb = BxDolDb::getInstance();

        $sQuery = $oDb->prepare("SELECT * FROM `sys_objects_search_extended` WHERE `object` = ?", $sObject);

        $aObject = $oDb->getRow($sQuery);
        if(!$aObject || !is_array($aObject))
            return false;

        $aObject['fields'] = self::getSearchFields($aObject);
        return $aObject;
    }

    static public function getSearchFields($aObject)
    {
        $oDb = BxDolDb::getInstance();

        $sQueryFields = "SELECT * FROM `sys_search_extended_fields` WHERE `object` = :object ORDER BY `order`";
        $aQueryFieldsBindings = array('object' => $aObject['object']);
        $aFields = $oDb->getAll($sQueryFields, $aQueryFieldsBindings);

        //--- Get fields
        if(empty($aFields) || !is_array($aFields)) {
            $aFields = BxDolContentInfo::getObjectInstance($aObject['object_content_info'])->getSearchableFieldsExtended();
            if(empty($aFields) || !is_array($aFields))
                return array();

            $iOrder = 0;
            foreach($aFields as $sField => $aField) 
                $oDb->query("INSERT INTO `sys_search_extended_fields`(`object`, `name`, `type`, `caption`, `values`, `search_type`, `search_operator`, `active`, `order`) VALUES(:object, :name, :type, :caption, :values, :search_type, :search_operator, '1', :order)", array(
                    'object' => $aObject['object'], 
                    'name' => $sField,
                    'type' => $aField['type'],
                    'caption' => $aField['caption'],
                    'values' => $aField['values'],
                    'search_type' => reset(BxDolSearchExtended::$TYPE_TO_TYPE_SEARCH[$aField['type']]), 
                    'search_operator' => reset(BxDolSearchExtended::$TYPE_TO_OPERATOR[$aField['type']]),
                    'order' => $iOrder++
                ));

            $aFields = $oDb->getAll($sQueryFields, $aQueryFieldsBindings);
        }

        //--- Process fields
        foreach ($aFields as $iIndex => $aField) {
            //--- Process field Values            
            if(!empty($aField['values'])) {
                if(strncmp(BX_DATA_LISTS_KEY_PREFIX, $aField['values'], 2) === 0) {
                    $sList = trim($aField['values'], BX_DATA_LISTS_KEY_PREFIX . ' ');
                    $aFields[$iIndex] = array_merge($aField, array(
                    	'values' => BxDolFormQuery::getDataItems($sList),
                    	'values_list_name' => $sList,
                    ));
                }
                else
                    $aFields[$iIndex]['values'] = unserialize($aField['values']);
            }
        }

        return $aFields;
    }

    public function deleteFields($aWhere)
    {
        if(empty($aWhere))
    		return false;

        return $this->query("DELETE FROM `sys_search_extended_fields` WHERE " . $this->arrayToSQL($aWhere, ' AND '));
    }
}

/** @} */
