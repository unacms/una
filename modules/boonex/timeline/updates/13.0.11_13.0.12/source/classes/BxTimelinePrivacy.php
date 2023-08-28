<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelinePrivacy extends BxBaseModNotificationsPrivacy
{
    protected $_oModule;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance('bx_timeline');
    }

    public function addSpaces($aValues, $iOwnerId, $aParams)
    {
        if(!$this->_aObject['spaces'])
            return $aValues;

        if(!empty($aParams['display']) && $aParams['display'] != $this->_oModule->_oConfig->getObject('form_display_post_add'))
            return $aValues;

        $sConnections = $this->_oModule->_oConfig->getObject('conn_subscriptions');
        $aConnections = BxDolConnection::getObjectInstance($sConnections)->getConnectedContent($iOwnerId);

        $aCnnGroups = array();
        foreach($aConnections as $iId) {
            $oCnnProfile = BxDolProfile::getInstance($iId);
            if(!$oCnnProfile)
                continue;

            $sCnnProfileModule = $oCnnProfile->getModule();
            $sCnnProfileMethod = 'check_allowed_post_in_profile';
            if(!BxDolRequest::serviceExists($sCnnProfileModule, $sCnnProfileMethod))
                continue;

            $mixedCheckResult = bx_srv($sCnnProfileModule, $sCnnProfileMethod, array($oCnnProfile->getContentId(), $iOwnerId));
            if($mixedCheckResult !== CHECK_ACTION_RESULT_ALLOWED)
                continue;

            $aCnnGroups[$sCnnProfileModule][] = array('key' => -$iId, 'value' => $oCnnProfile->getDisplayName());
        }

        ksort($aCnnGroups);
        foreach($aCnnGroups as $sCnnGroup => $aCnnGroup) {
            $aValues[] = array('type' => 'group_header', 'value' => mb_strtoupper(_t('_bx_timeline_form_post_input_owner_id_following_group', _t('_' . $sCnnGroup))));
            if(!empty($aCnnGroup) && is_array($aCnnGroup))
                $aValues = array_merge($aValues, $aCnnGroup);
            $aValues[] = array('type' => 'group_end');
        }

        return $aValues;
    }

    public function getContentByGroupAsSQLPart($mixedGroupId)
    {
        $aResult = parent::getContentByGroupAsSQLPart($mixedGroupId);

        if($this->_oModule->_oDb->isTableAlias()) {
            $sTable = $this->_oModule->_oDb->getTable();
            $sTableAlias = $this->_oModule->_oDb->getTableAlias();
            foreach($aResult as $sKey => $sValue)
                $aResult[$sKey] = str_replace($sTable, $sTableAlias, $sValue);
        }

        return $aResult;
    }
}

/** @} */
