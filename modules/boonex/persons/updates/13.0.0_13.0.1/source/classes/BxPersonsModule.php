<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Person profiles module.
 */
class BxPersonsModule extends BxBaseModProfileModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_aSearchableNamesExcept[] = $this->_oConfig->CNF['FIELD_AUTHOR'];
    }

    public function servicePrepareFields ($aFieldsProfile)
    {
        $a = parent::_servicePrepareFields($aFieldsProfile, array(), array());
        if (empty($a['fullname']))
            $a['fullname'] = $aFieldsProfile['name'];
        return $a;
    }
    
    /**
     * @page service Service Calls
     * @section bx_persons Persons 
     * @subsection bx_persons-other Other
     * @subsubsection bx_persons-get_search_options
     * 
     * @code bx_srv('bx_persons', 'get_search_options', [...]); @endcode
     * 
     * Get options for searchable fields
     * 
     * @return array - params or false.
     * 
     * @see BxPersonsModule::serviceGetSearchOptions
     */
    /** 
     * @ref bx_persons-get_search_options "get_search_options"
     */
    public function serviceGetSearchOptions ($sField, $sFieldType, $sSearchType)
    {
        $CNF = $this->_oConfig->CNF;
        if (isset($CNF['OBJECT_FORM_ENTRY']) && isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'])){
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oTemplate);
            foreach ($oForm->aInputs as $aFld) {
                if ($aFld['name'] == $sField && $aFld['type'] == $sFieldType && isset($aFld['checker']) && isset($aFld['checker']['params'])){
                    return $aFld['checker']['params'];
                }
            }
        }
        
        if (isset($CNF['OBJECT_FORM_ENTRY']) && isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'])){
            $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT'], $this->_oTemplate);
            foreach ($oForm->aInputs as $aFld) {
                if ($aFld['name'] == $sField && $aFld['type'] == $sFieldType && isset($aFld['checker']) && isset($aFld['checker']['params'])){
                    return $aFld['checker']['params'];
                }
            }
        }
        
        return false;
    }
    
    public function getProfileName ($aContentInfo)
    {
        return bx_process_output($aContentInfo[$this->_oConfig->CNF['FIELD_NAME']] . ($aContentInfo[$this->_oConfig->CNF['FIELD_LAST_NAME']] != '' ? ' ' . $aContentInfo[$this->_oConfig->CNF['FIELD_LAST_NAME']] : ''));
    }
    
}

/** @} */
