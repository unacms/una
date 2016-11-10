<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services for connections.
 */
class BxBaseServiceConnections extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get grid with friends connections
     */
    public function serviceConnectionsTable ()
    {
        $oGrid = BxDolGrid::getObjectInstance('sys_grid_connections');
        if (!$oGrid)
            return false;

        return $oGrid->getCode();
    }

    /**
     * get number of received unconfirmed connections (friend requests)
     * @param $sConnectionsObject connections object to get unconfirmed connections from
     * @param $mixedId id to get connections for, if omitted then logged-in profile id is used
     * @return number
     */
    public function serviceGetUnconfirmedConnectionsNum ($sConnectionsObject, $mixedId = 0)
    {
        $oConnection = BxDolConnection::getObjectInstance($sConnectionsObject);
        if (!$oConnection)
            return 0;

        if (!$mixedId)
            $mixedId = bx_get_logged_profile_id();

        $i = 0;
        $a = $oConnection->getConnectedInitiators($mixedId, 0); // get received friend requests
        foreach ($a as $iId)
            if (BxDolProfile::getInstance($iId))
                ++$i;

        return $i;
    }

    /*
     * Get notification data for Notifications module. 
     */
	public function serviceGetNotificationsPost($aEvent)
    {
    	$iProfile = (int)$aEvent['object_id'];
    	$oProfile = BxDolProfile::getInstance($iProfile);
        if(!$oProfile)
			return array();

		return array(
			'entry_sample' => '_sys_profile_sample_single',
			'entry_url' => $oProfile->getUrl(),
			'entry_caption' => $oProfile->getDisplayName(),
			'entry_author' => $oProfile->id(),
			'lang_key' => '_sys_profile_subscription_added',
		);
    }
}

/** @} */
