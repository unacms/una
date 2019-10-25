<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModTextFormPoll extends BxTemplFormView
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
        
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $CNF = &$this->_oModule->_oConfig->CNF;
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_POLL_ANSWERS']]) && !empty($aValues['id'])) {
            $aAnswers = $this->_oModule->_oDb->getPollAnswers(array(
                'type' => 'poll_id_pairs',
                'poll_id' => $aValues['id']
            ));

            $this->aInputs[$CNF['FIELD_POLL_ANSWERS']]['value'] = array_values($aAnswers);
            $this->aInputs[$CNF['FIELD_POLL_ANSWERS']]['value_ids'] = array_keys($aAnswers);
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($aValsToAdd[$CNF['FIELD_POLL_AUTHOR_ID']]))
            $aValsToAdd[$CNF['FIELD_POLL_AUTHOR_ID']] = bx_get_logged_profile_id();

        $iContentId = parent::insert($aValsToAdd, $isIgnore);
        if(!empty($iContentId))
            $this->processAnswersAdd($CNF['FIELD_POLL_ANSWERS'], $iContentId);

        return $iContentId;
    }

    public function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iResult = parent::update($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        $this->processAnswersUpdate($CNF['FIELD_POLL_ANSWERS'], $iContentId);

        return $iResult;
    }

    public function processAnswersAdd ($sField, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($this->aInputs[$sField]))
            return true;

        $aAnswers = $this->getCleanValue($sField);
        if(empty($aAnswers) || !is_array($aAnswers))
            return true;

        foreach($aAnswers as $iIndex => $sAnswer)
            if($sAnswer != '' && get_mb_len($sAnswer) > 0)
                $this->_oModule->_oDb->insertPollAnswer(array(
                    'poll_id' => $iContentId,
                    'title' => bx_process_input($sAnswer),
                    'order' => $iIndex
                ));

        return true;
    }

    public function processAnswersUpdate($sField, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($this->aInputs[$sField]))
            return true;

        $aAnswersIds = $this->getCleanValue($sField . '_ids');
        $aAnswersValues = $this->getCleanValue($sField);

        //--- Remove deleted
        $aAnswersDb = $this->_oModule->_oDb->getPollAnswers(array('type' => 'poll_id_pairs', 'poll_id' => $iContentId));
        $this->_oModule->_oDb->deletePollAnswersByIds(array_diff(array_keys($aAnswersDb), $aAnswersIds));

        //--- Update existed and remove empty
        foreach($aAnswersIds as $iIndex => $iId)
            if($aAnswersValues[$iIndex] != '' && get_mb_len($aAnswersValues[$iIndex]) > 0)
                $this->_oModule->_oDb->updatePollAnswers(array('title' => bx_process_input($aAnswersValues[$iIndex])), array('id' => (int)$iId));
            else 
                $this->_oModule->_oDb->deletePollAnswers(array('id' => (int)$iId));

        //--- Add new
        $iAnswersIds = count($aAnswersIds);
        $iAnswersValues = count($aAnswersValues);
        if($iAnswersValues > $iAnswersIds) {
            $iMaxOrder = (int)$this->_oModule->_oDb->getPollAnswers(array('type' => 'poll_id_max_order', 'poll_id' => $iContentId));

            $aAnswersValues = array_slice($aAnswersValues, $iAnswersIds);
            foreach($aAnswersValues as $sAnswersValue)
                if($sAnswersValue != '' && get_mb_len($sAnswersValue) > 0)
                    $this->_oModule->_oDb->insertPollAnswer(array(
                        'poll_id' => $iContentId,
                        'title' => bx_process_input($sAnswersValue),
                        'order' => ++$iMaxOrder
                    ));
        }

        return true;
    }

    protected function genCustomInputAnswers(&$aInput)
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject('poll');

        $aTmplVarsAnswers = array(
            array('class' => 'bx-fi-answer-blank', 'js_object' => $sJsObject, 'input_text' => $this->genCustomInputAnswersText($aInput, '', true)),
        );

        if(!empty($aInput['value']) && is_array($aInput['value']))
            foreach($aInput['value'] as $iKey => $sValue) {
                $sInput = $this->genCustomInputAnswersText($aInput, $sValue);
                if(!empty($aInput['value_ids'][$iKey]))
                    $sInput .= $this->genCustomInputAnswersHidden($aInput, (int)$aInput['value_ids'][$iKey]);

                $aTmplVarsAnswers[] = array('class' => '', 'js_object' => $sJsObject, 'input_text' => $sInput);
            }
        else 
            $aTmplVarsAnswers = array_merge($aTmplVarsAnswers, array(
                array('class' => '', 'js_object' => $sJsObject, 'input_text' => $this->genCustomInputAnswersText($aInput)),
                array('class' => '', 'js_object' => $sJsObject, 'input_text' => $this->genCustomInputAnswersText($aInput))
            ));

        return $this->_oModule->_oTemplate->parseHtmlByName('poll_form_answers.html', array(
            'bx_repeat:answers' => $aTmplVarsAnswers,
            'btn_add' => $this->genCustomInputAnswersButton($aInput)
        ));
    }

    protected function genCustomInputAnswersText($aInput, $mixedValue = '', $bDisabled = false)
    {
        $aInput['type'] = 'text';
        $aInput['name'] .= '[]';
        $aInput['value'] = $mixedValue;

        $aInput['attrs']['class'] = 'bx-def-margin-sec-top-auto';
        if($bDisabled)
            $aInput['attrs']['disabled'] = 'disabled';

        return $this->genInput($aInput);
    }

    protected function genCustomInputAnswersHidden($aInput, $mixedValue = '')
    {
        $aInput['type'] = 'hidden';
        $aInput['name'] .= '_ids[]';
        $aInput['value'] = $mixedValue;

        return $this->genInput($aInput);
    }

    protected function genCustomInputAnswersButton($aInput)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sName = $aInput['name'];

        $aInput['type'] = 'button';
        $aInput['name'] .= '_add';
        $aInput['value'] = _t($CNF['T']['txt_poll_form_answers_add']);
        $aInput['attrs']['class'] = 'bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('poll') . ".addPollAnswer(this, '" . $sName . "');";

        return $this->genInputButton($aInput);
    }
}

class BxBaseModTextFormPollCheckerHelper extends BxDolFormCheckerHelper
{
    static public function checkAvailAnswers ($s)
    {
        return !self::_isEmptyArray($s) && count($s) >= 2;
    }
}

/** @} */
