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
        if (!$this->_aObject['spaces'])
            return $aValues;

        if (!($oProfile = BxDolProfile::getInstance($iOwnerId)))
            return $aValues;

        if (!($aModules = BxDolModuleQuery::getInstance()->getModules()))
            return $aValues;
            
        $oProfileQuery = BxDolProfileQuery::getInstance();

        $sConnections = $this->_oModule->_oConfig->getObject('conn_subscriptions');
        $aConnections = BxDolConnection::getObjectInstance($sConnections)->getConnectedContent($iOwnerId);

        $aCnnGroups = array();
        foreach($aConnections as $iId) {
            $aProfileInfo = $oProfileQuery->getInfoById($iId);
            if(empty($aProfileInfo) || !is_array($aProfileInfo))
                continue;

            $aCnnGroups[$aProfileInfo['type']][] = $aProfileInfo['id'];
        }

        $oProfile = BxDolProfile::getInstance();
        foreach($aCnnGroups as $sCnnGroup => $aCnnGroup) {
            $aValues[] = array('type' => 'group_header', 'value' => _t('_bx_timeline_form_post_input_owner_id_following_group', _t('_' . $sCnnGroup)));
            foreach($aCnnGroup as $iId)
                $aValues[] = array('key' => -$iId, 'value' => $oProfile->getDisplayName($iId));
            $aValues[] = array('type' => 'group_end');
        }

        return $aValues;
    }
    
}

/** @} */
