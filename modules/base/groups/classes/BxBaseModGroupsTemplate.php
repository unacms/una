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
        
        $this->_sUnitClassWithCover .= ' bx-base-groups-unit-with-cover';
        $this->_sUnitClass = $this->_sUnitClassWithCover;
        $this->_sUnitClassWoInfo .= ' bx-base-groups-unit-wo-info'; 
    }

    function unitVars ($aData, $isCheckPrivateContent = true, $sTemplateName = 'unit.html')
    {
        $aVars = parent::unitVars ($aData, $isCheckPrivateContent, $sTemplateName);
        
        $CNF = &$this->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstance($aData[$CNF['FIELD_AUTHOR']]);
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        $bPublic = true; 
        if(!empty($CNF['OBJECT_PRIVACY_VIEW'])) {
            $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
            if ($oPrivacy && !$oPrivacy->check($aData[$CNF['FIELD_ID']]) && !$oPrivacy->isPartiallyVisible($aData[$CNF['FIELD_ALLOW_VIEW_TO']]))
                $bPublic = false;
        }
        
        $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aData[$CNF['FIELD_ID']], $this->MODULE);

        $aVars['title'] = $bPublic ? bx_process_output($aData[$CNF['FIELD_NAME']]) : _t($CNF['T']['txt_private_group']);
        $aVars['author'] = $oProfile->getDisplayName();
        $aVars['author_url'] = $oProfile->getUrl();
        $aVars['author_icon'] = $oProfile->getIcon();
        $aVars['author_thumb'] = $oProfile->getThumb();
        $aVars['author_avatar'] = $oProfile->getAvatar();
        $aVars['bx_if:info']['condition'] = true;
        $aVars['bx_if:info']['content']['members'] = $bPublic ? _t($CNF['T']['txt_N_fans'], $oConn ? $oConn->getConnectedInitiatorsCount($oGroupProfile->id(), true) : 0) : '&nbsp;';
        $aVars['bx_if:info']['content']['bx_if:btn'] = array (
            'condition' => $bPublic && $this->getModule()->checkAllowedFanAdd($aData) === CHECK_ACTION_RESULT_ALLOWED,
            'content' => array (
                'id' => $oGroupProfile->id(),
                'title' => $oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $oGroupProfile->id()) ? _t($CNF['T']['menu_item_title_become_fan_sent']) : _t($CNF['T']['menu_item_title_become_fan']),
                'object' => $CNF['OBJECT_CONNECTIONS'],
            ),
        );

        return $aVars;
    }
}

/** @} */
