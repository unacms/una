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
        $aObject['sortable_fields'] = self::getSearchSortableFields($aObject);
        return $aObject;
    }

    static public function getSearchSortableFields($aObject)
    {
        $oDb = BxDolDb::getInstance();
        $sQueryFields = "SELECT * FROM `sys_search_extended_sorting_fields` WHERE `object` = :object ORDER BY `order`";
        $aQueryFieldsBindings = array('object' => $aObject['object']);
        $aFields = $oDb->getAll($sQueryFields, $aQueryFieldsBindings);
        //--- Get fields
        if(empty($aFields) || !is_array($aFields)) {
            $mFields = BxDolContentInfo::getObjectInstance($aObject['object_content_info'])->getSortableFieldsExtended();
            if (!empty($mFields)){
                $iOrder = 0;
                foreach($mFields as $sField => $aField) {
                    $bResult = (int)$oDb->query("INSERT INTO `sys_search_extended_sorting_fields`(`object`, `name`, `direction`, `caption`, `active`, `order`) VALUES(:object, :name, :direction, :caption, :active, :order)", array(
                            'object' => $aObject['object'], 
                            'name' => $aField['name'],
                            'direction' => $aField['direction'],
                            'caption' => isset($aField['caption']) ? $aField['caption'] : 'none',
                            'active' => 1,
                            'order' => $iOrder++
                        )) > 0;
                }
            }    
        }
        return $aFields;
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

            $oLanguage = BxDolStudioLanguagesUtils::getInstance();
            $aLangIds = array_keys($oLanguage->getLanguages(true));

            $iOrder = 0;
            foreach($aFields as $sField => $aField) {
                $iNow = time();
                $sCaptionKey = (!empty($aField['caption']) ? $aField['caption'] : '_sys_form_input_' . $sField) . '_' . $iNow;
                $sInfoKey = (!empty($aField['info']) ? $aField['info'] : '_sys_form_input_' . $sField . '_info') . '_' . $iNow;
                $sSearchType = isset($aField['search_type']) ? $aField['search_type'] : reset(BxDolSearchExtended::$TYPE_TO_TYPE_SEARCH[$aField['type']]);
                $sSearchValue =  isset($aField['search_value']) ? $aField['search_value'] : (in_array($aField['type'], array('checkbox', 'switcher')) ? $aField['value'] : '');
                if ($sSearchType == 'datepicker_range_age' || $sSearchType == 'datepicker_range'){
                    $sSearchValue =  BxDolService::getSerializedService($aObject['module'], 'get_search_options', array($sField, $aField['type'], $sSearchType));
                }
                $iActive = isset($aField['active']) ? (int)$aField['active'] : 1;

                $bResult = (int)$oDb->query("INSERT INTO `sys_search_extended_fields`(`object`, `name`, `type`, `caption`, `info`, `values`, `pass`, `search_type`, `search_value`, `search_operator`, `active`, `order`) VALUES(:object, :name, :type, :caption, :info, :values, :pass, :search_type, :search_value, :search_operator, :active, :order)", array(
                    'object' => $aObject['object'], 
                    'name' => $sField,
                    'type' => $aField['type'],
                    'caption' => $sCaptionKey,
                    'info' => $sInfoKey,
                    'values' => $aField['values'],
                    'pass' => $aField['pass'],
                    'search_type' => $sSearchType,
                    'search_value' => $sSearchValue, 
                    'search_operator' => isset($aField['search_operator']) ? $aField['search_operator'] : reset(BxDolSearchExtended::$TYPE_TO_OPERATOR[$aField['type']]),
                    'active' => $iActive,
                    'order' => $iOrder++
                )) > 0;

                if($bResult) {
                    $aCaptionValues = !empty($aField['caption']) ? $oLanguage->getLanguageString($aField['caption']) : array();
                    $aCaptionSystemValues = !empty($aField['caption_system']) ? $oLanguage->getLanguageString($aField['caption_system']) : array();
                    $aInfoValues = !empty($aField['info']) ? $oLanguage->getLanguageString($aField['info']) : array();

                    foreach($aLangIds as $iLangId) {
                        $sCaptionValue = '';
                        if(isset($aCaptionValues[$iLangId]) && !empty($aCaptionValues[$iLangId]['string']))
                            $sCaptionValue = $aCaptionValues[$iLangId]['string'];
                        if(empty($sCaptionValue) && isset($aCaptionSystemValues[$iLangId]) && !empty($aCaptionSystemValues[$iLangId]['string']))
                            $sCaptionValue = $aCaptionSystemValues[$iLangId]['string'];
                        $oLanguage->addLanguageString($sCaptionKey, $sCaptionValue, $iLangId, 0, false);

                        $sInfoValue = '';
                        if(isset($aInfoValues[$iLangId]) && !empty($aInfoValues[$iLangId]['string']))
                            $sInfoValue = $aInfoValues[$iLangId]['string'];
                        $oLanguage->addLanguageString($sInfoKey, $sInfoValue, $iLangId, 0, false);
                    }
                }
            }

            $oLanguage->compileLanguage(0, true);

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
                else if(BxDolService::isSerializedService($aField['values']))
                    $aFields[$iIndex]['values'] = BxDolService::callSerialized($aField['values']);
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

        $sWhereClause = $this->arrayToSQL($aWhere, ' AND ');

        $aFields = $this->getAll("SELECT * FROM `sys_search_extended_fields` WHERE " . $sWhereClause);
        if(!empty($aFields) && is_array($aFields)) {
            $oLanguage = BxDolStudioLanguagesUtils::getInstance();

            foreach($aFields as $aField) {
                if(!empty($aField['caption']))
                    $oLanguage->deleteLanguageString($aField['caption'], 0, false);

                if(!empty($aField['info']))
                    $oLanguage->deleteLanguageString($aField['info'], 0, false);
            }

            $oLanguage->compileLanguage(0, true);
        }

        return $this->query("DELETE FROM `sys_search_extended_fields` WHERE " . $sWhereClause);
    }
    
    public function deleteSortableFields($aWhere)
    {
        if(empty($aWhere))
    		return false;

        $sWhereClause = $this->arrayToSQL($aWhere, ' AND ');
        
        return $this->query("DELETE FROM `sys_search_extended_sorting_fields` WHERE " . $sWhereClause);
    }
}

/** @} */
