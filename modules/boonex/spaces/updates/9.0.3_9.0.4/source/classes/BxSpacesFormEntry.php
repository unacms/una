<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit Space Form.
 */
class BxSpacesFormEntry extends BxBaseModGroupsFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_spaces';
        parent::__construct($aInfo, $oTemplate);
    }
    
    protected function genCustomInputParentSpace($aInput)
    {
        $iCurrent = bx_get('id');
        if ($iCurrent === false)
            $iCurrent = - 1;
        $aInput['ajax_get_suggestions'] = BX_DOL_URL_ROOT . "modules/?r=" . $this->_oModule->_oConfig->getUri() . "/ajax_get_parent_space&id=" . $iCurrent;
        $aInput['custom']['only_once'] = 1;
        if (isset($aInput['value']) && !is_array($aInput['value']))
            $aInput['value'] = array($aInput['value']);
        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
    
    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $this->defineLevelById($aValsToAdd);
        return parent::insert ($aValsToAdd, $isIgnore);
    }

    function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $this->defineLevelById($aValsToAdd);
        return parent::update ($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);
    }
    
    function defineLevelById(&$aValsToAdd)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iParentId = 0;
        if(isset($this->aInputs[$CNF['FIELD_PARENT']])){
            $iParentId = $this->aInputs[$CNF['FIELD_PARENT']]['value'];
            if(is_array($iParentId))
                $iParentId = array_shift($iParentId);
        }
        $aValsToAdd[$CNF['FIELD_PARENT']] = $iParentId;

        if(isset($CNF['FIELD_LEVEL']) && empty($aValsToAdd[$CNF['FIELD_LEVEL']])) {
            $aValsToAdd[$CNF['FIELD_LEVEL']] = 0;
            if(!empty($iParentId)) {
                $oParent = BxDolProfile::getInstance($iParentId);
                if($oParent)
                    $aValsToAdd[$CNF['FIELD_LEVEL']] = $this->_oModule->_oDb->getLevelById($oParent->getContentId()) + 1;
            }
        }
    }
}

/** @} */
