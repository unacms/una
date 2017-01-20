<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Create/Edit entry form
 */
class BxPollsFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_polls';
        parent::__construct($aInfo, $oTemplate);
    }

    public function getCode($bDynamicMode = false)
    {
        $this->_oModule->_oTemplate->addJs(array('form.js'));
        return $this->_oModule->_oTemplate->getJsCode('form') . parent::getCode($bDynamicMode);
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_SUBENTRIES']]) && !empty($aValues['id'])) {
            $this->aInputs[$CNF['FIELD_SUBENTRIES']]['values'] = $this->_oModule->_oDb->getSubentries(array(
                'type' => 'entry_id_pairs',
                'entry_id' => $aValues['id']
            ));
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iContentId = parent::insert($aValsToAdd, $isIgnore);
        if(!empty($iContentId))
            $this->processSubentriesAdd($CNF['FIELD_SUBENTRIES'], $iContentId);

        return $iContentId;
    }

    public function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iResult = parent::update($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        $this->processSubentriesUpdate($CNF['FIELD_SUBENTRIES'], $iContentId);

        return $iResult;
    }

    public function processSubentriesAdd ($sField, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($this->aInputs[$sField]))
            return true;

        $aSubentries = $this->getCleanValue($sField);
        if(empty($aSubentries) || !is_array($aSubentries))
            return true;

        foreach($aSubentries as $sSubentry)
            if(!empty($sSubentry))
                $this->_oModule->_oDb->insertSubentry(array(
                    'entry_id' => $iContentId,
                    'title' => bx_process_input($sSubentry)
                ));

        return true;
    }

    public function processSubentriesUpdate($sField, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($this->aInputs[$sField]))
            return true;

        //--- Update existed and remove empty
        $aSubentriesIds = $this->getCleanValue($sField . '_ids');
        $aSubentriesValues = $this->getCleanValue($sField);
        foreach($aSubentriesIds as $iIndex => $iId)
            if(!empty($aSubentriesValues[$iIndex]))
                $this->_oModule->_oDb->updateSubentry(array('title' => bx_process_input($aSubentriesValues[$iIndex])), array('id' => (int)$iId));
            else 
                $this->_oModule->_oDb->deleteSubentry(array('id' => (int)$iId));

        //--- Add new
        $iSubentriesIds = count($aSubentriesIds);
        $iSubentriesValues = count($aSubentriesValues);
        if($iSubentriesValues > $iSubentriesIds) {
            $aSubentriesValues = array_slice($aSubentriesValues, $iSubentriesIds);
            foreach($aSubentriesValues as $sSubentriesValue)
                if(!empty($sSubentriesValue))
                    $this->_oModule->_oDb->insertSubentry(array(
                        'entry_id' => $iContentId,
                        'title' => bx_process_input($sSubentriesValue)
                    ));
        }

        return true;
    }

    protected function genCustomInputSubentries(&$aInput)
    {
        $sResult = '';

        if(empty($aInput['values']) || !is_array($aInput['values']))
            $sResult .= $this->genCustomInputSubentriesText($aInput);
        else
            foreach($aInput['values'] as $iId => $sValue) {
                $sResult .= $this->genCustomInputSubentriesText($aInput, $sValue);
                $sResult .= $this->genCustomInputSubentriesHidden($aInput, $iId);
            }

        $sResult .= $this->genCustomInputSubentriesButton($aInput);

        return $sResult;
    }
    
    protected function genCustomInputSubentriesText($aInput, $mixedValue = '')
    {
        $aInput['type'] = 'text';
        $aInput['name'] .= '[]';
        $aInput['value'] = $mixedValue;
        $aInput['attrs']['class'] = 'bx-def-margin-sec-top-auto';
        
        return $this->genInput($aInput);
    }

    protected function genCustomInputSubentriesHidden($aInput, $mixedValue = '')
    {
        $aInput['type'] = 'hidden';
        $aInput['name'] .= '_ids[]';
        $aInput['value'] = $mixedValue;

        return $this->genInput($aInput);
    }

    protected function genCustomInputSubentriesButton($aInput)
    {
        $aInput['type'] = 'button';
        $aInput['name'] .= '_add';
        $aInput['value'] = _t('_bx_polls_form_entry_input_subentries_add');
        $aInput['attrs']['class'] = 'bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('form') . '.addMore(this);';

        return $this->genInputButton($aInput);
    }
}

/** @} */
