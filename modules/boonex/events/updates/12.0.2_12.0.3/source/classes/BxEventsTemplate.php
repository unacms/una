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

    function unitVars ($aData, $isCheckPrivateContent = true, $mixedTemplate = false, $aParams = array())
    {
        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $mixedTemplate, $aParams);

        $CNF = &$this->_oConfig->CNF;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);

        $aContentInfo = $this->getModule()->_oDb->getContentInfoById($aData[$CNF['FIELD_ID']]);

        $oDateStart = date_create('@' . $aContentInfo['date_start']);
        $oDateStart->setTimezone(new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));

        $oDateEnd = date_create('@' . $aContentInfo['date_end']);
        $oDateEnd->setTimezone(new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));

        $isPublic = CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedView($aData) || $oPrivacy->isPartiallyVisible($aData[$CNF['FIELD_ALLOW_VIEW_TO']]);        
        if ($isPublic) {
            $aVars['bx_if:info']['content']['members'] = $oDateStart->format(getParam('bx_events_short_date_format'));
        }

        return array_merge($aVars, array(
			'date_start' => $aData['date_start'],
			'date_start_f' => strftime('%e %b', $oDateStart->getTimestamp()),
			'date_end' => $aData['date_end'],
			'date_end_f' => strftime('%e %b', $oDateEnd->getTimestamp()),
        ));
    }
}

/** @} */
