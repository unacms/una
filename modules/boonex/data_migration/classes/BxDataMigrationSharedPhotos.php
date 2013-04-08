<?php

require_once('BxDataMigrationData.php');

class BxDataMigrationSharedPhotos extends BxDataMigrationData {
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
    function BxDataMigrationSharedPhotos (&$oMigrationModule, &$rOldDb, $oDolModule = '') {
        parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        $this->sType = 'bx_photos';
        $this->sOldFilesPath = 'media/images/sharingImages/';

        $this->aOldTables = array(
            'main' => 'sharePhotoFiles',
            'favorite' => 'sharePhotoFavorites',
            'rate' => 'gphoto_rating',
            'rate_track' => 'gphoto_voting_track',
            'cmts' => 'CmtsSharedPhoto'
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
     * Function migrate shared_photos data;
     *
     * @return : (integer) operation result;
     */
    function getMigration () {
        if (!$this->oDolModule) {
             $this->setResultStatus('System error: object  instance is not received');
             return MIGRATION_FAILED;
        }

        // set new status;
        $this->setResultStatus('Shared photos transfer now');

        mysql_query('SET NAMES utf8', $this->rOldDb);

        $sqlQuery = "SELECT * FROM `{$this->aOldTables['main']}` GROUP BY `medID`";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        while ($aRow = mysql_fetch_assoc($rResult)) {

                $sUri = !$this->isEntryExisting($aRow['medUri'])
                    ? $aRow['medUri']
                    : uriGenerate($aRow['medTitle'], $this->sType . '_main', 'Uri');

                $sUri = $this -> oMigrationModule -> _oDb  -> escape($sUri);

                $aSizeInfo = $this->getSizeInfo($aRow['medID'] . '.' . $aRow['medExt']);
                $sSize        = $aSizeInfo['width'] . 'x' . $aSizeInfo['height'];
                $sStatus = $aRow['Approved'] == 'true' ? 'approved' : 'disapproved';
                $sHash = md5(microtime());

                 // escape all data;
                $aRow = $this -> escapeData($aRow);

                $sqlQuery =
                "
                    INSERT INTO
                        `{$this->sType}_main`
                    SET
                      `Owner` = '{$aRow['medProfId']}',
                      `Ext`   = '{$aRow['medExt']}',
                      `Size`  = '$sSize',
                      `Title` = '{$aRow['medTitle']}',
                      `Uri`   = '{$sUri}',
                      `Desc`  = '{$aRow['medDesc']}',
                      `Tags`  = '{$aRow['medTags']}',
                      `Date`  = '{$aRow['medDate']}',
                      `Views` = '{$aRow['medViews']}',
                      `Hash`  = '$sHash',
                      `Status` = '$sStatus'
                ";

            $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
            if ($iResult <= 0) {
                $this->setResultStatus('Database error. Cannot insert new photo to the database.');
                return MIGRATION_FAILED;
            }

            // get last file id;
            $iFileId = $this->oMigrationModule->_oDb->lastId();

            //transform the file
               $this->_exportFile(
                   array(
                       'medID'     => $aRow['medID'],
                       'medFile'   => $aRow['medFile'],
                       'medProfId' => $aRow['medProfId'],
                       'medExt'    => $aRow['medExt'],
                       'newID'        => $iFileId,
                   ));

                $oTag = new BxDolTags();
                $oTag->reparseObjTags($this->sType, $iFileId);

                // define album
                $sError = $this->_defineAlbum($iFileId, $aRow['medProfId'], $aRow['medTitle']);
                if ($sError) {
                    $this->setResultStatus($sError);
                    return MIGRATION_FAILED;
                }

                $sError = $this->_exportFavorites(array('old'=>$aRow['medID'], 'new'=>$iFileId));
                if ($sError) {
                    $this->setResultStatus($sError);
                    return MIGRATION_FAILED;
                }

                $sError = $this->_exportVotings(array('old'=>$aRow['medID'], 'new'=>$iFileId));
                if ($sError) {
                    $this->setResultStatus($sError);
                    return MIGRATION_FAILED;
                }

                $sError = $this->_exportComments(array('old'=>$aRow['medID'], 'new'=>$iFileId));
                if ($sError) {
                    $this->setResultStatus($sError);
                    return MIGRATION_FAILED;
                }
            }

        // set as finished;
        $this->setResultStatus('All photos were transferred');
        return MIGRATION_SUCCESSFUL;
    }

    /**
     * Function will check existing entry in module;
     *
     * @param  : $sEntryUri (string) - entry's uri;
     * @return : true if exist;
     */
    function isEntryExisting($sEntryUri)
    {
        $sEntryUri = $this -> oMigrationModule -> _oDb  -> escape($sEntryUri);
        $sqlQuery  = "SELECT COUNT(*) FROM `{$this->sType}_main` WHERE `Uri` = '$sEntryUri'";
        return $this -> oMigrationModule -> _oDb -> getOne($sqlQuery) ? true : false;
    }

    /**
     * Function returns sizes array of photo;
     *
     * @param  : $sFile (string) - file name;
     * @return : array with 'width' and 'height' elements;
     */
    function getSizeInfo ($sFile) {
        $sFile = strip_tags($sFile);
        $sFilePath = $this->oMigrationModule->_oDb->getExtraParam('config_root') . $this->sOldFilesPath . $sFile;
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
     * @return : (boolean) - true if was copied;
     */
    function _exportFile ($aFile = array()) {
        $bOpperation = true;

        $sFile = $aFile['medID'] . '.' . $aFile['medExt'];
        $sSourceFilePath = $this->oMigrationModule->_oDb->getExtraParam('config_root') . $this->sOldFilesPath . $sFile;
        $sNewDirectory = BX_DIRECTORY_PATH_ROOT . $this->sNewFilesPath;

        if ( file_exists($sSourceFilePath) ) {
            if (!copy($sSourceFilePath, $sNewDirectory . $aFile['newID'] . '.' . $aFile['medExt'])) {
                return false;
            }
            foreach ($this->aNewSizes as $aValue) {
                $sNewFile = $sNewDirectory . $aFile['newID'] . $aValue['postfix'] . '.jpg';
                imageResize($sSourceFilePath, $sNewFile, $aValue['width'], $aValue['height'], true);
            }
        }
        else {
            $bOpperation = false;
        }

        return $bOpperation;
     }

     /**
     * Function export all comments;
     *
     * @param     : $iFileId (integer) - file id;
     * @return : (string) - error message or empty;
     */
    function _exportComments ($aFile) {
        $sQuery = "SELECT * FROM `{$this->aOldTables['cmts']}` WHERE `cmt_object_id` = {$aFile['old']}";
        $rResult = mysql_query($sQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
                $sText = $this->oMigrationModule->_oDb->escape($aRow['cmt_text']);

                $sqlQuery =
                "
                    INSERT INTO
                        `{$this->sType}_cmts`
                    SET
                        `cmt_id`          = {$aRow['cmt_id']},
                        `cmt_parent_id`  = {$aRow['cmt_parent_id']},
                        `cmt_object_id`  = {$aFile['new']},
                        `cmt_author_id`  = {$aRow['cmt_author_id']},
                        `cmt_text`         = '{$sText}',
                        `cmt_rate`         = {$aRow['cmt_rate']},
                        `cmt_rate_count` = {$aRow['cmt_rate_count']},
                        `cmt_time`         = '{$aRow['cmt_time']}',
                        `cmt_replies`     = {$aRow['cmt_replies']}
                ";

                $this->oMigrationModule->_oDb->query($sqlQuery);
            }
        }
    }

    /**
     * Function export all votings;
     *
     * @param     : $iFileId (integer);
     * @return : (string) - error message or empty;
     */
    function _exportVotings ($aFile) {
        $sqlQuery = "SELECT * FROM `{$this->aOldTables['rate']}` WHERE `gal_id` = {$aFile['old']}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
               $sqlQuery =
               "
                   INSERT INTO
                       `{$this->sType}_rating`
                SET
                    `gal_id` = {$aFile['new']},
                    `gal_rating_count` = {$aRow['gal_rating_count']},
                    `gal_rating_sum`   = {$aRow['gal_rating_sum']}
               ";

                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot insert votes information in the database.';
                }

                //export voting tracks;
                $sError = $this->_exportVotingTrack($aFile);

                if ($sError) {
                    return $sError;
                }

                $fRate = $aRow['gal_rating_sum'] / $aRow['gal_rating_count'];
                //update primary table;
                $sqlQuery =
                "
                    UPDATE
                        `{$this->sType}_main`
                    SET
                        `Rate` = $fRate,
                        `RateCount` = {$aRow['gal_rating_count']}
                    WHERE
                        `ID` = {$aFile['new']}
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
    function _exportVotingTrack ($aFile) {
        $sqlQuery = "SELECT * FROM `{$this->aOldTables['rate_track']}` WHERE `gal_id` = {$aFile['old']}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
              $sqlQuery =
                "
                    INSERT INTO
                        `{$this->sType}_voting_track`
                    SET
                        `gal_id`   = {$aFile['new']},
                        `gal_ip`   = '{$aRow['gal_ip']}',
                        `gal_date` = '{$aRow['gal_date']}'
                    ";
                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot insert votes track information to the database.';
                }
            }
        }
    }

    /**
     * Function export all favorites records;
     *
     * @param  : $iFileId (integer);
     * @return : (string) - error message or empty;
     */
    function _exportFavorites ($aFile) {
        $sqlQuery = "SELECT `medID`, `userID`, UNIX_TIMESTAMP(`favDate`) as `favDate` FROM `{$this->aOldTables['favorite']}` WHERE `medID` = {$aFile['old']}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        while ($aRow = mysql_fetch_assoc($rResult)) {
          $sqlQuery =
            "
                INSERT INTO
                    `{$this->sType}_favorites`
                SET
                    `ID` = {$aFile['new']},
                    `Profile` = '{$aRow['userID']}',
                    `Date` = '{$aRow['gal_date']}'
                ";
            $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
            if ($iResult <= 0) {
                return 'Database error. Cannot insert favorites information to the database.';
            }
        }
    }
}

?>