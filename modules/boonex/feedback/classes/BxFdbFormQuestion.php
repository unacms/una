<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Feedback Feedback
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFdbFormQuestionCheckerHelper extends BxDolFormCheckerHelper
{
    static public function checkAvailAnswers ($s)
    {
        $iCountMin = 2;

        if(self::_isEmptyArray($s) || count($s) < $iCountMin)
            return false;

        $iCount = 0;
        foreach($s as $k => $v)
            if(!empty($v))
                $iCount += 1;

        return $iCount >= $iCountMin;
    }
}

class BxFdbFormQuestion extends BxBaseModTextFormEntry
{
    protected $_sJsObject;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_feedback';

        parent::__construct($aInfo, $oTemplate);

        $this->_sJsObject = $this->_oModule->_oConfig->getJsObject('question');
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(isset($this->aInputs[$CNF['FIELD_ANSWERS']]) && !empty($aValues['id'])) {
            $aAnswers = $this->_oModule->_oDb->getAnswers(array(
                'type' => 'question_id',
                'question_id' => $aValues['id']
            ));

            $this->aInputs[$CNF['FIELD_ANSWERS']] = array_merge($this->aInputs[$CNF['FIELD_ANSWERS']], array(
                'value' => array(),
                'value_ids' => array(),
                'value_imps' => array()
            ));

            foreach($aAnswers as $aAnswer) {
                $this->aInputs[$CNF['FIELD_ANSWERS']]['value'][] = $aAnswer['title'];
                $this->aInputs[$CNF['FIELD_ANSWERS']]['value_ids'][] = $aAnswer['id'];
                if((int)$aAnswer['important'] == 1)
                    $this->aInputs[$CNF['FIELD_ANSWERS']]['value_imps'][] = $aAnswer['title'];
            }
        }

        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iContentId = parent::insert($aValsToAdd, $isIgnore);
        if(!empty($iContentId))
            $this->processAnswersAdd($CNF['FIELD_ANSWERS'], $iContentId);

