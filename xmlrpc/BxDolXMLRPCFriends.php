<?php

class BxDolXMLRPCFriends
{
    function getFriends($sUser, $sPwd, $sNick, $sLang)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang);

        $sFriendsSQL = "
            SELECT `p`.*, `f`.`ID`
            FROM (
                SELECT `ID` AS `ID` FROM `sys_friend_list` WHERE `Profile` = '{$iIdProfile}' AND `Check` =1
                UNION
                SELECT `Profile` AS `ID` FROM `sys_friend_list` WHERE `ID` = '{$iIdProfile}' AND `Check` =1
            ) AS `f`
            INNER JOIN `Profiles` AS `p` ON `p`.`ID` = `f`.`ID`
            ORDER BY p.`Avatar` DESC
        ";
        $r = db_res($sFriendsSQL);

        /*$r = db_res ("SELECT `Profiles`.* FROM `sys_friend_list`
            LEFT JOIN `Profiles` ON (`Profiles`.`ID` = `sys_friend_list`.`Profile` AND `sys_friend_list`.`ID` = '$iIdProfile' OR `Profiles`.`ID` = `sys_friend_list`.`ID` AND `sys_friend_list`.`Profile` = '$iIdProfile')
            WHERE (`sys_friend_list`.`Profile` = '$iIdProfile' OR `sys_friend_list`.`ID` = '$iIdProfile') AND `sys_friend_list`.`Check` = '1'
            ORDER BY `Profiles`.`Avatar` DESC");*/

        $aProfiles = array ();
        while ($aRow = mysql_fetch_array ($r))
            $aProfiles[] = new xmlrpcval(BxDolXMLRPCUtil::fillProfileArray($aRow, 'thumb'), 'struct');

        return new xmlrpcval ($aProfiles, "array");
    }

    function getFriendRequests($sUser, $sPwd, $sLang)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang);

        $r = db_res ("
            SELECT `Profiles`.* FROM `sys_friend_list`
            LEFT JOIN `Profiles` ON `Profiles`.`ID` = `sys_friend_list`.`ID`
            WHERE `sys_friend_list`.`Profile` = $iId AND `Check` = 0
            ORDER BY `Profiles`.`NickName` ASC");

        $aProfiles = array ();
        while ($aRow = mysql_fetch_array ($r))
            $aProfiles[] = new xmlrpcval(BxDolXMLRPCUtil::fillProfileArray($aRow, 'thumb'), 'struct');

        return new xmlrpcval ($aProfiles, "array");
    }

    function declineFriendRequest($sUser, $sPwd, $sNick)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        require_once( BX_DIRECTORY_PATH_ROOT . 'templates/tmpl_' . $GLOBALS['tmpl'] . '/scripts/BxTemplCommunicator.php');
        $aCommunicatorSettings = array ('member_id' => $iId);
        $aMembersList = array ($iIdProfile);
        $oCommunicator = &new BxTemplCommunicator('communicator_page', $aCommunicatorSettings);
        $oCommunicator->execFunction('_deleteRequest', 'sys_friend_list', $aMembersList);

        return new xmlrpcval ('ok');
    }

    function acceptFriendRequest($sUser, $sPwd, $sNick)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        require_once( BX_DIRECTORY_PATH_ROOT . 'templates/tmpl_' . $GLOBALS['tmpl'] . '/scripts/BxTemplCommunicator.php');
        $aCommunicatorSettings = array ('member_id' => $iId);
        $aMembersList = array ($iIdProfile);
        $oCommunicator = &new BxTemplCommunicator('communicator_page', $aCommunicatorSettings);
        $oCommunicator->execFunction('_acceptFriendInvite', 'sys_friend_list', $aMembersList);

        return new xmlrpcval ('ok');
    }

    function removeFriend($sUser, $sPwd, $sNick)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        require_once( BX_DIRECTORY_PATH_ROOT . 'templates/tmpl_' . $GLOBALS['tmpl'] . '/scripts/BxTemplCommunicator.php');
        $aCommunicatorSettings = array ('member_id' => $iId);
        $aMembersList = array ($iIdProfile);
        $oCommunicator = &new BxTemplCommunicator('communicator_page', $aCommunicatorSettings);
        $oCommunicator->execFunction( '_deleteRequest', 'sys_friend_list', $aMembersList, array(1, 1));

        return new xmlrpcval ('ok');
    }

    function addFriend($sUser, $sPwd, $sNick, $sLang)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang);

        ob_start();
        $_GET['action'] = '1';
        require_once( BX_DIRECTORY_PATH_ROOT . 'list_pop.php' );
        ob_end_clean();

        $sRet = PageListFriend ($iId, $iIdProfile);

        return new xmlrpcval (trim(strip_tags($sRet)));
    }
}

?>
