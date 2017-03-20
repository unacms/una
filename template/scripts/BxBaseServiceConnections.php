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

    /**
     * get number of initiated connections (like "my subscriptions")
     * @param $sConnectionsObject connections object to get connections from
     * @param $mixedId id to get connections for, if omitted then logged-in profile id is used
     * @return number
     */
    public function serviceGetConnectedContentNum ($sConnectionsObject, $mixedId = 0)
    {
        $oConnection = BxDolConnection::getObjectInstance($sConnectionsObject);
        if (!$oConnection)
            return 0;

        if (!$mixedId)
            $mixedId = bx_get_logged_profile_id();

        $i = 0;
        $a = $oConnection->getConnectedContent($mixedId); // get received friend requests
        foreach ($a as $iId)
            if (BxDolProfile::getInstance($iId))
                ++$i;

        return $i;
    }

    /**
     * get number of received connections (like "subscribed me")
     * @param $sConnectionsObject connections object to get connections from
     * @param $mixedId id to get connections for, if omitted then logged-in profile id is used
     * @return number
     */
    public function serviceGetConnectedInitiatorsNum ($sConnectionsObject, $mixedId = 0)
    {
        $oConnection = BxDolConnection::getObjectInstance($sConnectionsObject);
        if (!$oConnection)
            return 0;

        if (!$mixedId)
            $mixedId = bx_get_logged_profile_id();

        $i = 0;
        $a = $oConnection->getConnectedInitiators($mixedId); // get received friend requests
        foreach ($a as $iId)
            if (BxDolProfile::getInstance($iId))
                ++$i;

        return $i;
    }

	/**
     * get grid with subscriptions connections
     */
    public function serviceSubscriptionsTable ()
    {
        $aProfile = BxDolProfile::getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT))->getInfo();
        if(empty($aProfile) || !is_array($aProfile))
            return false;

        $aProfileInfo = BxDolService::call($aProfile['type'], 'get_content_info_by_id', array($aProfile['content_id']));
        if(empty($aProfileInfo) || !is_array($aProfileInfo))
            return false;

        if((int)$aProfileInfo['public_subscriptions'] == 0 && $aProfile['id'] != bx_get_logged_profile_id())
            return false;

        $oGrid = BxDolGrid::getObjectInstance('sys_grid_subscriptions');
        if (!$oGrid)
            return false;

        return BxDolTemplate::getInstance()->parseHtmlByName('connections_list.html', array(
            'name' => 'subscriptions',
            'content' => $oGrid->getCode()
        ));
    }

	/**
     * get grid with subscribed me connections
     */
    public function serviceSubscribedMeTable ()
    {
        $aProfile = BxDolProfile::getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT))->getInfo();
        if(empty($aProfile) || !is_array($aProfile))
            return false;

        $aProfileInfo = BxDolService::call($aProfile['type'], 'get_content_info_by_id', array($aProfile['content_id']));
        if(empty($aProfileInfo) || !is_array($aProfileInfo))
            return false;

        if((int)$aProfileInfo['public_subscribed_me'] == 0 && $aProfile['id'] != bx_get_logged_profile_id())
            return false;

        $oGrid = BxDolGrid::getObjectInstance('sys_grid_subscribed_me');
        if (!$oGrid)
            return false;

        return BxDolTemplate::getInstance()->parseHtmlByName('connections_list.html', array(
            'name' => 'subscribers',
            'content' => $oGrid->getCode()
        ));
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
