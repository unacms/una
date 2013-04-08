<?php

class BxDolXMLRPCUtil
{
    function getContacts($sUser, $sPwd)
    {
        $aRet = array ();
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        $aAll = array();
        $aContacts = array ();

        // hot list
        $r = db_res ("SELECT `p`.`ID`, `p`.`NickName` AS `Nick` FROM `Profiles` AS `p`
            INNER JOIN `sys_fave_list` AS `h` ON (`h`.`Profile` = `p`.`ID`)
            WHERE `h`.`ID` = $iId");
        while ($aRow = mysql_fetch_array ($r))
            $aAll[$aRow['ID']] = $aRow;

        // mail contacts received
        $r = db_res ("SELECT `p`.`ID`, `p`.`NickName` AS `Nick` FROM `Profiles` AS `p`
            INNER JOIN `sys_messages` AS `m` ON (`m`.`Sender` = `p`.`ID`)
            WHERE `p`.`ID` != $iId AND `m`.`Recipient` = $iId");
        while ($aRow = mysql_fetch_array ($r))
            $aAll[$aRow['ID']] = $aRow;

        // mail contacts sent
        $r = db_res ("SELECT `p`.`ID`, `p`.`NickName` AS `Nick` FROM `Profiles` AS `p`
            INNER JOIN `sys_messages` AS `m` ON (`m`.`Recipient` = `p`.`ID`)
            WHERE `p`.`ID` != $iId AND `m`.`Sender` = $iId");
        while ($aRow = mysql_fetch_array ($r))
            $aAll[$aRow['ID']] = $aRow;

        // friends 1
        $r = db_res ("SELECT `p`.`ID`, `p`.`NickName` AS `Nick` FROM `sys_friend_list` AS `fr`
            LEFT JOIN `Profiles` AS `p` ON (`p`.`ID` = `fr`.`Profile`)
            WHERE `fr`.`ID` = '$iId' AND `fr`.`Profile` != $iId AND `fr`.`Check` = '1'");
        while ($aRow = mysql_fetch_array ($r))
            $aAll[$aRow['ID']] = $aRow;

        // friends 2
        $r = db_res ("SELECT `p`.`ID`, `p`.`NickName` AS `Nick` FROM `sys_friend_list` AS `fr`
            LEFT JOIN `Profiles` AS `p` ON (`p`.`ID` = `fr`.`ID`)
            WHERE `fr`.`Profile` = '$iId' AND `fr`.`ID` != $iId AND `fr`.`Check` = '1'");
        while ($aRow = mysql_fetch_array ($r))
            $aAll[$aRow['ID']] = $aRow;

        $oZ = new BxDolAlerts('mobile', 'contacts', $iId, 0, array('contacts_data' => $aAll));
        $oZ->alert(); 

        foreach ($aAll as $aRow)
        {
            $a = array (
                'ID' => new xmlrpcval($aRow['ID']),
                'Nick' => new xmlrpcval($aRow['Nick']),
            );
            $aContacts[] = new xmlrpcval($a, 'struct');
        }
        return new xmlrpcval ($aContacts, "array");
    }

    function getCountries($sUser, $sPwd, $sLang)
    {
        $aRet = array ();
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        BxDolXMLRPCUtil::setLanguage ($sLang);

        $aCountries = array ();
        $r = db_res ("SELECT `ISO2`, `Country` FROM `sys_countries` ORDER BY `Country` ASC");
        while ($aRow = mysql_fetch_array ($r))
        {
            $a = array (
                'Name' => new xmlrpcval(_t('__'.$aRow['Country'])),
                'Code' => new xmlrpcval($aRow['ISO2']),
            );
            $aCountries[] = new xmlrpcval($a, 'struct');
        }
        return new xmlrpcval ($aCountries, "array");
    }

    function getThumbLink ($iId, $sType = 'thumb')
    {
        $sType = $sType == 'thumb' ? 'medium' : 'small';
        return $GLOBALS['oFunctions']->getMemberAvatar ((int)$iId, $sType);
    }

    function getUserInfo($iId, $iIdViewer = 0, $isCountData = true)
    {
        if (!$iIdViewer)
            $iIdViewer = $_COOKIE['memberID'];

        $aRet = array ();
        $aSexSql = getProfileInfo((int)$iId);
        $aRet['title'] = new xmlrpcval($aSexSql['Headline']);
        $aRet['thumb'] = new xmlrpcval(BxDolXMLRPCUtil::getThumbLink($iId));
        $aRet['sex'] = new xmlrpcval($aSexSql['Sex']);
        $aRet['age'] = new xmlrpcval(age($aSexSql['DateOfBirth']));
        $aRet['country'] = new xmlrpcval($aSexSql['Country']);
        $aRet['city'] = new xmlrpcval($aSexSql['City']);
        $aRet['status'] = new xmlrpcval($aSexSql['UserStatusMessage']);

        if ($isCountData) {
            $aRet['countFriends'] = new xmlrpcval(getFriendNumber($iId));
            $aRet['countPhotos'] = new xmlrpcval(BxDolXMLRPCMedia::_getMediaCount('photo', $iId, $iIdViewer));
            $aRet['countVideos'] = new xmlrpcval(BxDolXMLRPCMedia::_getMediaCount('video', $iId, $iIdViewer));
            $aRet['countSounds'] = new xmlrpcval(BxDolXMLRPCMedia::_getMediaCount('music', $iId, $iIdViewer));
        }

        bx_import('BxDolAlerts');
        $oZ = new BxDolAlerts('mobile', 'user_info', $iId, $iIdViewer, array('profile' => &$aSexSql, 'return_data' => &$aRet));
        $oZ->alert(); 
 
        return $aRet;
    }

    function fillProfileArray ($a, $sImage = 'icon', $iIdViewer = 0)
    {
        if (!$iIdViewer)
            $iIdViewer = $_COOKIE['memberID'];

        $sImageKey = ucfirst ($sImage);
        $sImage = BxDolXMLRPCUtil::getThumbLink($a['ID'], $sImage);

        bx_import('BxDolAlbums');

        $aRet = array (
               'ID' => new xmlrpcval($a['ID']),
               'Title' => new xmlrpcval($a['Headline']),
               'Nick' => new xmlrpcval($a['NickName']),
               'Sex' => new xmlrpcval($a['Sex']),
               'Age' => new xmlrpcval(age($a['DateOfBirth'])),
               'Country' => new xmlrpcval(_t($GLOBALS['aPreValues']['Country'][$a['Country']]['LKey'])),
               'City' => new xmlrpcval($a['City']),
               'CountPhotos' => new xmlrpcval(BxDolXMLRPCMedia::_getMediaCount('photo', $iId, $iIdViewer)),
               'CountVideos' => new xmlrpcval(BxDolXMLRPCMedia::_getMediaCount('video', $iId, $iIdViewer)),
               'CountSounds' => new xmlrpcval(BxDolXMLRPCMedia::_getMediaCount('music', $iId, $iIdViewer)),
               'CountFriends' => new xmlrpcval(getFriendNumber($a['ID'])),
               $sImageKey => new xmlrpcval($sImage),
            );

        $oZ = new BxDolAlerts('mobile', 'user_info2', $a['ID'], $iIdViewer, array('profile' => &$a, 'return_data' => &$aRet));
        $oZ->alert();

        return $aRet;
    }

    function getMenu ($sMenu, $aMarkersReplace = array ()) {

        $aDefaultMarkers = array (
            'site_url' => BX_DOL_URL_ROOT,
        );
        $aMarkersReplace = array_merge($aDefaultMarkers, $aMarkersReplace);
        $aKeys = array_keys($aMarkersReplace);
        $aValues = array_values($aMarkersReplace);
        foreach ($aKeys as $k => $v) 
            $aKeys[$k] = '{' . $v . '}';

        $aMenu = array ();
        $aRecords = $GLOBALS['MySQL']->getAll("SELECT * FROM `sys_menu_mobile` WHERE `page` = '$sMenu' AND `active` = 1 ORDER BY `order`");

        $oZ = new BxDolAlerts('mobile', 'menu', 0, 0, array('menu' => $sMenu, 'data' => &$aRecords, 'markers_replace' => &$aMarkersReplace));
        $oZ->alert(); 

        foreach ($aRecords as $r) {
            $a = array (); 
            if ($r['eval_hidden']) {
                if ($aMarkersReplace)
                    $sVal = str_replace($aKeys, $aValues, $r['eval_hidden']);
                if (@eval($sVal))
                    continue;
            }
            foreach ($r as $sName => $sVal) {                
                switch ($sName) {
                    case 'eval_hidden':
                        break;
                    case 'eval_bubble':
                        if ($sVal && $aMarkersReplace)
                            $sVal = str_replace($aKeys, $aValues, $sVal);
                        $a['bubble'] = new xmlrpcval($sVal ? @eval($sVal) : '');
                        break;
                    case 'icon':
                    case 'action_data':
                        if ($sVal && $aMarkersReplace)
                            $sVal = str_replace($aKeys, $aValues, $sVal);
                        $a[$sName] = new xmlrpcval($sVal);
                        break;
                    case 'title':
                        $a[$sName] = new xmlrpcval(_t($sVal));
                        break;
                    default:
                        $a[$sName] = new xmlrpcval($sVal);
                }
            }
            $aMenu[] = new xmlrpcval($a, 'struct');
        }
        return $aMenu;
     }

    function getIdByNickname ($sUser)
    {
        $sUser = process_db_input($sUser, BX_TAGS_NO_ACTION, BX_SLASHES_NO_ACTION);
        return (int)db_value("SELECT `ID` FROM `Profiles` WHERE `NickName` = '$sUser' LIMIT 1");
    }

    function checkLogin ($sUser, $sPwd)
    {
        $iId = (int)BxDolXMLRPCUtil::getIdByNickname ($sUser);
        $aProfile = getProfileInfo((int)$iId);
        if (!$aProfile)
            return 0;
        $_COOKIE["memberID" ] = $iId;
        $_COOKIE["memberPassword"] = sha1($sPwd . $aProfile['Salt']);
        $iRet = ($GLOBALS['logged']['member'] = member_auth(0, false)) ? $iId : 0;

        $oZ = new BxDolAlerts('mobile', 'check_login', $iId, 0, array('password' => $sPwd, 'return_data' => &$iRet));
        $oZ->alert();

        return $iRet;
    }

    function setLanguage ($sLang)
    {
        if ('English' == $sLang || !preg_match('/^[a-zA-Z]+$/', $sLang))
            $sLang = 'en';
        $_GET['lang'] = $sLang;
        $sCurrentLanguage = getCurrentLangName();
        global $LANG;
        require_once( BX_DIRECTORY_PATH_ROOT . "langs/lang-{$sCurrentLanguage}.php" );
    }

    function concat($s1, $s2)
    {
        return new xmlrpcval($s1.$s2);
    }
}

?>
