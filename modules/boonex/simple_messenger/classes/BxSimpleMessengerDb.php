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

    require_once( BX_DIRECTORY_PATH_CLASSES . 'BxDolModuleDb.php' );

    class BxSimpleMessengerDb extends BxDolModuleDb
    {
        var $_oConfig;

        var $sTablePrefix;

        /**
         * Constructor.
         */
        function BxSimpleMessengerDb(&$oConfig)
        {
            parent::BxDolModuleDb();

            $this -> _oConfig = $oConfig;
            $this -> sTablePrefix = $oConfig -> getDbPrefix();
        }

        /**
         * Function will create new message ;
         *
         * @param : $iSenderId (integer)    - sender Id;
         * @param : $iRecipientId (integer) - recipient Id;
         * @param : $sMessage (string)      - message text;
         * @return  : (integer) - number of affected rows ;
         */
        function createMessage($iSenderId, $iRecipientId, $sMessage)
        {
            // procces vars
            $iSenderId = (int) $iSenderId;
            $iRecipientId = (int) $iRecipientId;
            $sMessage = process_db_input($sMessage, BX_TAGS_NO_ACTION);

            $sQuery =
            "
                INSERT INTO
                    `{$this -> sTablePrefix}messages`
                SET
                    `SenderID`      = {$iSenderId},
                    `RecipientID`   = {$iRecipientId},
                    `Message`       = '{$sMessage}'
            ";

            return $this -> query($sQuery);
        }

        /**
         * Function will close chat window;
         *
         * @param   : $iLoggedMember (integer) - current's logged member;
         * @param   : $iRecipientId (integer) - recepient's Id;
         * @return  : (integer) - number of affected rows ;
         */
        function closeChatWindow($iRecipientId, $iLoggedMember)
        {
            $iRecipientId = (int) $iRecipientId;
            $iLoggedMember = (int) $iLoggedMember;

            // define the sender's id;
            $sQuery =
            "
                SELECT
                    `SenderID`
                FROM
                    `{$this -> sTablePrefix}messages`
                WHERE
                    (
                        `SenderID` = {$iLoggedMember}
                            AND
                        `RecipientID` = {$iRecipientId}
                    )
                        OR
                    (
                        `SenderID` = {$iRecipientId}
                            AND
                        `RecipientID` = {$iLoggedMember}
                    )
                ORDER BY
                    `Date` DESC
                LIMIT 1
            ";

            $iSenderId = $this -> getOne($sQuery);
            $sFieldId  = ($iSenderId == $iLoggedMember) ? 'SenderStatus' : 'RecipientStatus';

            $sQuery =
            "
                UPDATE
                    `{$this -> sTablePrefix}messages`
                SET
                    `{$sFieldId}` = 'close'
                WHERE
                    (
                        `SenderID` = {$iLoggedMember}
                            AND
                        `RecipientID` = {$iRecipientId}
                    )
                        OR
                    (
                        `SenderID` = {$iRecipientId}
                            AND
                        `RecipientID` = {$iLoggedMember}
                    )
                ORDER BY
                    `Date` DESC
                LIMIT 1
            ";

            return $this -> query($sQuery);
        }

        /**
         * Function will delete profile's history;
         *
         * @param  : $iProfileId (integer) - profile's Id;
         * @return : void;
         */
        function deleteAllMessagesHistory($iProfileId)
        {
            $iProfileId = (int) $iProfileId;

            $sQuery =
            "
                DELETE FROM
                    `{$this -> sTablePrefix}messages`
                WHERE
                    `SenderID` = {$iProfileId}
                        OR
                    `RecipientID` = {$iProfileId}
            ";

            $this -> query($sQuery);
        }

        /**
         * Function will delete messages history ;
         *
         * @param  : $iSender (integer)         - sender member's Id;
         * @param  : $iRecipient (integer)      - recipient member's Id;
         * @param  : $iAllowCountMessages integer;
         *
         */
        function deleteMessagesHistory($iSender, $iRecipient, $iAllowCountMessages)
        {
            $iSender = (int) $iSender;
            $iRecipient = (int) $iRecipient;
            $iAllowCountMessages = (int) $iAllowCountMessages;

            $sQuery =
            "
                SELECT
                    COUNT(*)
                FROM
                    `{$this -> sTablePrefix}messages`
                WHERE
                    (
                        `SenderID` = {$iSender}
                            AND
                        `RecipientID` = {$iRecipient}
                    )
                        OR
                    (
                        `SenderID` = {$iRecipient}
                            AND
                        `RecipientID` = {$iSender}
                    )
            ";

            $iMessageCount = (int) $this -> getOne($sQuery);
            if ( $iMessageCount > $iAllowCountMessages ) {
                // delete all unnecessary messages ;
                $iRowsDelete = $iMessageCount - $iAllowCountMessages;

                $sQuery =
                "
                    DELETE FROM
                        `{$this -> sTablePrefix}messages`
                    WHERE
                        (
                            `SenderID` = {$iSender}
                                AND
                            `RecipientID` = {$iRecipient}
                        )
                            OR
                        (
                            `SenderID` = {$iRecipient}
                                AND
                            `RecipientID` = {$iSender}
                        )
                    ORDER BY `ID`
                    LIMIT {$iRowsDelete}
                ";

                $this -> query($sQuery);
            }
        }

        /**
         * Function will get the last message's id for current chat box;
         *
         * @param  : $iSender (integer)         - sender member's Id;
         * @param  : $iRecipient (integer)      - recipient member's Id;
         * @return : (integer) - the last message's id;
         */
        function getLastMessagesId($iRecipient, $iSender)
        {
            $iRecipient = (int) $iRecipient;
            $iSender = (int) $iSender;

            $sQuery =
            "
                SELECT
                    `ID`
                FROM
                    `{$this -> sTablePrefix}messages`
                WHERE
                    (
                        `SenderID` = {$iSender}
                            AND
                        `RecipientID` = {$iRecipient}
                            OR
                        `SenderID` = {$iRecipient}
                            AND
                        `RecipientID` = {$iSender}
                    )
                ORDER BY
                    `ID` DESC
                LIMIT 1
            ";

            return $this -> getOne($sQuery);
        }

        /**
         * Function will get count of user's active chat boxes;
         *
         * @param  : $iSender (integer) - sender's id;
         * @return : (array)  - return array with all sender's chat boxes (recipients id);
                        [RecipientID] - (string)  recipient's Id;
         */
        function getChatBoxesCount($iSender)
        {
            $iSender = (int) $iSender;

            $sQuery =
            "
                SELECT
                    DISTINCT IF(`{$this -> sTablePrefix}messages`.`SenderID` = {$iSender}, `{$this -> sTablePrefix}messages`.`RecipientID`, `{$this -> sTablePrefix}messages`.`SenderID`) AS `RecipientID`
                FROM
                    `{$this -> sTablePrefix}messages`
                INNER JOIN
                    `Profiles`
                ON
                    `Profiles`.`ID` = {$iSender}
                WHERE
                    `{$this -> sTablePrefix}messages`.`RecipientID` = {$iSender}
                        OR
                    `{$this -> sTablePrefix}messages`.`SenderID` = {$iSender}
            ";

            $aSenders = $this -> getAll($sQuery);
            $aProcessedSenders = array();

           // procces all recived id;
            foreach($aSenders as $iKey => $aItems)
            {
                $aItems['RecipientID'] = (int) $aItems['RecipientID'];

                $sQuery =
                "
                    SELECT
                        IF(`SenderID` = {$aItems['RecipientID']}, `SenderStatus`, `RecipientStatus`) AS `Status`
                    FROM
                        `{$this -> sTablePrefix}messages`
                    WHERE
                        (
                            `RecipientID` = {$aItems['RecipientID']}
                                AND
                            `SenderID` = {$iSender}
                        )
                            OR
                        (
                            `RecipientID` = {$iSender}
                                AND
                            `SenderID` = {$aItems['RecipientID']}
                        )
                        ORDER BY
                            `Date` DESC
                        LIMIT 1
                ";

                if($this -> getOne($sQuery)!= 'close') {
                    $aProcessedSenders[] = $aItems['RecipientID'];
                }
            }

            return $aProcessedSenders;
        }

        /**
         * Function will get the chat box's number of messages;
         *
         * @param  : $iSender (integer)         - sender member's Id;
         * @param  : $iRecipient (integer)      - recipient member's Id;
         * @return : (integer) - number of messages;
         */
        function getMessagesCount($iRecipient, $iSender)
        {
            $iRecipient = (int) $iRecipient;
            $iSender = (int) $iSender;

            $sQuery =
            "
                SELECT
                    COUNT(*)
                FROM
                    `{$this -> sTablePrefix}messages`
                WHERE
                    (
                        `SenderID` = {$iSender}
                            AND
                        `RecipientID` = {$iRecipient}
                    )
                        OR
                    (
                        `SenderID` = {$iRecipient}
                            AND
                        `RecipientID` = {$iSender}
                    )
            ";

            return $this -> getOne($sQuery);
        }

        /**
         * Function will generate member's messages history ;
         *
         * @param  : $aCoreSettings (array)     - chat's core settings;
         * @param  : $iSender (integer)         - sender member's Id;
         * @param  : $iRecipient (integer)      - recipient member's Id;
         * @param  : $iLastMessageId (integer)  - last message's Id (query will return all rows after this value);
         * @param  : $iMessageLimit (integer)   - rows limit ;
         * @return : array;
                [ ID ]          - (integer) message's Id ;
                [ Message ]     - (string)  message string ;
                [ SenderID ]    - (integer) message's sender Id ;
                [ RecipientID ] - (integer) message's recipient Id ;
                [ Date ]        - (string)  when message was created ;
         */
        function getHistoryList(&$aCoreSettings, $iRecipient, $iSender, $iLastMessageId = 0, $iMessageLimit = 0)
        {
            $iRecipient     = (int) $iRecipient;
            $iSender        = (int) $iSender;
            $iLastMessageId = (int) $iLastMessageId;
            $iMessageLimit     = (int) $iMessageLimit;

            // define the rows limit ;
            $sRowsLimit = ( $iMessageLimit ) ? " LIMIT {$iMessageLimit}" : null;

            // check if chat history is enabled now;
            if($aCoreSettings['save_chat_history'] && !$sRowsLimit){

                $iMessagesCount = $this -> getMessagesCount($iRecipient, $iSender);
                $iLimitFrom     = $iMessagesCount - $aCoreSettings['number_visible_messages'];
                $sRowsLimit     = " LIMIT {$iLimitFrom}, 18446744073709551615";
            }

            $sQuery =
            "
                SELECT
                   `ID`, `Message`, `SenderID`,
                   `RecipientID`, DATE_FORMAT(`Date`, '{$aCoreSettings['message_date_format']}') AS `Date`
                FROM
                    `{$this -> sTablePrefix}messages`
                WHERE
                    (
                        (
                           `SenderID` = {$iSender}
                                AND
                            `RecipientID` = {$iRecipient}
                        )
                                OR
                        (
                            `SenderID` = {$iRecipient}
                                AND
                            `RecipientID` = {$iSender}
                        )
                    )
                        AND
                    (
                        `ID` > {$iLastMessageId}
                    )
                ORDER BY
                    `ID`
                {$sRowsLimit}
            ";

            return $this -> getAll($sQuery);
        }

        /**
         * Function will generate list of members;
         *
         * @param  : $iRecipientId (integer) - recipient Id ;
         * @param  : $aRegBoxes (array) - registered messages box;
         * @return : (array) - with members id ;
                        [SenderID] (integer) - sender's id;
         */
        function getNewChatBoxes( $iRecipientId, $aRegBoxes = array() )
        {
            $iRecipientId = (int) $iRecipientId;

            // define registered chat boxes;
            $sFilter = '';
            if ( $aRegBoxes && is_array($aRegBoxes) ) {
                foreach( $aRegBoxes  as $iKey => $aItem )
                {
                    $iKey = (int) $iKey;
                    $sFilter .= " AND (`{$this -> sTablePrefix}messages`.`SenderID` <> {$iKey}  AND  `{$this -> sTablePrefix}messages`.`RecipientID` <> {$iKey})";
                }
            }

            $sQuery =
            "
                SELECT
                    DISTINCT IF(`{$this -> sTablePrefix}messages`.`SenderID` = {$iRecipientId},  `{$this -> sTablePrefix}messages`.`RecipientID`,  `{$this -> sTablePrefix}messages`.`SenderID`) AS `RecipientID`
                FROM
                    `{$this -> sTablePrefix}messages`
                INNER JOIN
                    `Profiles`
                ON
                    `Profiles`.`ID` = `RecipientID`
                WHERE
                (
                    `{$this -> sTablePrefix}messages`.`RecipientID` = {$iRecipientId}
                        OR
                    `{$this -> sTablePrefix}messages`.`SenderID` = {$iRecipientId}
                )
                    {$sFilter}
            ";

            $aSenders = $this -> getAll($sQuery);
            $aProcessedSenders = array();

           // procces all recived id;
            foreach($aSenders as $iKey => $aItems)
            {
                $aItems['RecipientID'] = (int) $aItems['RecipientID'];

                $sQuery =
                "
                    SELECT
                        IF(`SenderID` = {$aItems['RecipientID']}, `SenderStatus`, `RecipientStatus`) AS `Status`
                    FROM
                        `{$this -> sTablePrefix}messages`
                    WHERE
                        (
                            `RecipientID` = {$aItems['RecipientID']}
                                AND
                            `SenderID` = {$iRecipientId}
                        )
                            OR
                        (
                            `RecipientID` = {$iRecipientId}
                                AND
                            `SenderID` = {$aItems['RecipientID']}
                        )
                        ORDER BY
                            `Date` DESC
                        LIMIT 1
                ";

                if($this -> getOne($sQuery)!= 'close') {
                    $aProcessedSenders[] = $aItems['RecipientID'];
                }
            }

            return $aProcessedSenders;
        }

        /**
         * Function will create member's privacy group;
         *
         * @param : $iMemberId (integer)    - member's Id;
         * @param : $iGroupValue (integer)  - privacy group's value;
         */
        function createPrivacyGroup($iMemberId, $iGroupValue = 0)
        {
            $iMemberId = (int) $iMemberId;
            $iGroupValue = (int) $iGroupValue;

            $sQuery = "SELECT COUNT(*) FROM `{$this -> sTablePrefix}privacy` WHERE `author_id` = {$iMemberId}";
            if( $this -> getOne($sQuery) ) {
                // update existeng';
                $sQuery = "UPDATE `{$this -> sTablePrefix}privacy` SET `allow_contact_to` = {$iGroupValue} WHERE `author_id` = {$iMemberId}";
                $this -> query($sQuery);
            }
            else {
                // create new;
                $sQuery = "INSERT INTO `{$this -> sTablePrefix}privacy` SET `allow_contact_to` = {$iGroupValue}, `author_id` = {$iMemberId}";
                $this -> query($sQuery);
            }
        }

        /**
         * Function will get privacy group value for member's Id;
         *
         * @param  : $iMemberId (integer)    - member's Id;
         * @return : (integer);
         */
        function getPrivacyGroupValue($iMemberId)
        {
            $iMemberId = (int) $iMemberId;

            $sQuery = "SELECT `allow_contact_to` FROM `{$this -> sTablePrefix}privacy` WHERE `author_id` = {$iMemberId}";
            return $this -> getOne($sQuery);
        }

        /**
         * Function will protect received data with backlashes ;
         *
         * @param  : $sData (string) - text data ;
         * @return : (string) - protected data ;
         */
        function shieldData($sData)
        {
            return process_db_input($sData, BX_TAGS_NO_ACTION);
        }
    }