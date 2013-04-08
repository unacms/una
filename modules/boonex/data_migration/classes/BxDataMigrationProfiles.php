<?php

    require_once 'BxDataMigrationData.php';

    class BxDataMigrationProfiles extends BxDataMigrationData
    {
        var $bAvatarInstalled;
        var $iTransffered = 0;

        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationProfiles(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
            $this -> bAvatarInstalled = $this -> oMigrationModule -> _oDb -> isModule('avatar') ? true : false;
        }

        /**
         * Function migrate profiles data;
         *
         * @return : (string) operation result;
         */
        function getMigration()
        {
            // set new status;
            $this -> setResultStatus('Profiles transfer now');

            mysql_query('SET NAMES utf8', $this->rOldDb);

            $sError = $this -> transformProfiles();
            if($sError) {
               $this -> setResultStatus($sError);
               return MIGRATION_FAILED;
            }

            // set as finished;
            $this -> setResultStatus('All profiles were transferred (' .  $this -> iTransffered . ' items)');

            return MIGRATION_SUCCESSFUL;
        }

        /**
         * Function Transform all old profiles;
         *
         * @return : (string) - error message if nedded;
         */
        function transformProfiles()
        {
            // upate new profiles table;
            $sError = $this -> _updateNewProfiles();
            if($sError) {
                return $sError;
            }

            $sQuery = "SELECT * FROM `Profiles`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

             while( $aRow = mysql_fetch_assoc($rResult) )
             {
                  if( !$this -> isProfileExisting($aRow['NickName']) ) {
                     // escape all data;
                     $aRow = $this -> escapeData($aRow);

                     $sQuery =
                     "
                         INSERT INTO
                             `Profiles`
                         SET
                             `ID`            = '{$aRow['ID']}',
                             `NickName`       = '{$aRow['NickName']}',
                             `Email`          = '{$aRow['Email']}',
                             `Password`       = '{$aRow['Password']}',
                             `Status`         = '{$aRow['Status']}',
                             `Couple`           = '{$aRow['Couple']}',
                             `Sex`                 = '{$aRow['Sex']}',
                             `LookingFor`    = '{$aRow['LookingFor']}',
                             `Headline`         = '{$aRow['Headline']}',
                             `DescriptionMe` = '{$aRow['DescriptionMe']}',
                             `Country`         = '{$aRow['Country']}',
                             `City`             = '{$aRow['City']}',
                             `DateOfBirth`    = '{$aRow['DateOfBirth']}',
                             `Featured`        = '{$aRow['Featured']}',
                             `DateReg`        = '{$aRow['DateReg']}',
                             `DateLastEdit`  = '{$aRow['DateLastEdit']}',
                             `DateLastLogin` = '{$aRow['DateLastLogin']}',
                             `DateLastNav`     = '{$aRow['DateLastNav']}',
                             `aff_num`         = '{$aRow['aff_num']}',
                             `Tags`             = '{$aRow['Tags']}',
                             `zip`             = '{$aRow['zip']}',
                             `EmailNotify`     = '{$aRow['EmailNotify']}'
                     ";

                     $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                     if($iResult <= 0) {
                        return 'Database error. Cannot insert new profile in the database.';
                     }

                     // update passwords
                     $this -> oMigrationModule -> _oDb -> query("UPDATE `Profiles` SET `Salt` = CONV(FLOOR(RAND()*99999999999999), 10, 36) WHERE `ID` = '{$aRow['ID']}'");
                     $this -> oMigrationModule -> _oDb -> query("UPDATE `Profiles` SET `Password` = SHA1(CONCAT(`Password`, `Salt`)) WHERE `ID` = '{$aRow['ID']}'");

                     createUserDataFile($aRow['ID']);

                     // migrate profile's picture;
                     if($aRow['Picture'] && $this -> bAvatarInstalled) {
                         $this -> _exportAvatar($aRow['ID'], $aRow['PrimPhoto']);
                     }

                     // migrate friends;
                     $sResult = $this -> _exportFriends($aRow['ID']);
                     if($sResult) {
                         return $sResult;
                     }

                     $this -> iTransffered++;
                  }
             }
        }

        /**
         * Function get profile image;
         *
         * @param  : $iImgId (integer) - profile's image id;
         * @return : (string) - image's name;
         */
        function getProfileImage($iImgId)
        {
            $iImgId = (int) $iImgId;

            $sQuery = "SELECT `med_file` FROM `media` WHERE `med_id` = {$iImgId} AND `med_type` = 'photo' LIMIT 1";
            $rResult = mysql_query($sQuery, $this -> rOldDb);
            $aRow = mysql_fetch_assoc($rResult);

            return isset($aRow['med_file']) ? $aRow['med_file'] : '';
        }

        /**
         * Function update new dolphin's profiles table;
         *
         * @return : (string) - error message or empty;
         */
        function _updateNewProfiles()
        {
            // define the last profile Id from old Db;
            $sQuery = "SELECT `ID` FROM `Profiles` ORDER BY `ID` DESC LIMIT 1";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            $aRow = mysql_fetch_assoc($rResult);
            $iLastProfileId = (int) $aRow['ID'];
            $iLastProfileId++;

            // update current profiles table;
            $sQuery = "SELECT `ID` FROM `Profiles`";
            $aProfiles = $this -> oMigrationModule -> _oDb -> getAll($sQuery);

            if($aProfiles) {
                foreach($aProfiles as $iKey => $aItems)
                {
                    $sQuery = "UPDATE `Profiles` SET `ID` = '{$iLastProfileId}' WHERE `ID` = '{$aItems['ID']}'";
                    $this -> oMigrationModule -> _oDb -> query($sQuery);

                    createUserDataFile($iLastProfileId);
                    $iLastProfileId++;
                }
            }
        }

        /**
         * Export all profile's friends;
         *
         * @param $iProfileId integer;
         * @return string - error message if needed;
         */
        function _exportFriends($iProfileId)
        {
            $iProfileId = (int) $iProfileId;

            $sQuery = "SELECT * FROM `FriendList` WHERE `ID` = {$iProfileId} OR `Profile` = {$iProfileId}";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isFriendExisting($aRow['ID'], $aRow['Profile'])) {
                   // build query;
                   $sQuery =
                    "
                         INSERT IGNORE INTO
                            `sys_friend_list`
                        SET
                           `ID`           = '{$aRow['ID']}',
                           `Profile`    = '{$aRow['Profile']}',
                           `Check`      = '{$aRow['Check']}',
                           `When`          = NOW()
                   ";

                   $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                   if($iResult <= 0) {
                       return 'Database error. Cannot insert friend list in the database.';
                   }
                }
            }
        }

        /**
         *  Function export profile's avatar;
         *
         * @param  : $iProfileId (integer);
         * @param  : $sAvatarName (integer) - avatar's id;
         * @return : void;
         */
        function _exportAvatar($iProfileId, $iAvatarId)
        {
            $iProfileId = (int) $iProfileId;
            $iAvatarId = (int) $iAvatarId;

            //define the old avatar's name;
            $sOldAvatarName = $this -> getProfileImage($iAvatarId);

            if($sOldAvatarName) {
                //define old avatar's path;
                $sOldAvatarPath = $this -> oMigrationModule -> _oDb -> getExtraParam('config_profileImage')
                            .  $iProfileId . '/photo_' . $sOldAvatarName;

                require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/classes/BxAvaModule.php');
                $oAvatar = BxDolModule::getInstance('bx_avatar');
                $oAvatar -> _iProfileId = $iProfileId;

                // resize image;
                $sNewImagePath = BX_AVA_DIR_TMP . '_' . $iProfileId . BX_AVA_EXT;

                $o =& BxDolImageResize::instance(BX_AVA_W, BX_AVA_W);
                $o->removeCropOptions ();
                $o->setJpegOutput (true);
                $o->setSize (BX_AVA_W, BX_AVA_W);
                $o->setSquareResize (true);
                if (IMAGE_ERROR_SUCCESS != $o->resize($sOldAvatarPath, $sNewImagePath))
                    return false;

                return $oAvatar ->_addAvatar($sNewImagePath, false);
            }
        }

        /**
         * Function will check existing poll in module;
         *
         * @param  : $sPollQuestion (string) - poll's question;
         * @return : true if exist;
         */
        function isProfileExisting($sNickName)
        {
            $sNickName = $this -> oMigrationModule -> _oDb  -> escape($sNickName);
            $sQuery  = "SELECT COUNT(*) FROM `Profiles` WHERE `NickName` = '{$sNickName}'";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }

        /**
         * Check friends pair;
         *
         * @param $iProfileId integer
         * @param $iFriendId integer
         * @return boolean - true if already existing
         */
        function isFriendExisting($iProfileId, $iFriendId)
        {
            $iProfileId = (int) $iProfileId;
            $iFriendId  = (int) $iFriendId;

            $sQuery =
            "
                SELECT
                    COUNT(*)
                FROM
                    `sys_friend_list`
                WHERE
                    (
                        (`ID` = {$iProfileId} AND `Profile` = {$iFriendId})
                            OR
                        (`ID` = {$iFriendId} AND `Profile` = {$iProfileId})
                    )
            ";

            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }
    }
