<?php

require_once('BxDataMigrationData.php');

global $sModulesPath;
require_once($sModulesPath . 'video/inc/functions.inc.php');

class BxDataMigrationVideos extends BxDataMigrationData {
    var $sType;
    var $sOldFilesPath;
    var $aOldTables;
    var $aOldFileTypes;

    var $sNewAlbumName;
    var $sNewFilesPath;
    var $aNewSizes;
    var $iTransffered = 0;

    /**
     * Class constructor;
     *
     * @param  : $oMigrationModule (object) - object instance of migration class;
     * @param  : $rOldDb (resourse) - connect to old dolphin's database;
     * @param  : $oDolModule (object);
     * @return : void;
     */
    function BxDataMigrationVideos (&$oMigrationModule, &$rOldDb, $oDolModule = '') {
        parent::BxDataMigrationData($oMigrationModule, $rOldDb, $oDolModule);
        $this->sType = 'bx_videos';
        $this->sOldFilesPath = 'ray/modules/movie/files/';
        $this->aOldFileTypes = array(
            '.jpg', '.flv', '.mpg'
        );

        $this->aOldTables = array(
            'main' => 'RayMovieFiles',
            'favorite' => 'shareVideoFavorites',
            'rate' => 'gvideo_rating',
            'rate_track' => 'gvideo_voting_track',
            'cmts' => 'CmtsSharedVideo'
        );

        $this->sNewAlbumName = $oDolModule->_oConfig->getGlParam('profile_album_name');
        $this->sNewFilesPath  = 'flash/modules/video/files/';
        $this->aNewSizes = array(
            'thumb'  => array('width' => $oDolModule->_oConfig->getGlParam('browse_width'), 'height' => $oDolModule->_oConfig->getGlParam('browse_height'), 'postfix' => '_small.jpg'),
            'file'   => array('width' => $oDolModule->_oConfig->getGlParam('file_width'), 'height' => $oDolModule->_oConfig->getGlParam('file_height'), 'postfix' => '.mp4')
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
        $this->setResultStatus('Videos transfer now');

        mysql_query('SET NAMES utf8', $this->rOldDb);

        $sqlQuery = "SELECT `ID`, `Title`, `Uri`, `Tags`, `Description`, `Time`, `Date`, `Owner`, if(`Approved`<>'true', 'disapproved', 'approved') as `Status` FROM `{$this->aOldTables['main']}` ORDER BY `ID` ASC";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
                if (!$this->isEntryExisting($aRow['Uri'])) {

                    //transform the file
                    if ( !$this->_exportFile(array('medID'=>$aRow['ID'])) ) {
                        continue;
                    }

                    // escape all data;
                    foreach($aRow as $sKey => $mValue) {
                        $aRow[$sKey] = $this->oMigrationModule->_oDb->escape($mValue);
                        $sqlBody .= "`$sKey` = '{$aRow[$sKey]}', ";
                    }

                    $sqlBody = trim($sqlBody, ', ');

                    $sqlQuery =
                    "
                        INSERT INTO
                            `RayVideoFiles`
                        SET
                          $sqlBody
                    ";

                    $sqlBody = '';

                    $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                    if ($iResult <= 0) {
                        $this->setResultStatus('Database error. Cannot insert new photo to the database.');
                        return MIGRATION_FAILED;
                    }

                    $oTag = new BxDolTags();
                    $oTag->reparseObjTags($this->sType, $aRow['ID']);

                    // define album
                    $sError = $this->_defineAlbum($aRow['ID'], $aRow['Owner'], $aRow['Title']);
                    if ($sError) {
                        $this->setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $sError = $this->_exportFavorites($aRow['ID']);
                    if ($sError) {
                        $this->setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $sError = $this->_exportVotings($aRow['ID']);
                    if ($sError) {
                        $this->setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $sError = $this->_exportComments($aRow['ID']);
                    if ($sError) {
                        $this->setResultStatus($sError);
                        return MIGRATION_FAILED;
                    }

                    $this -> iTransffered++;
                }
                else {
                    $this->setResultStatus('Duplicate data. Feedback with similar info already exists (remove all news and start again)');
                    return MIGRATION_FAILED;
                }
            }
        }

        // set as finished;
        $this -> setResultStatus('All Videos were transferred (' .  $this -> iTransffered . ' items)');

        return MIGRATION_SUCCESSFUL;
    }

    /**
     * Function will check existing entry in module;
     *
     * @param  : $sEntryUri (string) - entry's uri;
     * @return : true if exist;
     */
    function isEntryExisting($sEntryUri) {
        $sEntryUri = $this -> oMigrationModule -> _oDb  -> escape($sEntryUri);
        $sqlQuery  = "SELECT COUNT(*) FROM `RayVideoFiles` WHERE `Uri` = '$sEntryUri'";
        return $this->oMigrationModule->_oDb->getOne($sqlQuery) ? true : false;
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
       if (!$this->oMigrationModule->_oDb->getOne($sqlQuery)) {
           $oAlbums->addObject($iAlbumId, $iFileId, true);
       }
    }

    /**
     * Function export photo file;
     *
     * @param  : $aFile (array) - array with medID and medExt elements;
     * @return : (boolean) ;
     */
    function _exportFile ($aFile = array())
    {
        $bOperationResult = true;

        $sSourcePath = $this->oMigrationModule->_oDb->getExtraParam('config_root') . $this->sOldFilesPath;
        $sNewDirectory = BX_DIRECTORY_PATH_ROOT . $this->sNewFilesPath;
        foreach ($this->aOldFileTypes as $sValue) {
            $sFile = $aFile['medID'] . $sValue;
            $sSourceFilePath = $sSourcePath . $sFile;
            if (file_exists($sSourceFilePath) && is_dir($sNewDirectory)) {
                if (!@copy($sSourceFilePath, $sNewDirectory . $sFile)) {
                    return false;
                }
                switch ($sValue) {
                    case '.jpg':
                        imageResize($sSourceFilePath, $sNewDirectory . $aFile['medID'] . $this->aNewSizes['thumb']['postfix'], $this->aNewSizes['thumb']['width'], $this->aNewSizes['thumb']['height'], true);
                        break;
                    case '.flv':
                        $this->generateNewFile($sSourceFilePath, $sNewDirectory . $aFile['medID'] . $this->aNewSizes['file']['postfix']);
                        break;
                }
            }
            else
                return false;
        }

        return $bOperationResult;
    }

    function generateNewFile ($sSource, $sNew) {
        global $sFfmpegPath;
        $sMobileCommand = getConverterTmpl($sSource, "qcif") . "-b 512000 -sameq -ab 64000 -acodec libfaac -ac 1 " . $sNew;
        popen($sMobileCommand, 'r');
    }

    /**
     * Function export all comments;
     *
     * @param     : $iFileId (integer) - file id;
     * @return : (string) - error message or empty;
     */
    function _exportComments ($iFileId) {
        $iFileId = (int)$iFileId;
        $sqlQuery = "SELECT * FROM `{$this->aOldTables['cmts']}` WHERE `cmt_object_id` = {$iFileId}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
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
                        `cmt_object_id`  = {$aRow['cmt_object_id']},
                        `cmt_author_id`  = {$aRow['cmt_author_id']},
                        `cmt_text`         = '{$sText}',
                        `cmt_rate`         = {$aRow['cmt_rate']},
                        `cmt_rate_count` = {$aRow['cmt_rate_count']},
                        `cmt_time`         = '{$aRow['cmt_time']}',
                        `cmt_replies`     = {$aRow['cmt_replies']}
                ";

                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot insert comments information in the database.';
                }
            }
        }
    }

    /**
     * Function export all votings;
     *
     * @param     : $iFileId (integer);
     * @return : (string) - error message or empty;
     */
    function _exportVotings ($iFileId) {
        $iFileId = (int)$iFileId;
        $sqlQuery = "SELECT * FROM `{$this->aOldTables['rate']}` WHERE `gal_id` = {$iFileId}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
               $sqlQuery =
               "
                   INSERT INTO
                       `{$this->sType}_rating`
                SET
                    `gal_id` = {$aRow['gal_id']},
                    `gal_rating_count` = {$aRow['gal_rating_count']},
                    `gal_rating_sum`   = {$aRow['gal_rating_sum']}
               ";

                $iResult = (int)$this->oMigrationModule->_oDb->query($sqlQuery);
                if ($iResult <= 0) {
                    return 'Database error. Cannot insert votes information in the database.';
                }

                //export voting tracks;
                $sError = $this->_exportVotingTrack($aRow['gal_id']);

                if ($sError) {
                    return $sError;
                }

                $fRate = $aRow['gal_rating_sum'] / $aRow['gal_rating_count'];
                //update primary table;
                $sqlQuery =
                "
                    UPDATE
                        `RayVideoFiles`
                    SET
                        `Rate` = $fRate,
                        `RateCount` = {$aRow['gal_rating_count']}
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
        $sqlQuery = "SELECT * FROM `{$this->aOldTables['rate_track']}` WHERE `gal_id` = {$iGalId}";
        $rResult = mysql_query($sqlQuery, $this->rOldDb);
        if ($rResult) {
            while ($aRow = mysql_fetch_assoc($rResult)) {
              $sqlQuery =
                "
                    INSERT INTO
                        `{$this->sType}_voting_track`
                    SET
                        `gal_id`   = {$aRow['gal_id']},
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
    function _exportFavorites ($iFileId) {
        $iFileId = (int)$iFileId;
        $sqlQuery = "SELECT `medID`, `userID`, UNIX_TIMESTAMP(`favDate`) as `favDate` FROM `{$this->aOldTables['favorite']}` WHERE `medID` = {$iFileId}";
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
}

?>