        return $iContentId;
    }

    public function update ($iContentId, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $iResult = parent::update($iContentId, $aValsToAdd, $aTrackTextFieldsChanges);

        $this->processAnswersUpdate($CNF['FIELD_ANSWERS'], $iContentId);

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

        $aAnswersImps = $this->getCleanValue($sField . '_imps');
        $bAnswersImps = !empty($aAnswersImps) && is_array($aAnswersImps);

        foreach($aAnswers as $iIndex => $sAnswer)
            if($sAnswer != '' && get_mb_len($sAnswer) > 0)
                $this->_oModule->_oDb->insertAnswer(array(
                    'question_id' => $iContentId,
                    'title' => bx_process_input($sAnswer),
                    'important' => $bAnswersImps && in_array($sAnswer, $aAnswersImps) ? 1 : 0,
                    'order' => $iIndex
                ));

        return true;
    }

    public function processAnswersUpdate($sField, $iContentId = 0)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!isset($this->aInputs[$sField]))
            return true;

        $aAnswersValues = $this->getCleanValue($sField);

        $aAnswersIds = $this->getCleanValue($sField . '_ids');
        if(empty($aAnswersIds) || !is_array($aAnswersIds))
            $aAnswersIds = array();

        $aAnswersImps = $this->getCleanValue($sField . '_imps');
        if(empty($aAnswersImps) || !is_array($aAnswersImps))
            $aAnswersImps = array();

        //--- Remove deleted
        $aAnswersDb = $this->_oModule->_oDb->getAnswers(array('type' => 'question_id_pairs', 'question_id' => $iContentId));
        $this->_oModule->_oDb->deleteAnswerById(array_diff(array_keys($aAnswersDb), $aAnswersIds));

        //--- Update existed and remove empty
        foreach($aAnswersIds as $iIndex => $iAnswerId) {
            $sAnswersValue = $aAnswersValues[$iIndex];

            if($sAnswersValue != '' && get_mb_len($sAnswersValue) > 0)
                $this->_oModule->_oDb->updateAnswer(array(
                    'title' => bx_process_input($sAnswersValue), 
                    'important' => in_array($sAnswersValue, $aAnswersImps) ? 1 : 0
                ), array('id' => (int)$iAnswerId));
            else 
                $this->_oModule->_oDb->deleteAnswer(array('id' => (int)$iAnswerId));
        }

        //--- Add new
        $iAnswersIds = count($aAnswersIds);
        $iAnswersValues = count($aAnswersValues);
        if($iAnswersValues > $iAnswersIds) {
            $iMaxOrder = (int)$this->_oModule->_oDb->getAnswers(array('type' => 'question_id_max_order', 'question_id' => $iContentId));

            $aAnswersValues = array_slice($aAnswersValues, $iAnswersIds);
            foreach($aAnswersValues as $sAnswersValue)
                if($sAnswersValue != '' && get_mb_len($sAnswersValue) > 0)
                    $this->_oModule->_oDb->insertAnswer(array(
                        'question_id' => $iContentId,
                        'title' => bx_process_input($sAnswersValue),
                        'important' => in_array($sAnswersValue, $aAnswersImps) ? 1 : 0,
                        'order' => ++$iMaxOrder
                    ));
        }

        return true;
    }

    protected function genCustomInputAnswers(&$aInput)
    {
        if(!empty($aInput['value']) && is_array($aInput['value'])) {
            $aAnswersImp = array();
            if(!empty($aInput['value_imps']) && is_array($aInput['value_imps']))
                $aAnswersImp = $aInput['value_imps'];

            $aTmplVarsAnswers = array();
            foreach($aInput['value'] as $iIndex => $sValue) {
                $sInputText = $this->genCustomInputAnswersText($aInput, $sValue);
                if(!empty($aInput['value_ids'][$iIndex]))
                    $sInputText .= $this->genCustomInputAnswersHidden($aInput, (int)$aInput['value_ids'][$iIndex]);

                $sInputCheckbox = $this->genCustomInputAnswersCheckbox($aInput, $sValue, in_array($sValue, $aAnswersImp));

                $aTmplVarsAnswers[] = array(
                    'js_object' => $this->_sJsObject, 
                    'input_text' => $sInputText,
                    'input_checkbox' => $sInputCheckbox
                );
            }
        }
        else {
            $aAnswer = array(
                'js_object' => $this->_sJsObject, 
                'input_text' => $this->genCustomInputAnswersText($aInput),
                'input_checkbox' => $this->genCustomInputAnswersCheckbox($aInput)
            );

            $aTmplVarsAnswers = array($aAnswer, $aAnswer);
        }

        return $this->_oModule->_oTemplate->parseHtmlByName('form_answers.html', array(
            'bx_repeat:answers' => $aTmplVarsAnswers,
            'btn_add' => $this->genCustomInputAnswersButton($aInput)
        ));
    }

    protected function genCustomInputAnswersText($aInput, $mixedValue = '')
    {
        $aInput['type'] = 'text';
        $aInput['name'] .= '[]';
        $aInput['value'] = $mixedValue;
        
        if(!is_array($aInput['attrs']))
            $aInput['attrs'] = array();

        $sJsMethod = 'javascript:' . $this->_sJsObject . '.answerOnType(event)';
        $aInput['attrs'] = array_merge($aInput['attrs'], array(
            'class' => 'bx-def-margin-sec-top-auto',
            'onkeyup' => $sJsMethod,
            'onpaste' => $sJsMethod,
            'onblur' => $sJsMethod
        ));

        return $this->genInput($aInput);
    }

    protected function genCustomInputAnswersCheckbox($aInput, $mixedValue = '', $bChecked = false)
    {
        $aInput['type'] = 'checkbox';
        $aInput['name'] .= '_imps[]';
        $aInput['caption'] = _t('_bx_feedback_form_question_input_answers_imp');
        $aInput['value'] = $mixedValue;
        $aInput['required'] = 0;
        if($bChecked)
            $aInput['checked'] = 'checked';

        return $this->genRow($aInput);
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
        $sName = $aInput['name'];

        $aInput['type'] = 'button';
        $aInput['name'] .= '_add';
        $aInput['value'] = _t('_bx_feedback_form_question_input_answers_add');
        $aInput['attrs']['class'] = 'bx-def-margin-sec-top';
        $aInput['attrs']['onclick'] = $this->_oModule->_oConfig->getJsObject('question') . ".answerAdd(this, '" . $sName . "');";

        return $this->genInputButton($aInput);
    }
}

/** @} */
