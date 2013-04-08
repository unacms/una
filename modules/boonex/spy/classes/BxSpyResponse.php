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

    require_once(BX_DIRECTORY_PATH_CLASSES . 'BxDolAlerts.php');

    class BxSpyResponse extends BxDolAlertsResponse
    {
        var $_oModule;
        var $aInternalHandlers;

        /**
         * Constructor
         * @param  BxWallModule $oModule - an instance of current module
         */
        function BxSpyResponse($oModule) {
            parent::BxDolAlertsResponse();

            $this -> _oModule  = $oModule;
            $aInternalHandlers = $this -> _oModule -> _oDb -> getInternalHandlers();

            // procces all recived handlers;
            if( $aInternalHandlers && is_array($aInternalHandlers) ) {
                foreach($aInternalHandlers as $iKey => $aItems)
                {
                    $this -> aInternalHandlers[ $aItems['alert_unit'] . '_' . $aItems['alert_action'] ] = $aItems;
                }
            }
        }
        /**
         * Overwtire the method of parent class.
         *
         * @param BxDolAlerts $oAlert an instance of alert.
         */
        function response($oAlert)
        {
            $sKey = $oAlert -> sUnit . '_' . $oAlert -> sAction;

            // call defined method;
            if( array_key_exists($sKey, $this -> aInternalHandlers) ) {

                if( BxDolRequest::serviceExists($this -> aInternalHandlers[$sKey]['module_uri']
                        , $this -> aInternalHandlers[$sKey]['module_method']) ) {

                    // define functions parameters;
                    $aParams = array(
                        'action'       => $oAlert -> sAction,
                        'object_id'    => $oAlert -> iObject,
                        'sender_id'    => $oAlert -> iSender,
                        'extra_params' => $oAlert -> aExtras,
                    );

                    $aResult = BxDolService::call($this -> aInternalHandlers[$sKey]['module_uri']
                            , $this -> aInternalHandlers[$sKey]['module_method'], $aParams);

                    if($aResult) {
                        // create new event;
                        //define recipent id;
                        $iRecipientId = ( isset($aResult['recipient_id']) ) ? $aResult['recipient_id'] : $oAlert -> iObject;
                        if( isset($aResult['spy_type']) && $aResult['spy_type'] == 'content_activity'
                            && $iRecipientId == $oAlert -> iSender) {
                            $iRecipientId = 0;
                        }

                        $iEventId = 0;

                        if($oAlert -> iSender == 0 || !$oAlert -> iSender) {
                            if($this -> _oModule -> _oConfig -> bTrackGuestsActivites) {
                                $iEventId = $this -> _oModule -> _oDb -> createActivity($oAlert -> iSender, $iRecipientId, $aResult);
                            }
                        }
                        else {
                           $iEventId = $this -> _oModule -> _oDb -> createActivity($oAlert -> iSender, $iRecipientId, $aResult);
                        }

                        if($iEventId) {
                            // try to define all profile's friends;
                            $aFriends = getMyFriendsEx($oAlert -> iSender);
                            if( $aFriends && is_array($aFriends) ) {
                                foreach($aFriends as $iFriendId => $aItems)
                                {
                                    // attach event to friends;
                                    $this -> _oModule -> _oDb -> attachFriendEvent($iEventId, $oAlert -> iSender, $iFriendId);
                                }
                            }
                        }
                    }
                }
            }
        }
    }