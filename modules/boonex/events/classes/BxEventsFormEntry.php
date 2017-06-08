<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
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
                            'pass' => 'DateTs',
                        ),
                    ),
                ),
            );
        }

        if (isset($this->aInputs['timezone']) && !$this->aParams['view_mode']) {
            $this->aInputs['timezone']['values'] = array_combine(timezone_identifiers_list(), timezone_identifiers_list());
        }        
    }

    function initChecker ($aValues = array (), $aSpecificValues = array())
    {
        if (isset($aValues[$this->_oModule->_oConfig->CNF['FIELD_ID']]))
            $this->_iContentId = $aValues[$this->_oModule->_oConfig->CNF['FIELD_ID']];

        parent::initChecker($aValues, $aSpecificValues);
    }

    protected function genCustomRowDateEnd (&$aInput)
    {
        if ($this->aParams['view_mode'])
            return $this->_isSameDayEvent() ? '' : $this->genViewRowWrapped($aInput);

        return $this->genRowStandard($aInput);
    }

    protected function _isSameDayEvent()
    {
        if (false === ($aStartEnd = $this->_getStartEnd()))
            return false;
        else
            return $aStartEnd['start']->format('Y-m-d') == $aStartEnd['end']->format('Y-m-d');
    }

    protected function _getStartEnd()
    {
        if (!isset($this->aInputs['timezone']['value']) || !isset($this->aInputs['date_start']['value']) || !isset($this->aInputs['date_end']['value']))
            return false;

        $iTimeStart = bx_process_input ($this->aInputs['date_start']['value'], isset($this->aInputs['date_start']['date_filter']) ? $this->aInputs['date_start']['date_filter'] : BX_DATA_DATETIME_TS, false, false);
        $iTimeEnd = bx_process_input ($this->aInputs['date_end']['value'], isset($this->aInputs['date_end']['date_filter']) ? $this->aInputs['date_end']['date_filter'] : BX_DATA_DATETIME_TS, false, false);
        $oDateStart = date_create(date('Y-m-d H:i:s', $iTimeStart), new DateTimeZone($this->aInputs['timezone']['value'] ? $this->aInputs['timezone']['value'] : 'UTC'));
        $oDateEnd = date_create(date('Y-m-d H:i:s', $iTimeEnd), new DateTimeZone($this->aInputs['timezone']['value'] ? $this->aInputs['timezone']['value'] : 'UTC'));

        return array('start' => $oDateStart, 'end' => $oDateEnd);
    }
    
    protected function genCustomRowTime (&$aInput)
    {
        if (!$this->aParams['view_mode'])
            return '';

        if (false === ($aStartEnd = $this->_getStartEnd())) {
            $aInput['value'] = "Timezone, date start & date end fields are required to display this field";
        }
        else {            
            if ($aStartEnd['start']->format('Y-m-d') == $aStartEnd['end']->format('Y-m-d'))
                $aInput['value'] = _t('_bx_events_txt_from_time_to_time', $aStartEnd['start']->format(getParam('bx_events_time_format')), $aStartEnd['end']->format(getParam('bx_events_time_format')));
            else
                $aInput['value'] = '';
        }
        return $this->genViewRowWrapped($aInput);
    }
 
    function genViewRowValue(&$aInput)
    {
        if (in_array($aInput['name'], array('date_start', 'date_end'))) {

            if (false === ($aStartEnd = $this->_getStartEnd()))
                return "Timezone, date start & date end fields are required to display this field";
        
            $sFormat = $aStartEnd['start']->format('Y-m-d') == $aStartEnd['end']->format('Y-m-d') ? BX_FORMAT_DATE : BX_FORMAT_DATE_TIME;
            $oDate = 'date_start' == $aInput['name'] ? $aStartEnd['start'] : $aStartEnd['end'];
            return bx_time_js ($oDate->getTimestamp(), $sFormat, true);
        }
        return parent::genViewRowValue($aInput);
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
        if (!self::$_isCssJsAdded) {
            $this->oTemplate->addJs('intervals.js');
            $this->oTemplate->addCss('informer.css');
        }

        parent::addCssJs ();
    }
}

/** @} */
