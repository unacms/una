<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Events Events
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Create/Edit Group Form.
 */
class BxEventsFormEntry extends BxBaseModGroupsFormEntry
{
    protected $_iContentId = 0;

    public function __construct($aInfo, $oTemplate = false)
    {
        $this->MODULE = 'bx_events';
        parent::__construct($aInfo, $oTemplate);

        if (isset($this->aInputs['reoccurring'])) {
            $this->aInputs['reoccurring']['ghost_template'] = array (
                
                'params' => array(
                    'nested_form_template' => 'form_ghost_template_wrapper.html',
                    'db' => array(
                        'table' => 'bx_events_intervals',
                        'key' => 'interval_id',
                        'submit_name' => 'interval_id',
                    ),
                ),
                'inputs' => array (
                    'interval_id' => array (
                        'type' => 'hidden',
                        'name' => 'interval_id[]',
                        'value' => '{interval_id}',
                        'caption' => _t('Interval ID'),
                        'db' => array (
                            'pass' => 'Int',
                        ),
                    ),                
                    'repeat_year' => array (
                        'type' => 'select',                    
                        'name' => 'repeat_year[]',
                        'value' => '{repeat_year}',
                        'values' => BxDolForm::getDataItems('bx_events_repeat_year'),
                        'caption' => _t('_bx_events_form_input_repeat_year'),
                        'db' => array (
                            'pass' => 'Int',
                        ),
                    ),
                    'repeat_month' => array (
                        'type' => 'select',
                        'name' => 'repeat_month[]',
                        'value' => '{repeat_month}',
                        'values' => BxDolForm::getDataItems('bx_events_repeat_month'),
                        'caption' => _t('_bx_events_form_input_repeat_month'),
                        'db' => array (
                            'pass' => 'Int',
                        ),
                    ),
                    'repeat_week_of_month' => array (
                        'type' => 'select',
                        'name' => 'repeat_week_of_month[]',
                        'value' => '{repeat_week_of_month}',
                        'values' => BxDolForm::getDataItems('bx_events_repeat_week_of_month'),
                        'caption' => _t('_bx_events_form_input_repeat_week_of_month'),
                        'db' => array (
                            'pass' => 'Int',
                        ),
                    ),                
                    'repeat_day_of_month' => array (
                        'type' => 'select',
                        'name' => 'repeat_day_of_month[]',
                        'value' => '{repeat_day_of_month}',
                        'values' => BxDolForm::getDataItems('bx_events_repeat_day_of_month'),
                        'caption' => _t('_bx_events_form_input_repeat_day_of_month'),
                        'db' => array (
                            'pass' => 'Int',
                        ),
                    ),
                    'repeat_day_of_week' => array (
                        'type' => 'select',
                        'name' => 'repeat_day_of_week[]',
                        'value' => '{repeat_day_of_week}',
                        'values' => BxDolForm::getDataItems('bx_events_repeat_day_of_week'),
                        'caption' => _t('_bx_events_form_input_repeat_day_of_week'),
                        'db' => array (
                            'pass' => 'Int',
                        ),
                    ),
                    'repeat_stop' => array (
                        'type' => 'datepicker',
                        'name' => 'repeat_stop[]',
                        'value' => '{repeat_stop}',
                        'caption' => _t('_bx_events_form_input_stop_repeating'),
                        'db' => array (
                            'pass' => 'Date',
                        ),
                    ),
                ),
            );
        }

        if (isset($this->aInputs['timezone'])) {
            $this->aInputs['timezone']['values'] = array_combine(timezone_identifiers_list(), timezone_identifiers_list());
        }
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        if (isset($aValues[$this->_oModule->_oConfig->CNF['FIELD_ID']]))
            $this->_iContentId = $aValues[$this->_oModule->_oConfig->CNF['FIELD_ID']];

        parent::initChecker($aValues, $aSpecificValues);
    }
    
    protected function genCustomRowReoccurring (&$aInput)
    {
        return $this->genRowCustom($aInput, 'genInputReoccurring');
    }

    protected function genInputReoccurring (&$aInput, $sInfo = '', $sError = '')
    {
        $sUniqId = genRndPwd (8, false);

        return $this->oTemplate->parseHtmlByName('form_field_reoccurring.html', array(
            'info' => $sInfo,
            'error' => $sError,
            'uniq_id' => $sUniqId,
            'js_instance_name' => 'oBxEventsIntervals_' . $sUniqId,
            'options' => json_encode(array(
                'uniq_id' => $sUniqId,
                'js_instance_name' => 'oBxEventsIntervals_' . $sUniqId,
                'template_ghost' => $this->genGhostTemplate($aInput),
                'content_id' => $this->_iContentId, 
            )),
        ));
    }

    public function insert ($aValsToAdd = array(), $isIgnore = false)
    {
        $iContentId = parent::insert ($aValsToAdd, $isIgnore);

        if (isset($this->aInputs['reoccurring']) && is_array($this->aInputs['reoccurring']['ghost_template']) && !isset($this->aInputs['reoccurring']['ghost_template']['inputs'])) {
            foreach ($this->aInputs['reoccurring']['ghost_template'] as $oFormNested) {
                $oFormNested->insert(array('event_id' => $iContentId));
            }
        }
        
        return $iContentId;
    }

    function update ($val, $aValsToAdd = array(), &$aTrackTextFieldsChanges = null)
    {
        $iAffectedRows = parent::update($val, $aValsToAdd, $aTrackTextFieldsChanges);

        if (isset($this->aInputs['reoccurring']) && is_array($this->aInputs['reoccurring']['ghost_template']) && !isset($this->aInputs['reoccurring']['ghost_template']['inputs'])) {
            foreach ($this->aInputs['reoccurring']['ghost_template'] as $oFormNested) {
                $aSpecificValues = $oFormNested->getSpecificValues();
                $iIntervalId = $oFormNested->getSubmittedValue('interval_id', BX_DOL_FORM_METHOD_SPECIFIC, $aSpecificValues);
                if ($iIntervalId) {
                    $oFormNested->update($iIntervalId);
                } else {
                    $oFormNested->insert(array('event_id' => $this->_iContentId));
                }
            }
        }
        
        return $iAffectedRows;
    }
    
    function addCssJs ()
    {
        if (!self::$_isCssJsAdded)
            $this->oTemplate->addJs('intervals.js');

        parent::addCssJs ();
    }
}

/** @} */
