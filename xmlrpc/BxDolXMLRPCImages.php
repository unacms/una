<?php

class BxDolXMLRPCImages extends BxDolXMLRPCMedia
{
/*
    function getImages($sUser, $sPwd, $sNick)
    {
        $sProfileCat = BxDolService::call('bx_photos', 'get_profile_cat', array());
        return BxDolXMLRPCImages::getImagesInCategory($sUser, $sPwd, $sNick, $sProfileCat);
    }
*/

    function removeImage ($sUser, $sPwd, $iImageId)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        if (BxDolService::call('photos', 'remove_object', array((int)$iImageId)))
            return new xmlrpcval ("ok");
        return new xmlrpcval ("fail");
    }

    function makeThumbnail ($sUser, $sPwd, $iImageId)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        if (BxDolService::call('avatar', 'make_avatar_from_shared_photo_auto', array((int)$iImageId)))
            return new xmlrpcval ("ok");
        return new xmlrpcval ("fail");
    }

    function getImageAlbums ($sUser, $sPwd, $sNick)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        // create user's default album if there is no one
        if ($sUser == $sNick) {
            $sCaption = str_replace('{nickname}', $sUser, getParam('bx_photos_profile_album_name'));
            bx_import('BxDolAlbums');
            $oAlbum = new BxDolAlbums('bx_photos');
            $aData = array(
                'caption' => $sCaption,
                'location' => _t('_bx_photos_undefined'),
                'owner' => $iId,
                'AllowAlbumView' => BX_DOL_PG_ALL,
            );
            $oAlbum->addAlbum($aData);
        } 

        return BxDolXMLRPCMedia::_getMediaAlbums ('photo', $iIdProfile, $iId, $iIdProfile == $iId);
    }

    function uploadImage ($sUser, $sPwd, $sAlbum, $binImageData, $iDataLength, $sTitle, $sTags, $sDesc)
    {
        if (!($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        if (!BxDolXMLRPCMedia::_isMembershipEnabledFor($iIdProfileViewer, 'BX_PHOTOS_ADD', true))
            return new xmlrpcval ("fail access");

        // write tmp file

        $sTmpFilename = BX_DIRECTORY_PATH_ROOT . "tmp/" . time() . '_' . $iId;
        $f = fopen($sTmpFilename, "wb");
        if (!$f)
            return new xmlrpcval ("fail fopen");
        if (!fwrite ($f, $binImageData, (int)$iDataLength))
        {
            fclose($f);
            return new xmlrpcval ("fail write");
        }
        fclose($f);

        // upload
        $aFileInfo = array();
        $aFileInfo['medTitle'] = $sTitle;
        $aFileInfo['medDesc'] = $sDesc;
        $aFileInfo['medTags'] = $sTags;
        $aFileInfo['Categories'] = array ($sAlbum);
        $aFileInfo['album'] = $sAlbum;

        $isUpdateThumb = (int)db_value("SELECT `Avatar` FROM `Profiles` WHERE `ID` = '$iId' LIMIT 1") ? false : true;

        if (BxDolService::call('photos', 'perform_photo_upload', array($sTmpFilename, $aFileInfo, $isUpdateThumb, $iId), 'Uploader'))
            return new xmlrpcval ("ok");
        else
            return new xmlrpcval ("fail upload");
    }

    function getImagesInAlbum($sUser, $sPwd, $sNick, $iAlbumId)
    {
        $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);
        if (!$iIdProfile || !($iId = BxDolXMLRPCUtil::checkLogin ($sUser, $sPwd)))
            return new xmlrpcresp(new xmlrpcval(array('error' => new xmlrpcval(1,"int")), "struct"));

        return BxDolXMLRPCMedia::_getFilesInAlbum ('photos', $iIdProfile, $iId, (int)$iAlbumId);
    }

}

?>
