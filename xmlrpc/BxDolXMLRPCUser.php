<?php

class BxDolXMLRPCUser
{
    function login($sUser, $sPwd)
    {
        $iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd);
        return new xmlrpcresp(new xmlrpcval($iId, "int"));
    }

    function login2($sUser, $sPwd)
    {
        $iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd);
        return new xmlrpcresp(new xmlrpcval(array(
            'member_id' => new xmlrpcval($iId, "int"),
            'protocol_ver' => new xmlrpcval(BX_XMLRPC_PROTOCOL_VER, "int"),
        ), "struct"));
    }

    function updateUserLocation ($sUser, $sPwd, $sLat, $sLng, $sZoom, $sMapType)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)) || !preg_match('/^[A-Za-z0-9]*$/', $sMapType))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        $iRet = BxDolService::call('map_profiles', 'update_profile_location_manually', array ($iId, (float)$sLat, (float)$sLng, (int)$sZoom, $sMapType, '', '')) ? '1' : '0';

        return new xmlrpcresp(new xmlrpcval($iRet));
    }

    function getUserLocation ($sUser, $sPwd, $sNick)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        $iProfileId = getID($sNick, false);
        $aLocation = BxDolService::call('map_profiles', 'get_location', array($iProfileId, $iId, true));
        if (-1 == $aLocation) // access denied
            return new xmlrpcval("-1");
        if (!is_array($aLocation)) // location is undefined
            return new xmlrpcval("0");

        return new xmlrpcval(array(
            'lat' => new xmlrpcval($aLocation['lat']),
            'lng' => new xmlrpcval($aLocation['lng']),
            'zoom' => new xmlrpcval($aLocation['zoom']),
            'type' => new xmlrpcval($aLocation['type']),
            'address' => new xmlrpcval($aLocation['address']),
            'country' => new xmlrpcval($aLocation['country']),
        ), 'struct');
    }

    function getHomepageInfo($sUser, $sPwd)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        $aRet = BxDolXMLRPCUtil::getUserInfo($iId);

        $aRet['unreadLetters'] = new xmlrpcval(getNewLettersNum($iId));
        $aFriendReq =  db_arr( "SELECT count(*) AS `num` FROM `sys_friend_list` WHERE `Profile` = {$iId} AND  `Check` = '0'" );
        $aRet['friendRequests'] = new xmlrpcval($aFriendReq['num']);

        return new xmlrpcval ($aRet, "struct");
    }

    function getHomepageInfo2($sUser, $sPwd, $sLang)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang);

        $aRet = BxDolXMLRPCUtil::getUserInfo($iId);

        $aMarkersReplace = array (
            'member_id' => $iId,
            'member_username' => $sUser,
            'member_password' => $sPwd,
        );
        $aRet['menu'] = new xmlrpcval(BxDolXMLRPCUtil::getMenu('homepage', $aMarkersReplace), 'array');

        return new xmlrpcval ($aRet, "struct");
    }

    function getUserInfo2($sUser, $sPwd, $sNick, $sLang) {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang); 

        $mixedRet = BxDolXMLRPCUser::_checkUserPrivacy ($iId, $iIdProfile);
        if (true !== $mixedRet)
            return $mixedRet;

        $aRet['info'] = new xmlrpcval (BxDolXMLRPCUtil::getUserInfo($iIdProfile, 0, false), "struct");

        $aMarkersReplace = array (
            'member_id' => $iId,
            'member_username' => $sUser,
            'member_password' => $sPwd,
            'profile_id' => $iIdProfile,
            'profile_username' => $sNick,
        );
        $aRet['menu'] = new xmlrpcval(BxDolXMLRPCUtil::getMenu('profile', $aMarkersReplace), 'array');

        return new xmlrpcval ($aRet, "struct");
    }

    function getUserInfo($sUser, $sPwd, $sNick, $sLang) {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang); 

        $mixedRet = BxDolXMLRPCUser::_checkUserPrivacy ($iId, $iIdProfile);
        if (true !== $mixedRet)
            return $mixedRet;

        $aRet = BxDolXMLRPCUtil::getUserInfo($iIdProfile, 0, true);
        return new xmlrpcval ($aRet, "struct");
    }

    function _checkUserPrivacy($iId, $iIdProfile)
    {
        $mixedAccessDenied = false;

		if ($iIdProfile != $iId)
		{
			// privacy
			bx_import('BxDolPrivacy');
			$oPrivacy = new BxDolPrivacy('Profiles', 'ID', 'ID');
			if ($iIdProfile != $iId && !$oPrivacy->check('view', $iIdProfile, $iId))
				$mixedAccessDenied = '-1';
            
			// membership
            if (false === $mixedAccessDenied) {
    			$aCheckRes = checkAction($iId, ACTION_ID_VIEW_PROFILES, true, $iIdProfile);
	        	if ($aCheckRes[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED)
				    $mixedAccessDenied = $aCheckRes[CHECK_ACTION_MESSAGE];
            }
		}

    	$oZ = new BxDolAlerts('mobile', 'view_profile', $iIdProfile, $iId, array('access_denied' => &$mixedAccessDenied));
    	$oZ->alert(); 

        if (false !== $mixedAccessDenied)
            return new xmlrpcval ($mixedAccessDenied);

        return true;
    }
    
    function getUserInfoExtra($sUser, $sPwd, $sNick, $sLang)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang);

        $o = new BxDolXMLRPCProfileView ($iIdProfile);
        return $o->getProfileInfoExtra();
    }

    function updateStatusMessage ($sUser, $sPwd, $sStatusMsg)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        ob_start();
        $_GET['action'] = '1';
        require_once( BX_DIRECTORY_PATH_ROOT . 'list_pop.php' );
        ob_end_clean();

        $_POST['status_message'] = $sStatusMsg;
        ActionChangeStatusMessage ($iId);

        return new xmlrpcresp(new xmlrpcval($iRet, "int"));
    }

}

?>
