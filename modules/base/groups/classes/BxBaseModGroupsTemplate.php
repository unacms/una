<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

/*
 * Groups module representation.
 */
class BxBaseModGroupsTemplate extends BxBaseModProfileTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html')
    {
        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $sTemplateName);
        
        $CNF = &$this->_oConfig->CNF;

        $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);

        $isPublic = CHECK_ACTION_RESULT_ALLOWED === $this->getModule()->checkAllowedView($aData) || $oPrivacy->isPartiallyVisible($aData[$CNF['FIELD_ALLOW_VIEW_TO']]);        
        $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);

        $aVars['title'] = $isPublic ? bx_process_output($aData[$CNF['FIELD_NAME']]) : _t($CNF['T']['txt_private_group']);
        $aVars['bx_if:info']['condition'] = true;
        $aVars['bx_if:info']['content']['members'] = $isPublic ? _t($CNF['T']['txt_N_fans'], $oConn ? $oConn->getConnectedInitiatorsCount($oGroupProfile->id(), true) : 0) : '&nbsp;';
        $aVars['bx_if:info']['content']['bx_if:btn'] = array (
            'condition' => $isPublic && $this->getModule()->checkAllowedFanAdd($aData) === CHECK_ACTION_RESULT_ALLOWED,
            'content' => array (
                'id' => $oGroupProfile->id(),
                'title' => $oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $oGroupProfile->id()) ? _t($CNF['T']['menu_item_title_become_fan_sent']) : _t($CNF['T']['menu_item_title_become_fan']),
                'object' => $CNF['OBJECT_CONNECTIONS'],
            ),
        );

        return $aVars;
    }

    protected function _getUnitClass($aData, $sTemplateName = 'unit.html')
    {
        $sResult = '';

        switch($sTemplateName) {
            case 'unit.html':
            case 'unit_with_cover.html':
                $sResult = 'bx-base-pofile-unit-with-cover bx-base-groups-unit-with-cover';
                break;
            case 'unit_wo_info.html':
                $sResult = 'bx-base-pofile-unit-wo-info bx-base-groups-unit-wo-info';
                break;
        }

        return $sResult;
    }
}

/** @} */
