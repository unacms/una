<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    OktaConnect Okta Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOktaConCustom
{
    function __construct($aModule)
    {
    }

    function onConfig ($oConfig)
    {
    }

    function onRegister ($aRemoteProfileInfo)
    {
        bx_log('bx_oktacon', $aRemoteProfileInfo);
        
        if (!empty($aRemoteProfileInfo['communities']))
           $this->syncCommunities(bx_get_logged_profile_id(), explode(',', $aRemoteProfileInfo['communities']));
    }

    function onLogin ($oProfile, $aRemoteProfileInfo)
    {
        bx_log('bx_oktacon', $aRemoteProfileInfo);

        if (!empty($aRemoteProfileInfo['communities']))
           $this->syncCommunities($oProfile ? $oProfile->id() : bx_get_logged_profile_id(), explode(',', $aRemoteProfileInfo['communities']));
    }

    function onConvertRemoteFields($aProfileInfo, &$aProfileFields)
    {

    }

    function syncCommunities($iProfileId, $aCommunities, $sModule = 'bx_groups', $sObjConn = 'bx_groups_fans')
    {
	    if (!$iProfileId)
	        return;

        // convers content ids to profile ids + validate
        $aCommunitiesNew = [];
        foreach ($aCommunities as $iId) {
            $iId = (int)trim($iId);
            $aInfo = bx_srv($sModule, 'get_info', [$iId, false]);
            if (!$aInfo)
                continue;
            $aCommunitiesNew[] = $aInfo['profile_id'];
        }

        // get current profile communities
        $oConnections = BxDolConnection::getObjectInstance($sObjConn);
        $aCommunitiesOld = $oConnections->getConnectedInitiators($iProfileId, true);

        // communities to remove
        $aCommunitiesRemove = array_diff($aCommunitiesOld, $aCommunitiesNew);
        if ($aCommunitiesRemove) {
            foreach ($aCommunitiesRemove as $i) {
                $oConnections->removeConnection($iProfileId, $i);
            }
        }

        // communities to add
        $aCommunitiesAdd = array_diff($aCommunitiesNew, $aCommunitiesOld);
        if ($aCommunitiesAdd) {
            foreach ($aCommunitiesAdd as $i) {
                $oConnections->addConnection($iProfileId, $i);
                $oConnections->addConnection($i, $iProfileId);
            }
	    }
    }
}

/** @} */
