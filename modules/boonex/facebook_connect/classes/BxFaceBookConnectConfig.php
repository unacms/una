<?php

    /***************************************************************************
    *                            Dolphin Smart Community Builder
    *                              -------------------
    *     begin                : Mon Mar 23 2006
    *     copyright            : (C) 2007 BoonEx Group
    *     website              : http://www.boonex.com
    * This file is part of Dolphin - Smart Community Builder
    *
    * Dolphin is free software; you can redistribute it and/or modify it under
    * the terms of the GNU General Public License as published by the
    * Free Software Foundation; either version 2 of the
    * License, or  any later version.
    *
    * Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
    * without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    * See the GNU General Public License for more details.
    * You should have received a copy of the GNU General Public License along with Dolphin,
    * see license.txt file; if not, write to marketing@boonex.com
    ***************************************************************************/

    //-- demo api and secret key --//
    // api      : 112808408740127;
    // secret   : 464f98fc9bcac09ca66fa5b8169c9657;

    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolConfig.php');

    class BxFaceBookConnectConfig extends BxDolConfig
    {
        var $mApiID;
        var $mApiSecret;

        var $sPageReciver;
        var $sSessionKey;
        var $sDefaultRedirectUrl;

        var $sFacebookSessionUid = 'facebook_session';
        var $sFaceBookAlternativePostfix;
        var $sRedirectPage;

        var $bAutoFriends;
        var $aFaceBookReqParams;

        var $sDefaultCountryCode = 'US';

        /**
         * Class constructor;
         */
        function BxFaceBookConnectConfig($aModule)
        {
            parent::BxDolConfig($aModule);

            $this -> mApiID          = getParam('bx_facebook_connect_api_key');
            $this -> mApiSecret   = getParam('bx_facebook_connect_secret');
            $this -> sPageReciver = BX_DOL_URL_ROOT . $this -> getBaseUri() . 'login';

            $this -> sDefaultRedirectUrl = BX_DOL_URL_ROOT . 'member.php';
            $this -> sFaceBookAlternativePostfix = '_fb';
            $this -> sRedirectPage = getParam('bx_facebook_connect_redirect_page');

            $this -> bAutoFriends = 'on' == getParam('bx_facebook_connect_auto_friends')
                ? true
                : false;

            $this -> aFaceBookReqParams = array(
                'req_perms' => 'email,user_hometown,user_birthday,user_interests
                    ,user_likes,user_location',
            );
        }
    }