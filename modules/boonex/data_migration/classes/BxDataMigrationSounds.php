<?php

    bx_import('BxDolCategories');
    bx_import('BxDolAlbums');

    require_once 'BxDataMigrationData.php';

    class BxDataMigrationSounds extends BxDataMigrationData
    {
        var $sNewAlbumName;
        var $sType;
        var $sTablePrefix;
        var $iTransffered = 0;

        /**
         * Class constructor;
         *
         * @param  : $oMigrationModule (object) - object instance of migration class;
         * @param  : $rOldDb (resourse) - connect to old dolphin's database;
         * @param  : $oDolModule (object);
         * @return : void;
         */
        function BxDataMigrationSounds(&$oMigrationModule, &$rOldDb, $oDolModule = '')
        {
            parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
            $this -> sNewAlbumNames = getParam('bx_sounds_profile_album_name');

            $sMainPrefix   = $oDolModule -> _oConfig -> getMainPrefix();
            $this -> sType = $sMainPrefix;
            $this -> sTablePrefix = $sMainPrefix . '_';
        }

        /**
         * Function migrate sounds;
         *
         * @return : (integer) operation result;
         */
        function getMigration()
        {
            if(!$this -> oDolModule) {
                 $this -> setResultStatus('System error: object  instance is not received');
                 return MIGRATION_FAILED;
            }

            // set new status;
            $this -> setResultStatus('All sounds transfer now');

            mysql_query('SET NAMES utf8', $this->rOldDb);

            $sQuery = "SELECT * FROM `RayMusicFiles`";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                if( !$this -> isSoundsExisting($aRow['ID']) ) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    //transform the file
                    if(!$this -> _exportFile($aRow['ID'])) {
                        continue;
                    }

                     // define file status;
                     $sFileStatus = $aRow['Approved'] == 'true' ? 'approved' : 'disapproved';

                    // execute query;
                    $sQuery =
                    "
                        INSERT INTO
                            `RayMp3Files`
                        SET
                            `ID`             = {$aRow['ID']},
                            `Title`         = '{$aRow['Title']}',
                            `Uri`             = '{$aRow['Uri']}',
                            `Tags`             = '{$aRow['Tags']}',
                            `Description`     = '{$aRow['Description']}',
                            `Time`             = '{$aRow['Time']}',
                            `Date`             = '{$aRow['Date']}',
                            `Reports`         = '{$aRow['Reports']}',
                            `Owner`         = '{$aRow['Owner']}',
                            `Listens`         = '{$aRow['Listens']}',
                            `Status`         = '$sFileStatus'

                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        $this -> setResultStatus('Database error. Cannot insert new sound in the database.');
                        return MIGRATION_FAILED;
                    }

                    $oTag = new BxDolTags();
                    $oTag -> reparseObjTags($this -> sType, $aRow['ID']);

                    // define sound's album;
                    $sError = $this -> _defineSoundsAlbum($aRow['ID'], $aRow['Owner'], $aRow['Title']);
                    if($sError) {
                        $this -> setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $sError = $this -> _exportVotings($aRow['ID']);
                    if($sError) {
                        $this -> setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $sError = $this -> _exportComments($aRow['ID']);
                    if($sError) {
                        $this -> setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $this -> iTransffered++;
                }
                else {
                      $this -> setResultStatus('Duplicate data.
                            Sounds with similar info already exists
                            (remove all sounds and start again');

                      return MIGRATION_FAILED;
                }
            }

            // set as finished;
            $this -> setResultStatus('All sound were transferred (' .  $this -> iTransffered . ' items)');

            return MIGRATION_SUCCESSFUL;
        }

        /**
         * Function define sound's album and if not exist will create;
         *
         * @param  : $iFileId (integer);
         * @param  : $iOwnerId (integer) - sound's owner profile id;
         * @return : (string) - error message or empty;
         */
        function _defineSoundsAlbum($iFileId, $iOwnerId, $sFileName)
        {
            $iFileId = (int) $iFileId;
            $iOwnerId = (int) $iOwnerId;

           $sNickName = getNickName($iOwnerId);
           if(!$sNickName) {
              //return 'Owner of ' . $sFileName . ' was not defined';
           }

           $sAlbum    = str_replace('{nickname}', $sNickName, $this -> sNewAlbumNames);

           $oAlbums   = & new BxDolAlbums($this -> sType, $iOwnerId);
           $aData     = array(
               'caption'         => $this -> oMigrationModule -> _oDb  -> escape($sAlbum),
               'owner'             => $iOwnerId,
                  'description'     => '',
               'location'         => '',
               'AllowAlbumView'  => BX_DOL_PG_ALL,
           );

           $iAlbumId = (int) $oAlbums -> addAlbum($aData);

           // check objects;
           $sQuery = "SELECT COUNT(*) FROM `sys_albums_objects` WHERE `id_album` = {$iAlbumId} AND `id_object` = {$iFileId}";
           if( !$this -> oMigrationModule -> _oDb -> getOne($sQuery) ) {
               $oAlbums -> addObject($iAlbumId, $iFileId, true);
           }
        }

        /**
         * Function export all sound comments;
         *
         * @param     : $iFileId (integer);
         * @return : (string) - error message or empty;
         */
        function _exportComments($iFileId)
        {
            $iFileId = (int) $iFileId;

            $sQuery = "SELECT * FROM `CmtsSharedMusic` WHERE `cmt_object_id` = {$iFileId}";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
                $sText = $this -> oMigrationModule -> _oDb  -> escape($aRow['cmt_text']);
                $sQuery =
                "
                    INSERT INTO
                        `{$this -> sTablePrefix}cmts`
                    SET
                        `cmt_id`          = {$aRow['cmt_id']},
                        `cmt_parent_id`  = {$aRow['cmt_parent_id']},
                        `cmt_object_id`  = {$aRow['cmt_object_id']},
                        `cmt_author_id`  = {$aRow['cmt_author_id']},
                        `cmt_text`         = '{$sText}',
                        `cmt_rate`         = {$aRow['cmt_rate']},
                        `cmt_rate_count` = {$aRow['cmt_rate_count']},
                        `cmt_time`         = '{$aRow['cmt_time']}',
                        `cmt_replies`     = {$aRow['cmt_replies']}
                ";

                $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                if($iResult <= 0) {
                    return 'Database error. Cannot insert comments information in the database.';
                }
            }
        }

        /**
         * Function export all sound votings;
         *
         * @param     : $iFileId (integer);
         * @return : (string) - error message or empty;
         */
        function _exportVotings($iFileId)
        {
            $iFileId = (int) $iFileId;

            $sQuery = "SELECT * FROM `gmusic_rating` WHERE `gal_id` = {$iFileId}";
            $rResult = mysql_query($sQuery, $this -> rOldDb);

            while( $aRow = mysql_fetch_assoc($rResult) )
            {
               $sQuery =
               "
                   INSERT INTO
                       `{$this -> sTablePrefix}rating`
                SET
                    `gal_id` = {$aRow['gal_id']},
                    `gal_rating_count` = {$aRow['gal_rating_count']},
                    `gal_rating_sum`   = {$aRow['gal_rating_sum']}
               ";

                $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                if($iResult <= 0) {
                    return 'Database error. Cannot insert votes information in the database.';
                }

                //export voting tracks;
                $sError = $this -> exportVotingTrack($aRow['gal_id']);

                if($sError) {
                    return $sError;
                }

                $fRate = $aRow['gal_rating_sum'] / $aRow['gal_rating_count'];
                //update primary table;
                $sQuery =
                "
                    UPDATE
                        `RayMp3Files`
                    SET
                        `Rate` = $fRate,
                        `RateCount` = {$aRow['gal_rating_count']}
                    WHERE
                        `ID` = {$iFileId}
                ";

                $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                if($iResult <= 0) {
                    return 'Database error. Cannot update votes information in the database.';
                }

            }
        }

        /**
         * Function export all sound votings track;
         *
         * @param     : $iGalId (integer);
         * @return : (string) - error message or empty;
         */
        function exportVotingTrack($iGalId)
        {
              $iGalId = (int) $iGalId;

              $sQuery = "SELECT * FROM `gmusic_voting_track` WHERE `gal_id` = {$iGalId}";
              $rResult = mysql_query($sQuery, $this -> rOldDb);

              while( $aRow = mysql_fetch_assoc($rResult) )
              {
                  $sQuery =
                    "
                    INSERT INTO
                        `{$this -> sTablePrefix}voting_track`
                    SET
                        `gal_id`   = {$aRow['gal_id']},
                        `gal_ip`   = '{$aRow['gal_ip']}',
                        `gal_date` = '{$aRow['gal_date']}'
                    ";

                    $iResult = (int) $this -> oMigrationModule -> _oDb -> query($sQuery);
                    if($iResult <= 0) {
                        return 'Database error. Cannot insert votes track information in the database.';
                    }
              }
        }

        /**
         * Function export sound file;
         *
         * @param  : $iFileId (integer);
         * @return : (boolean);
         */
        function _exportFile($iFileId)
        {
            $iFileId = (int) $iFileId;

            $bOpperation = true;

            // copy file;
            $sFileName = $iFileId . '.mp3';
            $sOldFile  = $this -> oMigrationModule -> _oDb -> getExtraParam('config_root');
            $sOldFile .= 'ray/modules/music/files/' . $sFileName;

            $sNewDirectory = BX_DIRECTORY_PATH_ROOT . 'flash/modules/mp3/files/';
            if( !@copy($sOldFile, $sNewDirectory . $sFileName) ){
                $bOpperation = false;
            }

            return $bOpperation;
        }

        /**
         * Function export all favorites records;
         *
         * @param  : $iFileId (integer);
         * @return : (string) - error message or empty;
         */
        function _exportFavorites ($iFileId) {
            $iFileId = (int)$iFileId;
            $sqlQuery = "SELECT `medID`, `userID`, UNIX_TIMESTAMP(`favDate`) as `favDate` FROM `shareMusicFavorites` WHERE `medID` = {$iFileId}";
            $rResult = mysql_query($sqlQuery, $this->rOldDb);
            while ($aRow = mysql_fetch_assoc($rResult)) {
              $sqlQuery =
                "
                    INSERT INTO
                        `{$this->sType}_favorites`
                    SET
                        `ID`   = {$aRow['medID']},
                        `Profile`   = '{$aRow['userID']}',
                        `Date` = '{$aRow['gal_date']}'
                    ";
                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot insert favorites information to the database.';
                }
            }
        }

        /**
         * Function check exsiting sounds;
         *
         * @param : $iSoundId (integer) - sound's id;
         * @return : true if exist;
         */
        function isSoundsExisting($iSoundId)
        {
            $iSoundId = (int) $iSoundId;

            $sQuery = "SELECT COUNT(*) FROM `RayMp3Files` WHERE `ID` = '{$iSoundId}'";
            return $this -> oMigrationModule -> _oDb -> getOne($sQuery) ? true : false;
        }
    }