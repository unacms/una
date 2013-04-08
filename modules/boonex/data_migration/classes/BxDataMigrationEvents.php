<?php

    require_once 'BxDataMigrationData.php';

    class BxDataMigrationEvents extends BxDataMigrationData
    {
        var $sTablePrefix = 'bx_events_';
        var $iTransffered = 0;

        // default values for the fields which are not match with old ones
        var $aDefValues = array (
            'EventMembershipFilter' => '',
            'allow_view_event_to' => '3', // 3 - public (all)
            'allow_view_participants_to' => '3', // 3 - public (all)
            'allow_comment_to' => '3', // 3 - public (all)
            'allow_rate_to' => '3', // 3 - public (all)
            'allow_join_to' => '3', // 3 - public (all)
            'allow_post_in_forum_to' => 'p', //  p - event participants only
            'JoinConfirmation' => '0', // 0 - disabled
            'allow_upload_photos_to' => 'a', // a - event admin anly
            'allow_upload_videos_to' => 'a', // a - event admin anly
            'allow_upload_sounds_to' => 'a', // a - event admin anly
            'allow_upload_files_to' => 'a', // a - event admin anly
        );

        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationEvents(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        }

        /**
         * Function migrate all events;
         *
         * @return : (integer) operation result;
         */
        function getMigration()
        {
            if(!$this -> oDolModule) {
                 $this -> setResultStatus('System error: object instance is not received');
                 return MIGRATION_FAILED;
            }

            // set new status;
            $this -> setResultStatus('Events are transfering now');

            mysql_query('SET NAMES utf8', $this->rOldDb);

            // transfer all profiles polls;
            $sError = $this -> process();
            if($sError) {
                $this -> setResultStatus($sError);
                return MIGRATION_FAILED;
            }

            // set as finished;
            $this -> setResultStatus('Events were successfully transferred (' .  $this -> iTransffered . ' items)');

            return MIGRATION_SUCCESSFUL;
        }

        /**
         * Function will transfer all profile's polls;
         *
         * @return : (string) - error message or empty;
         */
        function process()
        {
            $sTableMainNew = $this -> sTablePrefix . $this->oDolModule->_oDb->_sTableMain;
            if ($this->oMigrationModule->_oDb->getOne("SELECT COUNT(*) FROM `{$sTableMainNew}`") > 0)
               return 'Error: it is possible to transfaer data to clean install only, in your case some existing data was found.';


            $sQuery = "SELECT * FROM `SDatingEvents`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while($r = mysql_fetch_assoc($rResult))
            {
                $r = $this -> escapeData($r);

                //count participants
                $iParticipants = $this -> getParticipantsCount($r['ID']);

                $sStatus = $r['Status'] == 'Active' ? 'approved' : 'pending';

                // execute query;
                $sQuery =
                "
                    INSERT INTO
                        `$sTableMainNew`
                    SET
                        `ID` = {$r['ID']},
                        `Title` = '{$r['Title']}',
                        `EntryUri` = '{$r['EntryUri']}',
                        `Description` = '{$r['Description']}',
                        `Status` = '{$sStatus}',
                        `Country` = '{$r['Country']}',
                        `City` = '{$r['City']}',
                        `Place` = '{$r['Place']}',
                        `PrimPhoto` = '',
                        `Date` = UNIX_TIMESTAMP('{$r['EventStart']}'),
                        `EventStart` = UNIX_TIMESTAMP('{$r['EventStart']}'),
                        `EventEnd` = UNIX_TIMESTAMP('{$r['EventStart']}'),
                        `ResponsibleID` = '{$r['ResponsibleID']}',
                        `EventMembershipFilter` = '{$this->aDefValues['EventMembershipFilter']}',
                        `Tags` = '{$r['Tags']}',
                        `Categories` = '',
                        `Views` = '0',
                        `Rate` = '0',
                        `RateCount` = '0',
                        `CommentsCount` = '0',
                        `FansCount` = '{$iParticipants}',
                        `Featured` = '0',
                        `allow_view_event_to` = '{$this->aDefValues['allow_view_event_to']}',
                        `allow_view_participants_to` = '{$this->aDefValues['allow_view_participants_to']}',
                        `allow_comment_to` = '{$this->aDefValues['allow_comment_to']}',
                        `allow_rate_to` = '{$this->aDefValues['allow_rate_to']}',
                        `allow_join_to` = '{$this->aDefValues['allow_join_to']}',
                        `allow_post_in_forum_to` = '{$this->aDefValues['allow_post_in_forum_to']}',
                        `JoinConfirmation` = '{$this->aDefValues['JoinConfirmation']}',
                        `allow_upload_photos_to` = '{$this->aDefValues['allow_upload_photos_to']}',
                        `allow_upload_videos_to` = '{$this->aDefValues['allow_upload_videos_to']}',
                        `allow_upload_sounds_to` = '{$this->aDefValues['allow_upload_sounds_to']}',
                        `allow_upload_files_to` = '{$this->aDefValues['allow_upload_files_to']}'
               ";

               $iResult = (int) $this->oMigrationModule->_oDb->query($sQuery);
               if($iResult <= 0) {
                    return 'Database error: can not insert new event into the table.';
               }

               //reparse tags
               $oTag = new BxDolTags();
               $oTag->reparseObjTags('bx_events', $r['ID']);

                // transfer partocipants
                $this -> _transferParticipants($r['ID']);

                // transfer photo;
                $this -> _transferEventPhoto($r);

                $this -> iTransffered++;
            }
        }

        /**
         * Transfer event's video;
         *
         * @param $aEventInfo array- event's info;
         * @return void;
         */
        function _transferEventPhoto($aEventInfo)
        {
           $sOldFilePath  = $this -> oMigrationModule -> _oDb -> getExtraParam('config_sdatingImage') . $aEventInfo['PhotoFilename'];

           if( file_exists($sOldFilePath) ) {

              $aFileInfo = array (
                'medTitle'    => stripslashes($aEventInfo['Title']),
                'medDesc'     => stripslashes($aEventInfo['Title']),
                'medTags'     => 'events',
                'Categories'  => array('Events'),
             );

             $aPathInfo = pathinfo ($sOldFilePath);
             $sTmpFile = BX_DIRECTORY_PATH_ROOT . 'tmp/bx_migration_events_image.' . $aPathInfo['extension'];
             @copy ($sOldFilePath, $sTmpFile);

             $iImgId = BxDolService::call('photos', 'perform_photo_upload', array($sTmpFile, $aFileInfo, false, $aEventInfo['ResponsibleID']), 'Uploader');

             @unlink ($sTmpFile);

             if($iImgId) {
                 // update event's table;
                 $sQuery = "INSERT `{$this -> sTablePrefix}images` SET `entry_id` = '{$aEventInfo['ID']}', `media_id` = '{$iImgId}'";
                 $this -> oMigrationModule -> _oDb -> query($sQuery);

                 $sQuery = "UPDATE `{$this -> sTablePrefix}main` SET `PrimPhoto` = '{$iImgId}' WHERE `ID` = '{$aEventInfo['ID']}'";
                 $this -> oMigrationModule -> _oDb -> query($sQuery);
             }
           }
        }

        /**
         * Transfer Participants;
         *
         * @param $iIventId integer;
         * @return void;
         */
        function _transferParticipants($iIventId)
        {
            $iIventId = (int) $iIventId;
            $sQuery   = "SELECT * FROM `SDatingParticipants` WHERE `IDEvent` = {$iIventId}";
            $rResult  = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                $sQuery =
                "
                    INSERT INTO
                        `{$this -> sTablePrefix}participants`
                    SET
                        `id_entry`     = {$iIventId},
                        `id_profile` = {$aRow['IDMember']},
                        `confirmed`  = 1,
                        `when`         = UNIX_TIMESTAMP()
                ";

               $iResult = (int) $this -> oMigrationModule -> _oDb->query($sQuery);
               if($iResult <= 0) {
                    return 'Database error: can not insert new participant into the table.';
               }
            }
        }

        /**
         * Get count of event's Participants;
         *
         * @param $iIventId integer;
         * @return integer;
         */
        function getParticipantsCount($iIventId)
        {
            $iIventId = (int) $iIventId;
            $sQuery = "SELECT COUNT(*) as `iCount` FROM `SDatingParticipants` WHERE `IDEvent` = {$iIventId}";
            $rResult = mysql_query($sQuery, $this -> rOldDb);
            $aRow = mysql_fetch_assoc($rResult);
            return (int) $aRow['iCount'];
        }
    }

?>
