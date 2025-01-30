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

class BxPollsFormEntryCheckerHelper extends BxDolFormCheckerHelper
{
    static public function checkAvailSubentries ($s)
    {
        return bx_is_api() ? self::checkAvail($s) : !self::_isEmptyArray($s) && count($s) >= 2;
    }
}
/**
 * Create/Edit entry form
 */
class BxPollsFormEntry extends BxBaseModTextFormEntry
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_polls';
        $this->_aFieldsCheckForSpam = array('subentries');
        parent::__construct($aInfo, $oTemplate);
    }

    public function getCode($bDynamicMode = false)
    {
        $sJs = $this->_oModule->_oTemplate->addJs(array('form.js'), $bDynamicMode);

        $sCode = '';
        if($bDynamicMode)
        	$sCode .= $sJs;

		$sCode .= $this->_oModule->_oTemplate->getJsCode('form');
		$sCode .= parent::getCode($bDynamicMode);

        return $sCode;
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_SUBENTRIES']]) && !empty($aValues['id'])) {
            $aSubentries = $this->_oModule->_oDb->getSubentries(array(
                'type' => 'entry_id_pairs',
                'entry_id' => $aValues['id']
            ));

            $this->aInputs[$CNF['FIELD_SUBENTRIES']]['value'] = array_values($aSubentries);
            $this->aInputs[$CNF['FIELD_SUBENTRIES']]['value_ids'] = array_keys($aSubentries);
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

    public function getCleanValue ($sName)
    {
        $mixedValue = parent::getCleanValue($sName);
        
        if($this->_bIsApi && !empty($mixedValue) && in_array($sName, ['subentries', 'subentries_ids']))
            $mixedValue = explode(',', $mixedValue);

        return $mixedValue;
    }

    public function processSubentriesAdd ($sField, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($this->aInputs[$sField]))
            return true;

        $aSubentries = $this->getCleanValue($sField);
        if(empty($aSubentries) || !is_array($aSubentries))
            return true;

        foreach($aSubentries as $iIndex => $sSubentry)
            if($sSubentry != '' && get_mb_len($sSubentry) > 0)
                $this->_oModule->_oDb->insertSubentry(array(
                    'entry_id' => $iContentId,
                    'title' => bx_process_input($sSubentry),
                    'order' => $iIndex
                ));

        return true;
    }

    public function processSubentriesUpdate($sField, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($this->aInputs[$sField]))
            return true;

        $aSubentriesIds = $this->getCleanValue($sField . '_ids');
        $aSubentriesValues = $this->getCleanValue($sField);

        //--- Remove deleted
        $aSubentriesDb = $this->_oModule->_oDb->getSubentries(array('type' => 'entry_id_pairs', 'entry_id' => $iContentId));
        $this->_oModule->_oDb->deleteSubentryById(array_diff(array_keys($aSubentriesDb), $aSubentriesIds));

        //--- Update existed and remove empty
        foreach($aSubentriesIds as $iIndex => $iId)
            if($aSubentriesValues[$iIndex] != '' && get_mb_len($aSubentriesValues[$iIndex]) > 0)
                $this->_oModule->_oDb->updateSubentry(array('title' => bx_process_input($aSubentriesValues[$iIndex])), array('id' => (int)$iId));
            else 
                $this->_oModule->_oDb->deleteSubentry(array('id' => (int)$iId));

        //--- Add new
        $iSubentriesIds = count($aSubentriesIds);
        $iSubentriesValues = count($aSubentriesValues);
        if($iSubentriesValues > $iSubentriesIds) {
            $iMaxOrder = (int)$this->_oModule->_oDb->getSubentries(array('type' => 'entry_id_max_order', 'entry_id' => $iContentId));

            $aSubentriesValues = array_slice($aSubentriesValues, $iSubentriesIds);
            foreach($aSubentriesValues as $sSubentriesValue)
                if($sSubentriesValue != '' && get_mb_len($sSubentriesValue) > 0)
                    $this->_oModule->_oDb->insertSubentry(array(
                        'entry_id' => $iContentId,
                        'title' => bx_process_input($sSubentriesValue),
                        'order' => ++$iMaxOrder
                    ));
        }

        return true;
    }

    protected function genCustomInputSubentries(&$aInput)
    {
        if($this->_bIsApi)
            return array_merge($aInput, [
                'type' => 'multi_field',
                'subtype' => 'text'
            ]);

        $sJsObject = $this->_oModule->_oConfig->getJsObject('form');

        if(!empty($aInput['value']) && is_array($aInput['value'])) {
            $aTmplVarsSubentries = array();
            foreach($aInput['value'] as $iKey => $sValue) {
                $sInput = $this->genCustomInputSubentriesText($aInput, $sValue);
                if(!empty($aInput['value_ids'][$iKey]))
                    $sInput .= $this->genCustomInputSubentriesHidden($aInput, (int)$aInput['value_ids'][$iKey]);

                $aTmplVarsSubentries[] = array('js_object' => $sJsObject, 'input_text' => $sInput);
            }
        }
        else 
            $aTmplVarsSubentries = array(
                array('js_object' => $sJsObject, 'input_text' => $this->genCustomInputSubentriesText($aInput)),
                array('js_object' => $sJsObject, 'input_text' => $this->genCustomInputSubentriesText($aInput))
            );

        return $this->_oModule->_oTemplate->parseHtmlByName('form_subentries.html', array(
            'bx_repeat:subentries' => $aTmplVarsSubentries,
            'btn_add' => $this->genCustomInputSubentriesButton($aInput)
        ));
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
        $sName = $aInput['name'];

        $aInput['type'] = 'button';
        $aInput['name'] .= '_add';
        $aInput['value'] = _t('_bx_polls_form_entry_input_subentries_add');
        $aInput['attrs']['class'] = 'bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('form') . ".subentryAdd(this, '" . $sName . "');";

        return $this->genInputButton($aInput);
    }
}

/** @} */
