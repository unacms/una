<?php

    class BxFaceBookConnectAlerts extends BxDolAlertsResponse
    {
        var $oModule;

        /**
         * Class constructor;
         */
        function BxFaceBookConnectAlerts() {
            $this -> oModule = BxDolModule::getInstance('bx_facebook_connect');
        }

        function response(&$o)
        {
            if ( $o -> sUnit == 'profile' ) {
                switch ( $o -> sAction ) {
                    case 'logout' :
                        //delete facebook's session cookie
                        $sCookieName = $this -> oModule -> oFacebook -> getSessionCookieName();
                        setcookie($sCookieName, '', time() - 96 * 3600, '/' );
                        unset($_COOKIE[$sCookieName]);
                        break;

                    case 'join' :
                            bx_import('BxDolSession');
                            $oSession = BxDolSession::getInstance();

                            $iFacebookProfileUid = $oSession
                                -> getValue($this -> oModule -> _oConfig -> sFacebookSessionUid);

                            if($iFacebookProfileUid) {
                                $oSession -> unsetValue($this -> oModule -> _oConfig -> sFacebookSessionUid);

                                //save Fb's uid
                                $this -> oModule -> _oDb -> saveFbUid($o -> iObject, $iFacebookProfileUid);

                                //Auto-friend members if they are already friends on Facebook
                                $this -> oModule -> _makeFriends($o -> iObject);
                            }
                        break;

                    case 'delete' :
                        //remove Fb account
                        $this -> oModule -> _oDb -> deleteFbUid($o -> iObject);
                        break;

                    default :
                }
            }
        }
    }
