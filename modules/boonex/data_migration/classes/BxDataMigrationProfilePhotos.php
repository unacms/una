<?php

require_once('BxDataMigrationData.php');

class BxDataMigrationProfilePhotos extends BxDataMigrationData {
    var $sType;
    var $sOldFilesPath;
    var $aOldTables;

    var $sNewAlbumName;
    var $sNewFilesPath;
    var $aNewSizes;
    /**
     * Class constructor;
     *
     * @param  : $oMigrationModule (object) - object instance of migration class;
     * @param  : $rOldDb (resourse) - connect to old dolphin's database;
     * @param  : $oDolModule (object);
     * @return : void;
     */
    function BxDataMigrationProfilePhotos (&$oMigrationModule, &$rOldDb, $oDolModule = '') {
        parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        $this->sType = 'bx_photos';
        $this->sOldFilesPath = 'media/images/profile/';

        $this->aOldTables = array(
            'main' => 'media',
            'rate' => 'media_rating',
            'rate_track' => 'media_voting_track'
        );

        $this->sNewAlbumName = $oDolModule->_oConfig->getGlParam('profile_album_name');
        $this->sNewFilesPath  = 'modules/boonex/photos/data/files/';
        $this->aNewSizes = array(
            'icon'   => array('width' => $oDolModule->_oConfig->getGlParam('icon_width'), 'height' => $oDolModule->_oConfig->getGlParam('icon_height'), 'postfix' => '_ri'),
            'thumb'  => array('width' => $oDolModule->_oConfig->getGlParam('thumb_width'), 'height' => $oDolModule->_oConfig->getGlParam('thumb_height'), 'postfix' => '_rt'),
            'browse' => array('width' => $oDolModule->_oConfig->getGlParam('browse_width'), 'height' => $oDolModule->_oConfig->getGlParam('browse_height'), 'postfix' => '_t'),
            'file'   => array('width' => $oDolModule->_oConfig->getGlParam('file_width'), 'height' => $oDolModule->_oConfig->getGlParam('file_height'), 'postfix' => '_m')
        );
    }

    /**
     * Function migrate profile photos data;
     *
     * @return : (integer) operation result;
     */
    function getMigration () {
        if (!$this->oDolModule) {
             $this->setResultStatus('System error: object  instance is not received');
             return MIGRATION_FAILED;
        }

        // set new status;
        $this->setResultStatus('Profile Photos are transfered now');

         mysql_query('SET NAMES utf8', $this->rOldDb);

        $sqlQuery = "SELECT `med_id` as `medID`,
                            `med_prof_id` as `medProfId`,
                            `med_file` as `medFile`,
                            `med_title` as `medTitle`,
                            if(`med_status`='active', 'approved', 'disapproved') as `medStatus`,
                            UNIX_TIMESTAMP(`med_date`) as `medDate`
                            FROM `{$this->aOldTables['main']}` WHERE `med_type`='photo' GROUP BY `med_id`";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);

        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
                if (!$this->isEntryExisting($aRow['medID'])) {
                    // escape all data;
                    $aRow = $this -> escapeData($aRow);

                    $aRow['medExt'] = substr($aRow['medFile'], strpos($aRow['medFile'], '.') + 1);
                    $aSizeInfo = $this->getSizeInfo('photo_' . $aRow['medFile'], $aRow['medProfId']);
                    $sUri  = uriGenerate($aRow['medTitle'], $this->sType . '_main', 'Uri');
                    $sSize = $aSizeInfo['width'] . 'x' . $aSizeInfo['height'];
                    $sHash = md5(microtime());

                       //transform the file
                    if( !$this->_exportFile(array('medID' => $aRow['medID'], 'medFile'=>$aRow['medFile'], 'medProfId'=>$aRow['medProfId'], 'medExt'=>$aRow['medExt']))) {
                        continue;
                    }

                    $sqlQuery =
                    "
                        INSERT INTO
                            `{$this->sType}_main`
                        SET
                          `ID`    =  {$aRow['medID']},
                          `Owner` = '{$aRow['medProfId']}',
                          `Ext`   = '{$aRow['medExt']}',
                          `Size`  = '$sSize',
                          `Title` = '{$aRow['medTitle']}',
                          `Uri`   = '$sUri',
                          `Desc`  = '{$aRow['medDesc']}',
                          `Tags`  = '{$aRow['medTags']}',
                          `Date`  = '{$aRow['medDate']}',
                          `Views` = '{$aRow['medViews']}',
                          `Hash`  = '$sHash',
                          `Status` = '{$aRow['medStatus']}'
                    ";

                    $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                    if ($iResult <= 0) {
                        $this->setResultStatus('Database error. Cannot insert new photo to the database.');
                        return MIGRATION_FAILED;
                    }

                    // define album
                    $sError = $this->_defineAlbum($aRow['medID'], $aRow['medProfId'], $aRow['medTitle']);
                    if ($sError) {
                        $this->setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $sError = $this->_exportVotings($aRow['medID']);
                    if ($sError) {
                        $this->setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }
                }
                else {
                    $this->setResultStatus('Duplicate data. Photo with similar info already exists (remove all photos and start again)');
                    return MIGRATION_FAILED;
                }
            }
        }

