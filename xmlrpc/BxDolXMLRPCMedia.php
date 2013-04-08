<?php

class BxDolXMLRPCMedia
{
    // ----------------- albums list

    function getAudioAlbums ($sUser, $sPwd, $sNick)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        return BxDolXMLRPCMedia::_getMediaAlbums ('music', $iIdProfile, $iId);
    }

    function getVideoAlbums ($sUser, $sPwd, $sNick)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        return BxDolXMLRPCMedia::_getMediaAlbums ('video', $iIdProfile, $iId);
    }

    function _getMediaAlbums ($sType, $iIdProfile, $iIdProfileViewer, $isShowEmptyAlbums = false)
    {
        $aAlbums = BxDolXMLRPCMedia::_getMediaAlbumsArray ($sType, $iIdProfile, $iIdProfileViewer, $isShowEmptyAlbums);

        $aXmlRpc = array ();

        foreach ($aAlbums as $r)
        {
            $a = array (
                'Id' => new xmlrpcval($r['Id']),
                'Title' => new xmlrpcval($r['Title']),
                'Num' =>new xmlrpcval($r['Num']),
            );
            $aXmlRpc[] = new xmlrpcval($a, 'struct');
        }

        return new xmlrpcval ($aXmlRpc, "array");
    }

    function _getMediaCount ($sType, $iIdProfile, $iIdProfileViewer) {
        $a = BxDolXMLRPCMedia::_getMediaAlbumsArray ($sType, $iIdProfile, $iIdProfileViewer);
        $iNum = 0;
        foreach ($a as $r)
            $iNum += $r['Num'];
        return $iNum;
    }

    function _getMediaAlbumsArray ($sType, $iIdProfile, $iIdProfileViewer, $isShowEmptyAlbums = false)
    {
        switch ($sType) {
            case 'photo':
                $sModuleName = 'photos';
                $sType = 'bx_photos';
                $sMemAction = 'BX_PHOTOS_VIEW';
                break;
            case 'video':
                $sModuleName = 'videos';
                $sType = 'bx_videos';
                $sMemAction = 'BX_VIDEOS_VIEW';
                break;
            case 'music':
                $sModuleName = 'sounds';
                $sType = 'bx_sounds';
                $sMemAction = 'BX_SOUNDS_VIEW';
                break;
            default:
                return array();
        }

        if (!BxDolXMLRPCMedia::_isMembershipEnabledFor($iIdProfileViewer, $sMemAction))
            return array ();

        bx_import('BxDolAlbums');
        $o = new BxDolAlbums ($sType, (int)$iIdProfile);
        $aList = $o->getAlbumList (array('owner' => (int)$iIdProfile, 'show_empty' => $isShowEmptyAlbums), 1, 1000);
        $aRet = array ();
        foreach ($aList as $r)
        {
            if ($iIdProfile != $iIdProfileViewer && !BxDolService::call ($sModuleName, 'get_album_privacy', array((int)$r['ID'], $iIdProfileViewer), 'Search'))
                continue;

            $aRet[] = array (
                'Id' => $r['ID'],
                'Title' => $r['Caption'],
                'Num' => $r['ObjCount'],
            );
        }
        return $aRet;
    }

    // ----------------- file list in albums

    function getVideoInAlbum($sUser, $sPwd, $sNick, $iAlbumId)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        return BxDolXMLRPCMedia::_getFilesInAlbum ('videos', $iIdProfile, $iId, $iAlbumId, 'video', 'getToken', 'flash/modules/video/get_mobile.php?id=');
    }

    function getAudioInAlbum($sUser, $sPwd, $sNick, $iAlbumId)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        return BxDolXMLRPCMedia::_getFilesInAlbum ('sounds', $iIdProfile, $iId, $iAlbumId, 'mp3', 'getMp3Token', 'flash/modules/mp3/get_file.php?id=');
    }

    function _getFilesInAlbum ($sModuleName, $iIdProfile, $iIdProfileViewer, $iAlbumId, $sWidget = '', $sFuncToken = '', $sTokenUrl = '')
    {
        if ($sWidget && preg_match('/^[a-zA-Z0-9_]+$/', $sWidget)) {
            require_once (BX_DIRECTORY_PATH_ROOT . "flash/modules/global/inc/db.inc.php");
            require_once (BX_DIRECTORY_PATH_ROOT . "flash/modules/{$sWidget}/inc/header.inc.php");
            require_once (BX_DIRECTORY_PATH_ROOT . "flash/modules/{$sWidget}/inc/constants.inc.php");
            require_once (BX_DIRECTORY_PATH_ROOT . "flash/modules/{$sWidget}/inc/functions.inc.php");
        }

        $a = BxDolService::call ($sModuleName, 'get_files_in_album', array((int)$iAlbumId, $iIdProfileViewer != $iIdProfile, $iIdProfileViewer, array('per_page' => 100)), 'Search');
        if (!$a)
            return new xmlrpcval (array(), "array");
        foreach ($a as $k => $aRow)
        {
            if ('youtube' == $aRow['Source'])
            {
                $sUrl = $aRow['Video'];
            }
            else
            {
                $sToken = '';
                if ($sFuncToken)
                    $sToken = $sFuncToken($aRow['id']);

                $sUrl = $sTokenUrl && $sToken ? BX_DOL_URL_ROOT . $sTokenUrl . $aRow['id'] . '&token=' . $sToken : $aRow['file'];
            }

            $a = array (
                'id' => new xmlrpcval($aRow['id']),
                'title' => new xmlrpcval($aRow['title']),
                'desc' => new xmlrpcval(BxDolService::call ($sModuleName, 'get_length', array($aRow['size']), 'Search')),
                'icon' => new xmlrpcval($aRow['icon']),
                'thumb' => new xmlrpcval($aRow['thumb']),
                'file' => new xmlrpcval($sUrl),
                'cat' => new xmlrpcval($sCat),
                'rate' => new xmlrpcval($aRow['Rate']),
                'rate_count' => new xmlrpcval((int)$aRow['RateCount']),
            );
            $aFiles[] = new xmlrpcval($a, 'struct');
        }
        return new xmlrpcval ($aFiles, "array");
    }

    function _isMembershipEnabledFor ($iProfileId, $sMembershipActionConstant, $isPerformAction = false) {
        defineMembershipActions (array('photos add', 'photos view', 'sounds view', 'videos view'));
        if (!defined($sMembershipActionConstant))
            return false;
        $aCheck = checkAction($iProfileId ? $iProfileId : $_COOKIE['memberID'], constant($sMembershipActionConstant), $isPerformAction);
        return $aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }
}

?>
