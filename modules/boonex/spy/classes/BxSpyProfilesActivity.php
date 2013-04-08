<?php

    class BxSpyProfilesActivity extends BxDolAlertsResponse
    {
        var $oSpy;

        function response(&$o)
        {
            $iActivityId  = 0;
            $iSenderId    = $o -> iSender;
            $iRecipientId = $o -> iObject;
            $aParams      = array();

            // get new instance;
            $this -> oSpy = BxDolModule::getInstance('bx_spy');

            // define profile's nickname;
            $sNickName      = getNickName($iRecipientId);
            $sProfileLink   = getProfileLink($iRecipientId);

            if ($o -> sUnit == 'profile') {
                switch ( $o -> sAction ) {
                    case 'join' :
                        $aParams = array(
                            'lang_key'  => '_bx_spy_profile_has_joined',
                            'params'    => array(
                                'profile_link' => $sProfileLink,
                                'profile_nick' => $sNickName,
                            ),
                        );
                        $iSenderId = $o -> iObject;
                        $iRecipientId = 0;
                        break;

                    case 'edit' :
                        $aParams = array(
                            'lang_key'  => '_bx_spy_profile_has_edited',
                            'params'    => array(
                                'profile_link' => $sProfileLink,
                                'profile_nick' => $sNickName,
                            ),
                        );
                        $iRecipientId = 0;
                        break;

                    case 'commentPost' :
                        if($iSenderId != $iRecipientId) {
                            $aSenderInfo          = $this -> _getSenderInfo($iSenderId);
                            $sSenderNickName      = $aSenderInfo['NickName'];
                            $sSenderProfileLink   = $aSenderInfo['Link'];

                            $aParams = array(
                                'lang_key'  => '_bx_spy_profile_has_commented',
                                'params'    => array(
                                    'sender_p_link' => $sSenderProfileLink,
                                    'sender_p_nick' => $sSenderNickName,

                                    'recipient_p_link' => $sProfileLink,
                                    'recipient_p_nick' => $sNickName,
                                ),
                            );
                        }
                        break;

                    case 'rate' :
                        if($iSenderId != $iRecipientId) {
                            $aSenderInfo          = $this -> _getSenderInfo($iSenderId);
                            $sSenderNickName      = $aSenderInfo['NickName'];
                            $sSenderProfileLink   = $aSenderInfo['Link'];

                            $aParams = array(
                                'lang_key'  => '_bx_spy_profile_has_rated',
                                'params'    => array(
                                    'sender_p_link' => $sSenderProfileLink,
                                    'sender_p_nick' => $sSenderNickName,

                                    'recipient_p_link' => $sProfileLink,
                                    'recipient_p_nick' => $sNickName,
                                ),
                            );
                        }
                        break;

                    case 'view' :
                        if($iSenderId != $iRecipientId) {
                            $aSenderInfo          = $this -> _getSenderInfo($iSenderId);
                            $sSenderNickName      = $aSenderInfo['NickName'];
                            $sSenderProfileLink   = $aSenderInfo['Link'];

                            $aParams = array(
                                'lang_key'  => '_bx_spy_profile_has_viewed',
                                'params'    => array(
                                    'sender_p_link' => $sSenderProfileLink,
                                    'sender_p_nick' => $sSenderNickName,

                                    'recipient_p_link' => $sProfileLink,
                                    'recipient_p_nick' => $sNickName,
                                ),
                            );
                        }
                        break;
                }
            }

            if($o -> sUnit == 'friend') {
                switch ( $o -> sAction ) {
                    case 'request' :
                        if($iSenderId != $iRecipientId) {
                            $aRecipientInfo          = $this -> _getSenderInfo($iSenderId);
                            $sRecipientNickName      = $aRecipientInfo['NickName'];
                            $sRecipientProfileLink   = $aRecipientInfo['Link'];

                            $aParams = array(
                                'lang_key'  => '_bx_spy_profile_friend_request',
                                'params'    => array(
                                    'sender_p_link' => $sProfileLink,
                                    'sender_p_nick' => $sNickName,

                                    'recipient_p_link' => $sRecipientProfileLink,
                                    'recipient_p_nick' => $sRecipientNickName,
                                ),
                            );
                        }
                        break;

                    case 'accept' :
                        if($iSenderId != $iRecipientId) {
                            $aSenderInfo          = $this -> _getSenderInfo($iSenderId);
                            $sSenderNickName      = $aSenderInfo['NickName'];
                            $sSenderProfileLink   = $aSenderInfo['Link'];

                            $aParams = array(
                                'lang_key'  => '_bx_spy_profile_friend_accept',
                                'params'    => array(
                                    'sender_p_link' => $sProfileLink,
                                    'sender_p_nick' => $sNickName,

                                    'recipient_p_link' => $sSenderProfileLink,
                                    'recipient_p_nick' => $sSenderNickName,
                                ),
                            );
                        }
                        break;
                }

            }

            if($aParams) {
                // create new activity;
                $aParams['spy_type'] = 'profiles_activity';

                if($iSenderId == 0) {
                    if($this -> oSpy -> _oConfig -> bTrackGuestsActivites) {
                        $iActivityId = $this -> oSpy -> _oDb -> createActivity($iSenderId, $iRecipientId, $aParams);
                    }
                }
                else {
                   $iActivityId = $this -> oSpy -> _oDb -> createActivity($iSenderId, $iRecipientId, $aParams);
                }

                if($iActivityId) {
                    // try to define all profile's friends;
                    $aFriends = getMyFriendsEx($iSenderId);
                    if( $aFriends && is_array($aFriends) ) {
                        foreach($aFriends as $iFriendId => $aItems)
                        {
                            // attach activity to friend;
                            $this -> oSpy -> _oDb -> attachFriendEvent($iActivityId, $iSenderId, $iFriendId);
                        }
                    }
                }

            }
        }

        /**
         * Function will define sender's nickname;
         *
         * @return : (array) - sender's some info;
         */
        function _getSenderInfo($iSenderId)
        {
            // define profile's nickname;
            $sSenderNickName = getNickName($iSenderId);
            // defile profile's link;
            $sSenderLink     = $sSenderNickName ? getProfileLink($iSenderId) : 'javascript:void(0)';

            !$sSenderNickName  ? $sSenderNickName = _t('_Guest') : null;

            $aRet = array(
                'NickName' => $sSenderNickName,
                'Link'     => $sSenderLink,
            );

           return $aRet;
        }
    }
