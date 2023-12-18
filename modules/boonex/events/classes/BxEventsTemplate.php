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

/*
 * Groups module representation.
 */
class BxEventsTemplate extends BxBaseModGroupsTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_events';
        parent::__construct($oConfig, $oDb);
    }

    public function getBlockSessions($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aSessions = $this->_oDb->getSessions(['sample' => 'event_id', 'event_id' => $iContentId, 'order_by' => $this->_bIsApi ? 'date_start' : '']);
        if(empty($aSessions) || !is_array($aSessions))
            return '';

        $sFormatDateTime = getParam($CNF['PARAM_FORMAT_DATETIME']);
        $sFormatDate = getParam($CNF['PARAM_FORMAT_DATE']);
        $sFormatTime = getParam($CNF['PARAM_FORMAT_TIME']);

        $aTmplVarsSession = [];
        foreach($aSessions as $aSession) {
            $sDate = '';
            if(date('d', $aSession['date_start']) == date('d', $aSession['date_end']))
                $sDate = date($sFormatDate, $aSession['date_start']) . ' ' . date($sFormatTime, $aSession['date_start']) . ' - ' . date($sFormatTime, $aSession['date_end']);
            else
                $sDate = date($sFormatDateTime, $aSession['date_start']) . ' - ' . date($sFormatDateTime, $aSession['date_end']);

            $aTmplVarsSession[] = [
                'title' => bx_process_output($aSession['title']),
                'date_time' =>  $sDate,
            ];
        }

        $s = $this->parseHtmlByName('entry-sessions.html', [
            'bx_repeat:sessions' => $aTmplVarsSession
        ]);

        if($this->_bIsApi)
            return [bx_api_get_block('event_sessions',  $aTmplVarsSession)];

        return $s;
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $mixedTemplate = false, $aParams = array())
    {
        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $mixedTemplate, $aParams);

        $CNF = &$this->_oConfig->CNF;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);

        $aContentInfo = $this->getModule()->_oDb->getContentInfoById($aData[$CNF['FIELD_ID']]);

        $oDateStart = date_create('@' . $aContentInfo['date_start']);
        if ($oDateStart)
            $oDateStart->setTimezone(new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));

        $oDateEnd = date_create('@' . $aContentInfo['date_end']);
        if ($oDateEnd)
            $oDateEnd->setTimezone(new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));

        $isPublic = CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedView($aData) || $oPrivacy->isPartiallyVisible($aData[$CNF['FIELD_ALLOW_VIEW_TO']]);        
        if ($isPublic) {
            $aVars['bx_if:info']['content']['members'] = $oDateStart->format(getParam($CNF['PARAM_FORMAT_DATE']));
        }

        return array_merge($aVars, array(
			'date_start' => $aData['date_start'] ? $aData['date_start'] : '',
			'date_start_f' => $oDateStart ? bx_time_js($oDateStart->getTimestamp(), BX_FORMAT_DATE_TIME, true) : '',
			'date_end' => $aData['date_end'] ? $aData['date_end'] : '',
			'date_end_f' => $oDateEnd ? bx_time_js($oDateEnd->getTimestamp(), BX_FORMAT_DATE_TIME, true) : '',
        ));
    }

    protected function _getBrowsingFiltersContent($aParams)
    {
        $sJsObject = $this->_oConfig->getJsObject('main');

        $aForm = [
            'inputs' => [
                'by_date' => [
                    'name' => 'by_date',
                    'type' => 'radio_set',
                    'caption' => _t('_bx_events_form_filters_input_by_date'),
                    'values' => [
                        ['key' => 'all', 'value' => _t('_bx_events_form_filters_input_by_date_all')],
                        ['key' => 'today', 'value' => _t('_bx_events_form_filters_input_by_date_today')],
                        ['key' => 'tomorrow', 'value' => _t('_bx_events_form_filters_input_by_date_tomorrow')],
                        ['key' => 'weekend', 'value' => _t('_bx_events_form_filters_input_by_date_weekend')],
                        ['key' => 'this_week', 'value' => _t('_bx_events_form_filters_input_by_date_this_week')],
                        ['key' => 'next_week', 'value' => _t('_bx_events_form_filters_input_by_date_next_week')],
                        ['key' => 'this_month', 'value' => _t('_bx_events_form_filters_input_by_date_this_month')],
                        ['key' => 'date_range', 'value' => _t('_bx_events_form_filters_input_by_date_date_range')],
                        
                    ],
                    'value' => 'all',
                    'attrs' => ['onchange' => $sJsObject . '.onChangeBrowsingFiltersByDate(this)'],
                    'dv_thd' => 1
                ],
                'date_start' => [
                    'name' => 'date_start',
                    'type' => 'datepicker',
                    'tr_attrs' => ['class' => 'date-range date-start', 'style' => 'display:none']
                ],
                'date_end' => [
                    'name' => 'date_end',
                    'type' => 'datepicker',
                    'tr_attrs' => ['class' => 'date-range date-end', 'style' => 'display:none']
                ],
                'timezone' => [
                    'name' => 'timezone',
                    'type' => 'hidden',
                    'value' => ''
                ],
                'controls' => [
                    'name' => 'controls',
                    'type' => 'input_set',
                    [
                        'name' => 'apply',
                        'type' => 'button',
                        'value' => _t('_bx_events_form_filters_input_do_apply'),
                        'attrs' => ['onclick' => $sJsObject . '.applyBrowsingFilter(this, ' . json_encode($aParams) . ')']
                    ], [
                        'name' => 'cancel',
                        'type' => 'button',
                        'value' => _t('_Cancel'),
                        'attrs' => [
                            'class' => 'bx-def-margin-sec-left',
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()"
                        ]
                    ],
                    
                ],
                
            ]
        ];

        $oForm = new BxTemplFormView($aForm);
        return $this->addJs(['moment-timezone-with-data-1970-2030.min.js'], true) . $oForm->getCode(true);
    }
}

/** @} */