        // set as finished;
        $this->setResultStatus('All photos were transferred');
        return MIGRATION_SUCCESSFUL;
    }

    /**
     * Function will check existing entry in module;
     *
     * @param  : $iEntryId (integer) - entry's id;
     * @return : true if exist;
     */
    function isEntryExisting($iEntryId)
    {
        $iEntryId = (int) $iEntryId;
        $sqlQuery  = "SELECT COUNT(*) FROM `{$this->sType}_main` WHERE `ID` = {$iEntryId} LIMIT 1";
        return $this -> oMigrationModule -> _oDb -> getOne($sqlQuery) ? true : false;
    }

    /**
     * Function returns sizes array of photo;
     *
     * @param  : $sFile (string) - file name;
     * @param  : $iOwnerId (int) - file's owner;
     * @return : array with 'width' and 'height' elements;
     */
    function getSizeInfo ($sFile, $iOwnerId) {
        $sFile = strip_tags($sFile);
        $iOwnerId = (int)$iOwnerId;
        $sFilePath = $this->oMigrationModule->_oDb->getExtraParam('config_root') . $this->sOldFilesPath . $iOwnerId . '/' . $sFile;
        if (file_exists($sFilePath)) {
            $aInfo = getimagesize($sFilePath);
            return array('width' => $aInfo[0], 'height' => $aInfo[1]);
        }
        else
            return $this->aNewSizes['file'];
    }

    /**
     * Function creates album and add new object there;
     *
     * @param  : $iFileId (int) - file id;
     * @param  : $iOwnerId (int) - file's owner id;
     * @param  : $sFileName (string) - file name;
     * @return : nothing;
     */

    function _defineAlbum($iFileId, $iOwnerId, $sFileName) {
        $sNickName = getNickName($iOwnerId);
        $sAlbum    = str_replace('{nickname}', $sNickName, $this->sNewAlbumName);

       $iFileId = (int) $iFileId;
       $iOwnerId = (int) $iOwnerId;

       $oAlbums   = &new BxDolAlbums($this->sType, $iOwnerId);
       $aData     = array(
           'caption'         => $this->oMigrationModule->_oDb->escape($sAlbum),
           'owner'             => $iOwnerId,
              'description'     => $this->oMigrationModule->_oDb->escape($sAlbum),
           'location'         => _t('_Undefined'),
           'AllowAlbumView'  => BX_DOL_PG_ALL,
       );

       $iAlbumId = (int) $oAlbums->addAlbum($aData);
       // check objects;
       $sqlQuery = "SELECT COUNT(*) FROM `sys_albums_objects` WHERE `id_album` = {$iAlbumId} AND `id_object` = {$iFileId}";
       if (!$this->oMigrationModule->_oDb->getOne($sQuery)) {
           $oAlbums->addObject($iAlbumId, $iFileId, true);
       }
    }

    /**
     * Function export photo file;
     *
     * @param  : $aFile (array) - array with medID and medExt elements;
     * @return : (boolean) false if not exist;
     */
    function _exportFile ($aFile = array()) {
        $bOpperation = true;

        $sFile = $aFile['medFile'];
        $sSourceFilePath = $this->oMigrationModule->_oDb->getExtraParam('config_root') . $this->sOldFilesPath . $aFile['medProfId'] . '/photo_' . $sFile;
        $sNewDirectory = BX_DIRECTORY_PATH_ROOT . $this->sNewFilesPath;
        if (file_exists($sSourceFilePath) && is_dir($sNewDirectory)) {
            //copy as original
            copy($sSourceFilePath, $sNewDirectory . $aFile['medID'] . '.' . $aFile['medExt']);
            foreach ($this->aNewSizes as $aValue) {
                $sNewFile = $sNewDirectory . $aFile['medID'] . $aValue['postfix'] . '.jpg';
                imageResize($sSourceFilePath, $sNewFile, $aValue['width'], $aValue['height'], true);
            }
        }
        else {
            $bOpperation = false;
        }

        return $bOpperation;
    }

    /**
     * Function export all votings;
     *
     * @param     : $iFileId (integer);
     * @return : (string) - error message or empty;
     */
    function _exportVotings ($iFileId) {
        $iFileId = (int)$iFileId;
        $sqlQuery = "SELECT * FROM `{$this->aOldTables['rate']}` WHERE `med_id` = {$iFileId}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
               $sqlQuery =
               "
                   INSERT INTO
                       `{$this->sType}_rating`
                SET
                    `gal_id` = {$aRow['med_id']},
                    `gal_rating_count` = {$aRow['med_rating_count']},
                    `gal_rating_sum`   = {$aRow['med_rating_sum']}
               ";

                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot insert votes information in the database.';
                }

                //export voting tracks;
                $sError = $this->_exportVotingTrack($aRow['med_id']);

                if ($sError) {
                    return $sError;
                }

                $fRate = $aRow['med_rating_sum'] / $aRow['med_rating_count'];
                //update primary table;
                $sqlQuery =
                "
                    UPDATE
                        `{$this->sType}_main`
                    SET
                        `Rate` = $fRate,
                        `RateCount` = {$aRow['med_rating_count']}
                    WHERE
                        `ID` = {$iFileId}
                ";

                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot update votes information in the database.';
                }
            }
        }
    }

    /**
     * Function export all votings track;
     *
     * @param     : $iGalId (integer);
     * @return : (string) - error message or empty;
     */
    function _exportVotingTrack ($iGalId) {
        $iGalId = (int) $iGalId;
        $sqlQuery = "SELECT * FROM `{$this->aOldTables['rate_track']}` WHERE `med_id` = {$iGalId}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
              $sqlQuery =
                "
                    INSERT INTO
                        `{$this->sType}_voting_track`
                    SET
                        `gal_id`   = {$aRow['med_id']},
                        `gal_ip`   = '{$aRow['med_ip']}',
                        `gal_date` = '{$aRow['med_date']}'
                    ";
                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot insert votes track information to the database.';
                }
            }
        }
    }
}

?>