<?php

    $GLOBALS['bx_profiler_disable'] = 1;

    include("../inc/header.inc.php");
    require_once(BX_DIRECTORY_PATH_INC . 'admin.inc.php');

    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCUtil.php');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCUser.php');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCMessages.php');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCSearch.php');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCFriends.php');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCMedia.php');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCImages.php');

    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/BxDolXMLRPCProfileView.php');

    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/lib/xmlrpc.inc');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/lib/xmlrpcs.inc');
    require_once(BX_DIRECTORY_PATH_ROOT . 'xmlrpc/lib/xmlrpc_wrappers.inc');

    define('BX_XMLRPC_PROTOCOL_VER', 2);

    $s = new xmlrpc_server(
        array(

            // util

            "dolphin.concat" => array(
                "function" => "BxDolXMLRPCUtil::concat",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "concat two strings",
            ),

            "dolphin.getContacts" => array(
                "function" => "BxDolXMLRPCUtil::getContacts",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get user contacts",
            ),

            "dolphin.getCountries" => array(
                "function" => "BxDolXMLRPCUtil::getCountries",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get countries list",
            ),

            // user related

            "dolphin.login" => array(
                "function" => "BxDolXMLRPCUser::login",
                "signature" => array (array ($xmlrpcInt, $xmlrpcString, $xmlrpcString)),
                "docstring" => "returns user id on success or 0 if login failed",
            ),
            "dolphin.login2" => array(
                "function" => "BxDolXMLRPCUser::login2",
                "signature" => array (array ($xmlrpcInt, $xmlrpcString, $xmlrpcString)),
                "docstring" => "returns user id on success or 0 if login failed (v.2)",
            ), 
            "dolphin.getHomepageInfo" => array(
                "function" => "BxDolXMLRPCUser::getHomepageInfo",
                "signature" => array (array ($xmlrpcStruct, $xmlrpcString, $xmlrpcString)),
                "docstring" => "return logged in user information to dispay on homepage",
            ),
            "dolphin.getHomepageInfo2" => array(
                "function" => "BxDolXMLRPCUser::getHomepageInfo2",
                "signature" => array (array ($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "return logged in user information to dispay on homepage (v.2)",
            ),
            "dolphin.getUserInfo" => array(
                "function" => "BxDolXMLRPCUser::getUserInfo",
                "signature" => array (array ($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "return user information",
            ),
            "dolphin.getUserInfo2" => array(
                "function" => "BxDolXMLRPCUser::getUserInfo2",
                "signature" => array (array ($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "return user information (v.2)",
             ),
            "dolphin.getUserInfoExtra" => array(
                "function" => "BxDolXMLRPCUser::getUserInfoExtra",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "return extended users information",
            ),

            "dolphin.updateStatusMessage" => array(
                "function" => "BxDolXMLRPCUser::updateStatusMessage",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "update user status message, returns 0 on error, or 1 on success",
            ),

            "dolphin.getUserLocation" => array(
                "function" => "BxDolXMLRPCUser::getUserLocation",
                "signature" => array (array ($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get user location, returns struct on succees, 0 on error, -1 on access denied",
            ),

            "dolphin.updateUserLocation" => array(
                "function" => "BxDolXMLRPCUser::updateUserLocation",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "update user location, returns 1 on succees, 0 on error",
            ),

            // messages

            "dolphin.getMessagesInbox" => array(
                "function" => "BxDolXMLRPCMessages::getMessagesInbox",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get user's inbox messages",
            ),
            "dolphin.getMessagesSent" => array(
                "function" => "BxDolXMLRPCMessages::getMessagesSent",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get user's sent messages",
            ),
            "dolphin.getMessageInbox" => array(
                "function" => "BxDolXMLRPCMessages::getMessageInbox",
                "signature" => array (array ($xmlrpcStruct, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get user's inbox message",
            ),
            "dolphin.getMessageSent" => array(
                "function" => "BxDolXMLRPCMessages::getMessageSent",
                "signature" => array (array ($xmlrpcScruct, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get user's sent message",
            ),

            "dolphin.sendMessage" => array(
                "function" => "BxDolXMLRPCMessages::sendMessage",
                "signature" => array (array ($xmlrpcScruct, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "send message",
            ),

            // search

            "dolphin.getSearchResultsLocation" => array(
                "function" => "BxDolXMLRPCSearch::getSearchResultsLocation",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get search results by location",
            ),
            "dolphin.getSearchResultsKeyword" => array(
                "function" => "BxDolXMLRPCSearch::getSearchResultsKeyword",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString,$xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get search results by keyword",
            ),
            "dolphin.getSearchResultsNearMe" => array(
                "function" => "BxDolXMLRPCSearch::getSearchResultsNearMe",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get search results near specified location",
            ),

            // friends

            "dolphin.getFriends" => array(
                "function" => "BxDolXMLRPCFriends::getFriends",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get user's friends",
            ),
            "dolphin.getFriendRequests" => array(
                "function" => "BxDolXMLRPCFriends::getFriendRequests",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get friend requests",
            ),
            "dolphin.declineFriendRequest" => array(
                "function" => "BxDolXMLRPCFriends::declineFriendRequest",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "decline friend request",
            ),
            "dolphin.acceptFriendRequest" => array(
                "function" => "BxDolXMLRPCFriends::acceptFriendRequest",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "accept friend request",
            ),
            "dolphin.removeFriend" => array(
                "function" => "BxDolXMLRPCFriends::removeFriend",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "remove friend",
            ),
            "dolphin.addFriend" => array(
                "function" => "BxDolXMLRPCFriends::addFriend",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "add friend",
            ),

            // images
/*
            "dolphin.getImages" => array(
                "function" => "BxDolXMLRPCImages::getImages",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get profile's images",
            ),
*/
            "dolphin.removeImage" => array(
                "function" => "BxDolXMLRPCImages::removeImage",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "remove user image by id",
            ),
            "dolphin.makeThumbnail" => array(
                "function" => "BxDolXMLRPCImages::makeThumbnail",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "make primary image by image id",
            ),
            "dolphin.getImageAlbums" => array(
                "function" => "BxDolXMLRPCImages::getImageAlbums",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get profile's images albums",
            ),
            "dolphin.uploadImage" => array(
                "function" => "BxDolXMLRPCImages::uploadImage",
                "signature" => array (array ($xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcBase64, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "upload new image",
            ),

            // media


            "dolphin.getAudioAlbums" => array(
                "function" => "BxDolXMLRPCMedia::getAudioAlbums",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get profile's sound albums",
            ),
            "dolphin.getVideoAlbums" => array(
                "function" => "BxDolXMLRPCMedia::getVideoAlbums",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get profile's video albums",
            ),
            "dolphin.getImagesInAlbum" => array(
                "function" => "BxDolXMLRPCImages::getImagesInAlbum",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get profile's images in specified album",
            ),
            "dolphin.getVideoInAlbum" => array(
                "function" => "BxDolXMLRPCMedia::getVideoInAlbum",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get profile's video in specified album",
            ),
            "dolphin.getAudioInAlbum" => array(
                "function" => "BxDolXMLRPCMedia::getAudioInAlbum",
                "signature" => array (array ($xmlrpcArray, $xmlrpcString, $xmlrpcString, $xmlrpcString, $xmlrpcString)),
                "docstring" => "get profile's sounds in specified album",
            ),
        ),
        0
    );

    $s->functions_parameters_type = 'phpvals';
    $GLOBALS['xmlrpc_internalencoding'] = 'UTF-8';
    $s->service();

?>
